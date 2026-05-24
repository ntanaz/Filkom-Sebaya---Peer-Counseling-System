<?php

namespace App\Http\Controllers;

use App\Models\CounselingRequest;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Create a new schedule for counseling session.
     */
    public function createSchedule(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
        ], [
            'date.required' => 'Tanggal sesi wajib diisi.',
            'date.after_or_equal' => 'Tanggal sesi tidak boleh di masa lalu.',
            'time.required' => 'Waktu sesi wajib diisi.',
        ]);

        $counselor = Auth::user();
        $counseling = CounselingRequest::where('request_id', $id)
            ->where('konselor_id', $counselor->user_id)
            ->firstOrFail();

        // Clash Check: Is this counselor busy at the selected date and time?
        $clash = Schedule::where('date', $request->date)
            ->where('time', $request->time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereHas('counselingRequest', function ($q) use ($counselor) {
                $q->where('konselor_id', $counselor->user_id);
            })->exists();

        if ($clash) {
            return redirect()->back()->withInput()->with('error', 'Gagal: Jadwal bentrok dengan sesi konseling lain pada waktu tersebut.');
        }

        // Create or update schedule
        Schedule::updateOrCreate(
            ['request_id' => $counseling->request_id],
            [
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'confirmed',
            ]
        );

        // Update counseling request status
        $counseling->update([
            'status' => 'accepted', // maps to 'dijadwalkan' in accessor
        ]);

        // Send Notification and Email
        NotificationController::sendNotification(
            $counseling->konseli_id,
            $counseling->request_id,
            'Jadwal konseling berhasil dibuat pada ' . $request->date . ' pukul ' . $request->time . '.',
            'schedule'
        );

        return redirect()->route('counselor.sessions')->with('success', 'Jadwal sesi konseling berhasil dikonfirmasi.');
    }

    /**
     * Reschedule an existing session schedule.
     */
    public function reschedule(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:counseling_requests,request_id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'reschedule_reason' => 'required|string',
        ], [
            'date.required' => 'Tanggal sesi baru wajib diisi.',
            'date.after_or_equal' => 'Tanggal sesi baru tidak boleh di masa lalu.',
            'time.required' => 'Waktu sesi baru wajib diisi.',
            'reschedule_reason.required' => 'Alasan reschedule wajib diisi.',
        ]);

        $counselor = Auth::user();
        $counseling = CounselingRequest::where('request_id', $request->request_id)
            ->where('konselor_id', $counselor->user_id)
            ->firstOrFail();

        // Clash Check: Is this counselor busy at the selected date and time?
        $clash = Schedule::where('date', $request->date)
            ->where('time', $request->time)
            ->where('request_id', '!=', $counseling->request_id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereHas('counselingRequest', function ($q) use ($counselor) {
                $q->where('konselor_id', $counselor->user_id);
            })->exists();

        if ($clash) {
            return redirect()->back()->withInput()->with('error', 'Gagal: Jadwal bentrok dengan sesi konseling lain pada waktu tersebut.');
        }

        // Update schedule details
        $schedule = Schedule::updateOrCreate(
            ['request_id' => $counseling->request_id],
            [
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'confirmed',
                'reschedule_reason' => $request->reschedule_reason,
                'rescheduled_at' => now(),
            ]
        );

        // Update counseling request status
        $counseling->update([
            'status' => 'rescheduled', // stored as rescheduled in database
        ]);

        // Send Notification and Email to Konseli
        NotificationController::sendNotification(
            $counseling->konseli_id,
            $counseling->request_id,
            'Jadwal sesi konseling Anda telah diubah oleh konselor menjadi ' . $request->date . ' pukul ' . $request->time . '. Alasan: ' . $request->reschedule_reason,
            'status'
        );

        return redirect()->route('counselor.detail', $counseling->request_id)->with('success', 'Jadwal sesi konseling berhasil di-reschedule.');
    }

    /**
     * Cancel an active session.
     */
    public function cancel(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:counseling_requests,request_id',
        ]);

        $counselor = Auth::user();
        $counseling = CounselingRequest::where('request_id', $request->request_id)
            ->where('konselor_id', $counselor->user_id)
            ->firstOrFail();

        // Update schedule status to cancelled
        if ($counseling->schedule) {
            $counseling->schedule->update(['status' => 'cancelled']);
        }

        // Update counseling status
        $counseling->update([
            'status' => 'cancelled', // database cancelled maps to dibatalkan
        ]);

        // Send Notification and Email to Konseli
        NotificationController::sendNotification(
            $counseling->konseli_id,
            $counseling->request_id,
            'Sesi konseling Anda telah dibatalkan oleh konselor.',
            'status'
        );

        return redirect()->route('counselor.sessions')->with('success', 'Sesi konseling berhasil dibatalkan.');
    }
}
