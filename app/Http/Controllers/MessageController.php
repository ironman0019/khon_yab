<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the user's conversations.
     */
    public function index(): \Illuminate\View\View
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

        return view('messages.index', compact('conversations'));
    }

    /**
     * Display the conversation thread with a specific user.
     */
    public function show(User $user): \Illuminate\View\View
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

        return view('messages.show', compact('messages', 'user'));
    }

    /**
     * Store a newly created message.
     */
    public function store(StoreMessageRequest $request): RedirectResponse
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

        return redirect()->route('messages.show', $recipient->id)
            ->with('success', __('Message sent successfully.'));
    }

    /**
     * Mark a specific message as read.
     */
    public function markAsRead(Message $message): RedirectResponse
    {
        $user = Auth::user();

        // Ensure user can only mark their own received messages as read
        if ($message->recipient_id !== $user->id) {
            abort(403);
        }

        $message->markAsRead();

        return redirect()->back()
            ->with('success', __('Message marked as read.'));
    }

    /**
     * Mark all messages in a conversation as read.
     */
    public function markConversationAsRead(User $user): RedirectResponse
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

        return redirect()->back()
            ->with('success', __('All messages in conversation marked as read.'));
    }
}
