<?php

namespace App\Http\Controllers;

use App\Models\CounselingRequest;
use App\Models\Schedule;
use App\Models\Report;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CounselorController extends Controller
{
    /**
     * Display Counselor Dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Stats summary
        $totalSessions = CounselingRequest::where('konselor_id', $user->user_id)->count();
        $activeSessions = CounselingRequest::where('konselor_id', $user->user_id)
            ->whereIn('status', ['diproses', 'dijadwalkan'])->count();
        $completedSessions = CounselingRequest::where('konselor_id', $user->user_id)
            ->where('status', 'selesai')->count();
        $pendingRequests = CounselingRequest::where('status', 'menunggu')
            ->where(function($query) use ($user) {
                $query->where('konselor_id', $user->user_id)
                      ->orWhereNull('konselor_id');
            })->count();

        // Recent active requests
        $recentRequests = CounselingRequest::where('konselor_id', $user->user_id)
            ->whereIn('status', ['diproses', 'dijadwalkan'])
            ->with(['konseli', 'schedule'])
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('counselor.dashboard', compact(
            'totalSessions', 
            'activeSessions', 
            'completedSessions', 
            'pendingRequests', 
            'recentRequests'
        ));
    }

    /**
     * Display incoming counseling requests.
     */
    public function requests()
    {
        $user = Auth::user();
        
        // Incoming requests: waiting and assigned to this counselor OR completely unassigned (null)
        $requests = CounselingRequest::where('status', 'menunggu')
            ->where(function($query) use ($user) {
                $query->where('konselor_id', $user->user_id)
                      ->orWhereNull('konselor_id');
            })
            ->with('konseli')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('counselor.requests', compact('requests'));
    }

    /**
     * Accept an incoming request.
     */
    public function acceptRequest($id)
    {
        $user = Auth::user();
        $counseling = CounselingRequest::where('request_id', $id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        $counseling->update([
            'konselor_id' => $user->user_id,
            'status' => 'diproses',
            'accepted_at' => now(),
        ]);

        // Notify Konseli
        Notification::create([
            'user_id' => $counseling->konseli_id,
            'request_id' => $counseling->request_id,
            'message' => 'Pengajuan konseling Anda telah diterima oleh konselor ' . $user->name . '.',
            'type' => 'success',
            'is_read' => false,
        ]);

        return redirect()->route('counselor.sessions')->with('success', 'Permintaan konseling berhasil diterima.');
    }

    /**
     * Reject or release an incoming request.
     */
    public function rejectRequest($id)
    {
        $counseling = CounselingRequest::where('request_id', $id)
            ->where('status', 'menunggu')
            ->firstOrFail();

        // If pre-assigned, release it to public by setting konselor_id to null
        if ($counseling->konselor_id) {
            $counseling->update([
                'konselor_id' => null
            ]);
            return redirect()->route('counselor.requests')->with('success', 'Permintaan konseling dilepaskan kembali ke publik.');
        } else {
            // Cancel request entirely
            $counseling->delete();
            return redirect()->route('counselor.requests')->with('success', 'Permintaan konseling berhasil ditolak.');
        }
    }

    /**
     * Display session management.
     */
    public function sessions()
    {
        $user = Auth::user();
        
        $sessions = CounselingRequest::where('konselor_id', $user->user_id)
            ->whereIn('status', ['diproses', 'dijadwalkan'])
            ->with(['konseli', 'schedule'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('counselor.sessions', compact('sessions'));
    }

    /**
     * Schedule a counseling session date and time.
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

        $user = Auth::user();
        $counseling = CounselingRequest::where('request_id', $id)
            ->where('konselor_id', $user->user_id)
            ->whereIn('status', ['diproses', 'dijadwalkan'])
            ->firstOrFail();

        // Create or update schedule
        Schedule::updateOrCreate(
            ['request_id' => $counseling->request_id],
            [
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'confirmed', // immediately set to confirmed
            ]
        );

        $counseling->update([
            'status' => 'dijadwalkan'
        ]);

        // Notify Konseli
        Notification::create([
            'user_id' => $counseling->konseli_id,
            'request_id' => $counseling->request_id,
            'message' => 'Jadwal konseling telah diatur pada ' . $request->date . ' pukul ' . $request->time . '.',
            'type' => 'info',
            'is_read' => false,
        ]);

        return redirect()->route('counselor.sessions')->with('success', 'Jadwal sesi konseling berhasil dikonfirmasi.');
    }

    /**
     * Complete a session and write report.
     */
    public function completeSession(Request $request, $id)
    {
        $request->validate([
            'summary' => 'required|string',
            'recommendation' => 'required|string',
            'case_level' => 'required|string',
        ], [
            'summary.required' => 'Ringkasan konseling wajib diisi.',
            'recommendation.required' => 'Rekomendasi wajib diisi.',
            'case_level.required' => 'Tingkat keparahan kasus wajib dipilih.',
        ]);

        $user = Auth::user();
        $counseling = CounselingRequest::where('request_id', $id)
            ->where('konselor_id', $user->user_id)
            ->where('status', 'dijadwalkan')
            ->firstOrFail();

        // Save report
        Report::create([
            'request_id' => $counseling->request_id,
            'counselor_id' => $user->user_id,
            'summary' => $request->summary,
            'recommendation' => $request->recommendation,
        ]);

        // Update schedule status
        if ($counseling->schedule) {
            $counseling->schedule->update(['status' => 'completed']);
        }

        // Update counseling status
        $counseling->update([
            'status' => 'selesai',
            'case_level' => $request->case_level,
            'completed_at' => now(),
        ]);

        // Notify Konseli
        Notification::create([
            'user_id' => $counseling->konseli_id,
            'request_id' => $counseling->request_id,
            'message' => 'Sesi konseling telah selesai. Rekomendasi laporan telah dikirim.',
            'type' => 'success',
            'is_read' => false,
        ]);

        return redirect()->route('counselor.sessions')->with('success', 'Sesi konseling telah selesai dan laporan disimpan.');
    }

    /**
     * Display counseling request details for counselor.
     */
    public function detail($id)
    {
        $user = Auth::user();
        
        $counseling = CounselingRequest::where('request_id', $id)
            ->where('konselor_id', $user->user_id)
            ->with(['konseli', 'schedule', 'reports'])
            ->firstOrFail();

        return view('counselor.detail', compact('counseling'));
    }
}
