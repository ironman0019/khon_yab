<?php

namespace App\Http\Controllers\Admin\ContactMessageManagement;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of contact messages.
     */
    public function index(Request $request): View
    {
        $query = ContactMessage::query();

        if ($request->filled('search')) {
            $search = trim($request->get('search'));
            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            }
        }

        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'unread') {
                $query->where('is_read', false);
            } elseif ($status === 'read') {
                $query->where('is_read', true);
            }
        }

        $contactMessages = $query->latest()->paginate(15)->withQueryString();

        return view('admin.contact-message-management.index', compact('contactMessages'));
    }

    /**
     * Display the specified contact message.
     */
    public function show(ContactMessage $contact_message): View
    {
        $contact_message->markAsRead();

        return view('admin.contact-message-management.show', [
            'contactMessage' => $contact_message,
        ]);
    }

    /**
     * Mark the contact message as unread.
     */
    public function markUnread(ContactMessage $contact_message): RedirectResponse
    {
        $contact_message->markAsUnread();

        return redirect()
            ->route('admin.contact-message-management.show', ['contact_message' => $contact_message])
            ->with('success', __('admin.Contact message marked as unread.'));
    }

    /**
     * Remove the specified contact message.
     */
    public function destroy(ContactMessage $contact_message): RedirectResponse
    {
        $contact_message->delete();

        return redirect()
            ->route('admin.contact-message-management.index')
            ->with('success', __('admin.Contact message deleted successfully.'));
    }
}
