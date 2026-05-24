@extends('layouts.app')

@section('title', 'Detail Konseling - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/konseli.css'])
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
                <a href="{{ route('konseli.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('konseli.create') }}" class="navbar-link">Ajukan Konseling</a>
                <a href="{{ route('konseli.history') }}" class="navbar-link">Riwayat</a>
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
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Detail Sesi Konseling</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Informasi lengkap perkembangan pengajuan bimbingan Anda secara real-time</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <!-- Back Link -->
        <div style="margin-bottom: 32px; text-align: left;">
            <a href="{{ route('konseli.dashboard') }}" style="font-size: 14px; color: #0f2942; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; text-decoration: underline;">&larr; Kembali ke Dashboard</a>
        </div>

        <div class="detail-grid">
            
            <!-- Information Section -->
            <div class="detail-section" style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 40px; box-shadow: var(--shadow-card);">
                <h3 class="detail-title" style="font-size: 20px; font-weight: 800; color: #0f2942; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 24px;">Informasi Konseling</h3>

                <div class="detail-item">
                    <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 6px;">Topik Permasalahan</div>
                    <div class="detail-value" style="font-size: 20px; font-weight: 800; color: #0f2942;">{{ $counseling->topic }}</div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                    <div class="detail-item">
                        <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 6px;">Kategori</div>
                        <div class="detail-value" style="font-weight: 600; color: #0f2942;">{{ $counseling->category }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 6px;">Status Saat Ini</div>
                        <div class="detail-value">
                            <span class="status-badge {{ $counseling->status }}">{{ $counseling->status }}</span>
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                    <div class="detail-item">
                        <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 6px;">Konselor Pendamping</div>
                        <div class="detail-value" style="font-weight: 600; color: #0f2942;">{{ $counseling->counselor ? $counseling->counselor->name : 'Dalam Proses Pencarian' }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 6px;">No. Telepon Konselor</div>
                        <div class="detail-value" style="color: #64748b;">{{ $counseling->counselor ? ($counseling->counselor->phone ?? 'Belum dicantumkan') : '-' }}</div>
                    </div>
                </div>

                <div class="detail-item" style="margin-bottom: 24px;">
                    <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 6px;">Ringkasan Kebutuhan Konseling</div>
                    <div class="detail-value" style="background-color: var(--bg-main); padding: 16px; border-radius: 4px; font-weight: normal; color: #64748b; line-height: 1.6; border: 1px solid #f1f5f9;">
                        {{ $counseling->description }}
                    </div>
                </div>

                @if($counseling->problem_description)
                    <div class="detail-item" style="margin-bottom: 24px;">
                        <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 6px;">Detail Permasalahan Tambahan</div>
                        <div class="detail-value" style="background-color: var(--bg-main); padding: 16px; border-radius: 4px; font-weight: normal; color: #64748b; line-height: 1.6; border: 1px solid #f1f5f9;">
                            {{ $counseling->problem_description }}
                        </div>
                    </div>
                @endif

                @if($counseling->reports->count() > 0)
                    <div class="detail-item" style="margin-top: 32px; border-top: 1.5px solid #f1f5f9; padding-top: 24px;">
                        <h4 style="font-size: 16px; color: var(--primary-green-dark); font-weight: 800; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Laporan & Rekomendasi Konseling
                        </h4>
                        @foreach($counseling->reports as $report)
                            <div style="margin-bottom: 16px;">
                                <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 4px;">Ringkasan Pertemuan</div>
                                <p style="font-size: 14px; color: #64748b; margin-top: 4px; line-height: 1.6;">{{ $report->summary }}</p>
                            </div>
                            <div>
                                <div class="detail-label" style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; margin-bottom: 4px;">Rekomendasi Tindak Lanjut</div>
                                <p style="font-size: 14px; color: #64748b; margin-top: 4px; line-height: 1.6;">{{ $report->recommendation }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Timeline Status Section -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div class="timeline-container" style="background-color: #ffffff; border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 40px; box-shadow: var(--shadow-card);">
                    <h3 class="detail-title" style="font-size: 20px; font-weight: 800; color: #0f2942; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 24px;">Timeline Status</h3>
                    
                    <div class="timeline">
                        <!-- Step 1: Menunggu -->
                        <div class="timeline-step {{ $counseling->status === 'menunggu' ? 'active' : 'completed' }}">
                            <div class="timeline-bullet"></div>
                            <div class="timeline-content">
                                <div class="timeline-title" style="font-weight: 700;">Pengajuan Terkirim</div>
                                <div class="timeline-time">
                                    {{ $counseling->created_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Diproses -->
                        <div class="timeline-step {{ $counseling->status === 'diproses' ? 'active' : (in_array($counseling->status, ['dijadwalkan', 'selesai']) ? 'completed' : '') }}">
                            <div class="timeline-bullet"></div>
                            <div class="timeline-content">
                                <div class="timeline-title" style="font-weight: 700;">Diterima & Diproses Konselor</div>
                                @if($counseling->accepted_at)
                                    <div class="timeline-time">
                                        {{ $counseling->accepted_at->format('d M Y, H:i') }}
                                    </div>
                                @else
                                    <div class="timeline-time">Menunggu persetujuan konselor sebaya</div>
                                @endif
                            </div>
                        </div>

                        <!-- Step 3: Dijadwalkan -->
                        <div class="timeline-step {{ $counseling->status === 'dijadwalkan' ? 'active' : ($counseling->status === 'selesai' ? 'completed' : '') }}">
                            <div class="timeline-bullet"></div>
                            <div class="timeline-content">
                                <div class="timeline-title" style="font-weight: 700;">Pertemuan Dijadwalkan</div>
                                @if($counseling->schedule)
                                    <div class="timeline-time" style="font-weight: 700; color: #0f2942; margin-top: 4px; display: flex; align-items: center; gap: 4px;">
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $counseling->schedule->date }} Pukul {{ $counseling->schedule->time }}
                                    </div>
                                @else
                                    <div class="timeline-time">Jadwal pertemuan belum ditentukan</div>
                                @endif
                            </div>
                        </div>

                        <!-- Step 4: Selesai -->
                        <div class="timeline-step {{ $counseling->status === 'selesai' ? 'completed' : '' }}">
                            <div class="timeline-bullet"></div>
                            <div class="timeline-content">
                                <div class="timeline-title" style="font-weight: 700;">Sesi Selesai</div>
                                @if($counseling->completed_at)
                                    <div class="timeline-time">
                                        {{ $counseling->completed_at->format('d M Y, H:i') }}
                                    </div>
                                @else
                                    <div class="timeline-time">Menunggu sesi diselesaikan</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-container" style="background-color: #e0f2fe; border-color: rgba(2, 132, 199, 0.1); border-radius: var(--radius-md); padding: 24px;">
                    <h4 style="color: #0369a1; font-weight: 700; margin-bottom: 8px;">Butuh Pertolongan Segera?</h4>
                    <p style="font-size: 13px; color: #0369a1; opacity: 0.9; line-height: 1.6;">Jika Anda mengalami situasi darurat medis atau psikologis yang mendesak, silakan hubungi unit layanan darurat UB atau pusat kesehatan terdekat segera.</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
