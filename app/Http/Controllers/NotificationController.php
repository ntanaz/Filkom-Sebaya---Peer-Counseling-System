<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use App\Models\CounselingRequest;
use App\Notifications\StatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the notifications.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Notification::where('user_id', $user->user_id);

        // Filtering
        $filter = $request->query('filter', 'all');
        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        return view('notifications.index', compact('notifications', 'user', 'filter'));
    }

    /**
     * Mark a specific notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', $user->user_id)
            ->firstOrFail();

        $notification->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai dibaca.');
    }

    /**
     * Helper to dispatch status/schedule notifications and trigger professional email.
     */
    public static function sendNotification($userId, $requestId, $message, $type)
    {
        $notification = Notification::create([
            'user_id' => $userId,
            'request_id' => $requestId,
            'message' => $message,
            'type' => $type,
            'is_read' => false,
        ]);

        // Send integrated email notification using Laravel Notification
        $user = User::find($userId);
        $counseling = CounselingRequest::find($requestId);
        if ($user && $counseling && ($type === 'status' || $type === 'schedule')) {
            try {
                // Get the raw status or mapped status
                $statusName = $counseling->getRawOriginal('status') ?? $counseling->status;
                
                // Map the status database value to a professional Indonesian string for the email
                $statusMapping = [
                    'pending' => 'Menunggu Persetujuan',
                    'accepted' => 'Diterima / Dijadwalkan',
                    'rescheduled' => 'Jadwal Diubah',
                    'cancelled' => 'Dibatalkan',
                    'completed' => 'Selesai',
                ];
                
                $statusLabel = $statusMapping[$statusName] ?? ucfirst($statusName);
                
                $user->notify(new StatusUpdatedNotification(
                    $user->name,
                    $counseling->topic,
                    $statusLabel,
                    $user->email
                ));
            } catch (\Exception $e) {
                // Fail-gracefully if mail driver is not configured
            }
        }

        return $notification;
    }
}
