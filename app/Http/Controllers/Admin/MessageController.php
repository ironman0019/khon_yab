<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Get unread message count for the admin.
     */
    public function unreadCount(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();
        $count = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Display a listing of the admin's conversations.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get all messages where user is sender or recipient
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id);
        })
            ->with(['sender', 'recipient'])
            ->latest()
            ->get();

        // Group messages by conversation partner
        $conversations = $messages->groupBy(function ($message) use ($user) {
            // Get the other user in the conversation
            return $message->sender_id === $user->id
                ? $message->recipient_id
                : $message->sender_id;
        })->map(function ($conversationMessages, $partnerId) use ($user) {
            $partner = User::find($partnerId);
            $latestMessage = $conversationMessages->first();
            $unreadCount = $conversationMessages->where('recipient_id', $user->id)
                ->where('is_read', false)
                ->count();

            return [
                'partner' => $partner,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
                'total_count' => $conversationMessages->count(),
            ];
        })->sortByDesc(function ($conversation) {
            return $conversation['latest_message']->created_at;
        })->values();

        return view('admin.messages.index', compact('conversations'));
    }

    /**
     * Display the conversation thread with a specific user.
     */
    public function show(User $user): View
    {
        $currentUser = Auth::user();

        // Ensure user is not viewing their own conversation
        if ($user->id === $currentUser->id) {
            abort(404);
        }

        // Get all messages between the two users
        $messages = Message::conversationWith($currentUser, $user)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all messages as read when viewing conversation
        Message::conversationWith($currentUser, $user)
            ->where('recipient_id', $currentUser->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        // Get all conversations for the left panel
        $allMessages = Message::where(function ($query) use ($currentUser) {
            $query->where('sender_id', $currentUser->id)
                ->orWhere('recipient_id', $currentUser->id);
        })
            ->with(['sender', 'recipient'])
            ->latest()
            ->get();

        $conversations = $allMessages->groupBy(function ($message) use ($currentUser) {
            return $message->sender_id === $currentUser->id
                ? $message->recipient_id
                : $message->sender_id;
        })->map(function ($conversationMessages, $partnerId) use ($currentUser) {
            $partner = User::find($partnerId);
            $latestMessage = $conversationMessages->first();
            $unreadCount = $conversationMessages->where('recipient_id', $currentUser->id)
                ->where('is_read', false)
                ->count();

            return [
                'partner' => $partner,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
                'total_count' => $conversationMessages->count(),
            ];
        })->sortByDesc(function ($conversation) {
            return $conversation['latest_message']->created_at;
        })->values();

        return view('admin.messages.show', compact('messages', 'user', 'conversations'));
    }

    /**
     * Show the form for creating a new message.
     */
    public function create(): View
    {
        return view('admin.messages.create');
    }

    /**
     * Store a newly created message.
     */
    public function store(StoreMessageRequest $request): RedirectResponse|JsonResponse
    {
        $sender = Auth::user();

        // Find recipient by email and user type
        $recipient = User::where('email', $request->recipient_email)
            ->where('user_type', $request->recipient_user_type)
            ->firstOrFail();

        // Check if this is a reply to an existing conversation
        $existingConversation = Message::conversationWith($sender, $recipient)
            ->exists();

        // Subject is only required for first message in conversation
        $subject = $request->subject;
        if (! $subject && ! $existingConversation) {
            $subject = __('No Subject');
        }

        $message = Message::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'subject' => $subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        $message->load(['sender', 'recipient']);

        // Get updated conversations for sidebar
        $conversations = $this->getConversations($sender);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'success_message' => __('admin.Message sent successfully.'),
                'conversations' => $conversations,
            ]);
        }

        return redirect()->route('admin.messages.show', $recipient->id)
            ->with('success', __('admin.Message sent successfully.'));
    }

    /**
     * Mark a specific message as read.
     */
    public function markAsRead(Message $message): RedirectResponse|JsonResponse
    {
        $user = Auth::user();

        // Ensure user can only mark their own received messages as read
        if ($message->recipient_id !== $user->id) {
            abort(403);
        }

        $message->markAsRead();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()
            ->with('success', __('Message marked as read.'));
    }

    /**
     * Mark all messages in a conversation as read.
     */
    public function markConversationAsRead(User $user): RedirectResponse|JsonResponse
    {
        $currentUser = Auth::user();

        // Ensure user is not marking their own conversation
        if ($user->id === $currentUser->id) {
            abort(404);
        }

        Message::conversationWith($currentUser, $user)
            ->where('recipient_id', $currentUser->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()
            ->with('success', __('All messages in conversation marked as read.'));
    }

    /**
     * Fetch messages for a conversation.
     */
    public function fetchMessages(User $user): JsonResponse
    {
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            return response()->json(['error' => 'Invalid conversation'], 404);
        }

        $sinceId = request()->get('since_id');

        $query = Message::conversationWith($currentUser, $user)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc');

        if ($sinceId) {
            $query->where('id', '>', $sinceId);
        }

        $messages = $query->get();

        // Mark new messages as read
        if ($messages->isNotEmpty()) {
            Message::conversationWith($currentUser, $user)
                ->where('recipient_id', $currentUser->id)
                ->where('is_read', false)
                ->whereIn('id', $messages->pluck('id'))
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
        }

        return response()->json([
            'messages' => $messages,
        ]);
    }

    /**
     * Fetch conversations list.
     */
    public function fetchConversations(): JsonResponse
    {
        $user = Auth::user();
        $conversations = $this->getConversations($user);

        return response()->json([
            'conversations' => $conversations,
        ]);
    }

    /**
     * Get conversations for a user.
     */
    private function getConversations(User $user): \Illuminate\Support\Collection
    {
        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id);
        })
            ->with(['sender', 'recipient'])
            ->latest()
            ->get();

        return $messages->groupBy(function ($message) use ($user) {
            return $message->sender_id === $user->id
                ? $message->recipient_id
                : $message->sender_id;
        })->map(function ($conversationMessages, $partnerId) use ($user) {
            $partner = User::find($partnerId);
            $latestMessage = $conversationMessages->first();
            $unreadCount = $conversationMessages->where('recipient_id', $user->id)
                ->where('is_read', false)
                ->count();

            return [
                'partner' => $partner,
                'latest_message' => $latestMessage,
                'unread_count' => $unreadCount,
                'total_count' => $conversationMessages->count(),
            ];
        })->sortByDesc(function ($conversation) {
            return $conversation['latest_message']->created_at;
        })->values();
    }
}
