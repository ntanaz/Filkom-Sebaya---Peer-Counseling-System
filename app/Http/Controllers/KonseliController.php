<?php

namespace App\Http\Controllers;

use App\Models\CounselingRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KonseliController extends Controller
{
    /**
     * Display Konseli Dashboard.
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Fetch active requests (status is not selesai)
        $activeRequests = CounselingRequest::where('konseli_id', $user->user_id)
            ->whereIn('status', ['menunggu', 'diproses', 'dijadwalkan'])
            ->with(['counselor', 'schedule'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch notifications
        $notifications = Notification::where('user_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('konseli.dashboard', compact('activeRequests', 'notifications'));
    }

    /**
     * Show form to submit counseling request.
     */
    public function create()
    {
        $counselors = User::where('role', 'counselor')->get();
        return view('konseli.create', compact('counselors'));
    }

    /**
     * Store new counseling request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'topic' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'required|string',
            'problem_description' => 'nullable|string',
            'konselor_id' => 'nullable|exists:users,user_id',
        ], [
            'topic.required' => 'Topik pengajuan wajib diisi.',
            'category.required' => 'Kategori pengajuan wajib diisi.',
            'description.required' => 'Deskripsi wajib diisi.',
        ]);

        $user = Auth::user();

        $counselingRequest = CounselingRequest::create([
            'konseli_id' => $user->user_id,
            'konselor_id' => $request->konselor_id, // If null, auto-assigned or selected by counselor later
            'topic' => $request->topic,
            'description' => $request->description,
            'category' => $request->category,
            'status' => 'menunggu',
            'problem_description' => $request->problem_description,
        ]);

        // Create success notification for user
        Notification::create([
            'user_id' => $user->user_id,
            'request_id' => $counselingRequest->request_id,
            'message' => 'Pengajuan berhasil dikirim.',
            'type' => 'success',
            'is_read' => false,
        ]);

        // Notify counselor if pre-selected
        if ($request->konselor_id) {
            Notification::create([
                'user_id' => $request->konselor_id,
                'request_id' => $counselingRequest->request_id,
                'message' => 'Ada permintaan konseling masuk untuk Anda dari ' . $user->name . '.',
                'type' => 'request',
                'is_read' => false,
            ]);
        }

        return redirect()->route('konseli.dashboard')->with('success', 'Pengajuan berhasil dikirim.');
    }

    /**
     * Display Counseling History.
     */
    public function history()
    {
        $user = Auth::user();
        
        $historyRequests = CounselingRequest::where('konseli_id', $user->user_id)
            ->where('status', 'selesai')
            ->with(['counselor', 'reports'])
            ->orderBy('completed_at', 'desc')
            ->get();

        return view('konseli.history', compact('historyRequests'));
    }

    /**
     * Display counseling details and status timeline.
     */
    public function detail($id)
    {
        $user = Auth::user();
        
        $counseling = CounselingRequest::where('request_id', $id)
            ->where('konseli_id', $user->user_id)
            ->with(['counselor', 'schedule', 'reports'])
            ->firstOrFail();

        return view('konseli.detail', compact('counseling'));
    }
}
