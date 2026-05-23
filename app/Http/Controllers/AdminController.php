<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CounselingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display Admin Dashboard with summary statistics.
     */
    public function dashboard()
    {
        // Statistics Summary
        $countKonseli = User::where('role', 'konseli')->count();
        $countCounselor = User::where('role', 'counselor')->count();
        $countAdmin = User::where('role', 'admin')->count();

        $reqMenunggu = CounselingRequest::where('status', 'menunggu')->count();
        $reqDiproses = CounselingRequest::where('status', 'diproses')->count();
        $reqDijadwalkan = CounselingRequest::where('status', 'dijadwalkan')->count();
        $reqSelesai = CounselingRequest::where('status', 'selesai')->count();

        $totalRequests = CounselingRequest::count();

        // Recent counseling requests
        $recentRequests = CounselingRequest::with(['konseli', 'counselor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'countKonseli', 
            'countCounselor', 
            'countAdmin', 
            'reqMenunggu', 
            'reqDiproses', 
            'reqDijadwalkan', 
            'reqSelesai', 
            'totalRequests',
            'recentRequests'
        ));
    }

    /**
     * List all users for management.
     */
    public function users(Request $request)
    {
        $query = User::query();

        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name', 'asc')->get();

        return view('admin.users', compact('users'));
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:konseli,counselor,admin',
            'nim' => [
                'nullable', 
                'string', 
                Rule::requiredIf($request->role === 'konseli'), 
                'unique:users,nim'
            ],
            'phone' => 'nullable|string',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'nim.required' => 'NIM wajib diisi bagi mahasiswa (Konseli).',
            'nim.unique' => 'NIM sudah terdaftar.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'nim' => $request->role === 'konseli' ? $request->nim : null,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Update an existing user.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->user_id, 'user_id')],
            'role' => 'required|in:konseli,counselor,admin',
            'nim' => [
                'nullable', 
                'string', 
                Rule::requiredIf($request->role === 'konseli'), 
                Rule::unique('users')->ignore($user->user_id, 'user_id')
            ],
            'phone' => 'nullable|string',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'nim.required' => 'NIM wajib diisi bagi mahasiswa (Konseli).',
            'nim.unique' => 'NIM sudah digunakan.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'nim' => $request->role === 'konseli' ? $request->nim : null,
            'phone' => $request->phone,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Soft delete a user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->user_id === auth()->user()->user_id) {
            return redirect()->route('admin.users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Pengguna berhasil dihapus.');
    }
}
