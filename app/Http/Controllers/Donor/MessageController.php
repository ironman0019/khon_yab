<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMessageRequest;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of the donor's conversations.
     */
    public function index(): View
    {
        $user = Auth::user();

        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('recipient_id', $user->id);
        })
            ->with(['sender', 'recipient'])
            ->latest()
            ->get();

        $conversations = $messages->groupBy(function ($message) use ($user) {
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

        return view('donor.messages.index', compact('conversations'));
    }

    /**
     * Display the conversation thread with a specific user.
     */
    public function show(User $user): View
    {
        $currentUser = Auth::user();

        if ($user->id === $currentUser->id) {
            abort(404);
        }

        $messages = Message::conversationWith($currentUser, $user)
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

        Message::conversationWith($currentUser, $user)
            ->where('recipient_id', $currentUser->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('donor.messages.show', compact('messages', 'user'));
    }

    /**
     * Show the form for creating a new message.
     */
    public function create(): View
    {
        return view('donor.messages.create');
    }

    /**
     * Store a newly created message.
     */
    public function store(StoreMessageRequest $request): RedirectResponse
    {
        $sender = Auth::user();

        // Handle admin selection (value = -1)
        if ($request->recipient_user_type == -1) {
            $recipient = User::where('email', $request->recipient_email)
                ->where('is_admin', true)
                ->firstOrFail();
        } else {
            $recipient = User::where('email', $request->recipient_email)
                ->where('user_type', $request->recipient_user_type)
                ->firstOrFail();
        }

        $existingConversation = Message::conversationWith($sender, $recipient)
            ->exists();

        $subject = $request->subject;
        if (! $subject && ! $existingConversation) {
            $subject = __('No Subject');
        }

        Message::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'subject' => $subject,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return redirect()->route('donor.messages.show', $recipient->id)
            ->with('success', __('Message sent successfully.'));
    }
}
