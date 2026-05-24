@extends('layouts.app')

@section('title', 'Permintaan Masuk - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/counselor.css'])
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
                <a href="{{ route('counselor.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('counselor.requests') }}" class="navbar-link active">Permintaan Masuk</a>
                <a href="{{ route('counselor.sessions') }}" class="navbar-link">Manajemen Sesi</a>
            </div>
        </div>
        <div class="navbar-right-actions">
            <a href="{{ route('notifications.index') }}" class="notification-bell-link" title="Notifikasi">
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
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Permintaan Masuk</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Terima pengajuan konseling dari mahasiswa yang memerlukan bimbingan sebaya</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <div class="request-list">
            <h3 style="font-size: 18px; font-weight: 800; color: #0f2942; margin-bottom: 24px; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 12px;">Daftar Permintaan Konseling Masuk</h3>

            @if($requests->count() > 0)
                <div style="overflow-x: auto;">
                    <table class="requests-table">
                        <thead>
                            <tr>
                                <th>NIM / Nama Mahasiswa</th>
                                <th>Kategori</th>
                                <th>Topik</th>
                                <th>Deskripsi Masalah</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $req)
                                <tr>
                                    <td style="vertical-align: top; width: 220px;">
                                        <div style="font-weight: 700; color: #0f2942;">{{ $req->konseli->name }}</div>
                                        <div style="font-size: 12px; color: var(--text-light); margin-top: 2px;">NIM: {{ $req->konseli->nim }}</div>
                                    </td>
                                    <td style="vertical-align: top; width: 120px;">
                                        <span class="status-badge diproses" style="background-color: var(--primary-blue-light); color: var(--primary-blue-dark); font-size: 11px;">{{ $req->category }}</span>
                                    </td>
                                    <td style="vertical-align: top; font-weight: 700; color: #0f2942; width: 220px;">
                                        {{ $req->topic }}
                                    </td>
                                    <td style="vertical-align: top; color: var(--text-muted); font-size: 13px;">
                                        {{ Str::limit($req->description, 100) }}
                                    </td>
                                    <td style="text-align: right; vertical-align: top; width: 180px;">
                                        <div style="display: inline-flex; gap: 8px;">
                                            <!-- Reject Form -->
                                            <form action="{{ route('counselor.reject', $req->request_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="secondary-button" style="padding: 8px 12px; font-size: 12px; border-color: var(--danger); color: var(--danger); font-weight: 700; border-radius: 4px;" onclick="return confirm('Apakah Anda yakin ingin menolak permintaan ini?')">Tolak</button>
                                            </form>

                                            <!-- Accept Form -->
                                            <form action="{{ route('counselor.accept', $req->request_id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="primary-button" style="padding: 8px 12px; font-size: 12px; background-color: #0f2942; background-image: none; box-shadow: none; border-radius: 4px; font-weight: 700;">Terima</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state" style="padding: 56px 0;">
                    <div class="empty-state-icon" style="color: var(--text-light); display: flex; justify-content: center; margin-bottom: 12px;">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"></path></svg>
                    </div>
                    <p style="font-weight: 500; color: #64748b;">Belum ada data tersedia.</p>
                    <p style="font-size: 13px; color: #94a3b8;">Tidak ada permintaan konseling masuk yang menunggu persetujuan saat ini.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
