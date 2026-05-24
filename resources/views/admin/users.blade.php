@extends('layouts.app')

@section('title', 'Manajemen Pengguna - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/admin.css'])
@endpush

@section('content')
    <!-- Horizontal Topbar Navbar -->
    <nav class="navbar-container">
        <div class="navbar-brand-group">
            <div class="navbar-logo-wrap">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo" class="navbar-logo-img">
                <span class="navbar-logo-text">FILKOM Sebaya</span>
            </div>
            <div class="navbar-menu">
                <a href="{{ route('admin.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('admin.users') }}" class="navbar-link active">Manajemen User</a>
            </div>
        </div>
        <div class="navbar-right-actions">
            <a href="{{ route('notifications.index') }}" class="notification-bell-link" title="Notifikasi Sistem">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" class="icon-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"></path>
                </svg>
                @php
                    $unreadNotificationsCount = \App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count();
                @endphp
                @if($unreadNotificationsCount > 0)
                    <span class="notification-bell-badge">
                        {{ $unreadNotificationsCount }}
                    </span>
                @endif
            </a>
            <form action="{{ route('logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="navbar-logout-btn">Keluar</button>
            </form>
        </div>
    </nav>



    <!-- Top Banner Jumbotron (Full Width) -->
    <section class="jumbotron-banner">
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Manajemen Pengguna</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Tambah, edit, atau nonaktifkan akun Konseli, Counselor, dan Admin</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <div class="user-management">
            
            <!-- Management Header Action -->
            <div class="management-header">
                
                <!-- Filter Form -->
                <form action="{{ route('admin.users') }}" method="GET" class="management-filters">
                    <input type="text" name="search" value="{{ request('search') }}" class="input-field" placeholder="Cari berdasarkan nama, email, atau NIM..." style="max-width: 280px; border-radius: 4px; border: 1.5px solid #cbd5e1;">
                    <select name="role" class="input-field" style="max-width: 160px; cursor: pointer; border-radius: 4px; border: 1.5px solid #cbd5e1;">
                        <option value="">-- Semua Role --</option>
                        <option value="konseli" {{ request('role') === 'konseli' ? 'selected' : '' }}>Konseli</option>
                        <option value="counselor" {{ request('role') === 'counselor' ? 'selected' : '' }}>Counselor</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <button type="submit" class="primary-button" style="padding: 10px 16px; background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; font-weight: 700;">Filter</button>
                    @if(request('search') || request('role'))
                        <a href="{{ route('admin.users') }}" class="secondary-button" style="padding: 10px 16px; border-radius: 4px; border: 1.5px solid #cbd5e1; color: #0f2942; font-weight: 700;">Reset</a>
                    @endif
                </form>

                <!-- Add User Button -->
                <button onclick="toggleAddModal(true)" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; padding: 12px 24px; font-weight: 700;">+ Tambah Pengguna Baru</button>
            </div>

            <!-- User Data Table -->
            <div style="overflow-x: auto;">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>NIM / Identitas</th>
                            <th>Nama Lengkap</th>
                            <th>Alamat Email</th>
                            <th>No. Telepon</th>
                            <th>Role</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <span style="font-family: monospace; font-weight: 600; font-size: 13px;">{{ $user->nim ?? '-' }}</span>
                                </td>
                                <td style="font-weight: 700; color: #0f2942;">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <span class="status-badge {{ $user->role === 'admin' ? 'selesai' : ($user->role === 'counselor' ? 'diproses' : 'menunggu') }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <!-- Edit Action Button -->
                                        <button onclick="openEditModal({{ json_encode($user) }})" class="secondary-button" style="padding: 6px 12px; font-size: 12px; border-color: #0f2942; color: #0f2942; font-weight: 700; border-radius: 4px;">Edit</button>

                                        <!-- Delete Action Button -->
                                        @if($user->user_id !== Auth::user()->user_id)
                                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                @csrf
                                                <button type="submit" class="secondary-button" style="padding: 6px 12px; font-size: 12px; border-color: var(--danger); color: var(--danger); font-weight: 700; border-radius: 4px;">Hapus</button>
                                            </form>
                                        @else
                                            <button class="secondary-button" style="padding: 6px 12px; font-size: 12px; opacity: 0.5; cursor: not-allowed; border-radius: 4px;" disabled>Self</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--text-light); padding: 48px;">
                                    Belum ada data tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- =================================================
    MODAL: ADD NEW USER
    ================================================= -->
    <div class="modal-backdrop" id="add-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Pengguna Baru</h3>
                <span class="modal-close" onclick="toggleAddModal(false)">&times;</span>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="add-name">Nama Lengkap</label>
                    <input type="text" name="name" id="add-name" class="input-field" placeholder="Contoh: Budi Santoso" required style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="add-email">Alamat Email</label>
                    <input type="email" name="email" id="add-email" class="input-field" placeholder="budi@student.ub.ac.id" required style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="add-password">Kata Sandi</label>
                    <input type="password" name="password" id="add-password" class="input-field" placeholder="Minimal 6 karakter" required style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="add-role">Role Akses</label>
                    <select name="role" id="add-role" class="input-field" onchange="toggleNimField(this.value, 'add-nim-group')" required style="border-radius: 4px; border: 1.5px solid #cbd5e1; cursor: pointer;">
                        <option value="konseli">Konseli (Mahasiswa)</option>
                        <option value="counselor">Counselor (Konselor Sebaya)</option>
                        <option value="admin">Admin System</option>
                    </select>
                </div>

                <div class="form-group" id="add-nim-group">
                    <label for="add-nim">Nomor Induk Mahasiswa (NIM)</label>
                    <input type="text" name="nim" id="add-nim" class="input-field" placeholder="15 Digit NIM UB" style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="add-phone">Nomor Telepon / WA (Opsional)</label>
                    <input type="text" name="phone" id="add-phone" class="input-field" placeholder="Contoh: 08123456789" style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <button type="button" class="secondary-button" onclick="toggleAddModal(false)" style="border-radius: 4px; font-weight: 700;">Batal</button>
                    <button type="submit" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; font-weight: 700;">Simpan Pengguna</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =================================================
    MODAL: EDIT USER
    ================================================= -->
    <div class="modal-backdrop" id="edit-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Edit Pengguna</h3>
                <span class="modal-close" onclick="toggleEditModal(false)">&times;</span>
            </div>

            <form id="edit-form" action="" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="edit-name">Nama Lengkap</label>
                    <input type="text" name="name" id="edit-name" class="input-field" required style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="edit-email">Alamat Email</label>
                    <input type="email" name="email" id="edit-email" class="input-field" required style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="edit-password">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" id="edit-password" class="input-field" placeholder="Minimal 6 karakter" style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="edit-role">Role Akses</label>
                    <select name="role" id="edit-role" class="input-field" onchange="toggleNimField(this.value, 'edit-nim-group')" required style="border-radius: 4px; border: 1.5px solid #cbd5e1; cursor: pointer;">
                        <option value="konseli">Konseli (Mahasiswa)</option>
                        <option value="counselor">Counselor (Konselor Sebaya)</option>
                        <option value="admin">Admin System</option>
                    </select>
                </div>

                <div class="form-group" id="edit-nim-group">
                    <label for="edit-nim">Nomor Induk Mahasiswa (NIM)</label>
                    <input type="text" name="nim" id="edit-nim" class="input-field" style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div class="form-group">
                    <label for="edit-phone">Nomor Telepon / WA (Opsional)</label>
                    <input type="text" name="phone" id="edit-phone" class="input-field" style="border-radius: 4px; border: 1.5px solid #cbd5e1;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
                    <button type="button" class="secondary-button" onclick="toggleEditModal(false)" style="border-radius: 4px; font-weight: 700;">Batal</button>
                    <button type="submit" class="primary-button" style="background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; font-weight: 700;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>

    <script>
        function toggleAddModal(show) {
            document.getElementById('add-modal').style.display = show ? 'flex' : 'none';
            if (show) {
                // default role is konseli, so show NIM field
                toggleNimField('konseli', 'add-nim-group');
            }
        }

        function toggleEditModal(show) {
            document.getElementById('edit-modal').style.display = show ? 'flex' : 'none';
        }

        function openEditModal(user) {
            const form = document.getElementById('edit-form');
            form.action = `/admin/pengguna/edit/${user.user_id}`;
            
            document.getElementById('edit-name').value = user.name;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-role').value = user.role;
            document.getElementById('edit-phone').value = user.phone || '';
            document.getElementById('edit-nim').value = user.nim || '';
            document.getElementById('edit-password').value = '';
            
            toggleNimField(user.role, 'edit-nim-group');
            toggleEditModal(true);
        }

        function toggleNimField(role, elementId) {
            const element = document.getElementById(elementId);
            if (role === 'konseli') {
                element.style.display = 'flex';
            } else {
                element.style.display = 'none';
            }
        }
    </script>
@endsection
