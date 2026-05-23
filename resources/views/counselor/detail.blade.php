@extends('layouts.app')

@section('title', 'Detail Konseling - FILKOM Sebaya')

@push('styles')
    @vite(['resources/css/counselor.css'])
@endpush

@section('content')
    <!-- Horizontal Topbar Navbar -->
    <nav class="navbar-container">
        <div style="display: flex; align-items: center; gap: 40px;">
            <div class="navbar-logo" style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo" style="height: 32px; border-radius: 6px;">
                <span style="font-weight: 700; color: #0f2942; font-size: 18px;">FILKOM Sebaya</span>
            </div>
            <div class="navbar-menu" style="display: flex; gap: 28px;">
                <a href="{{ route('counselor.dashboard') }}" class="navbar-link">Dashboard</a>
                <a href="{{ route('counselor.requests') }}" class="navbar-link">Permintaan Masuk</a>
                <a href="{{ route('counselor.sessions') }}" class="navbar-link">Manajemen Sesi</a>
            </div>
        </div>
        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="secondary-button" style="border-radius: 6px; padding: 8px 24px; font-size: 13px; color: #0f2942; border: 1.5px solid #e2e8f0; font-weight: 600; background-color: #ffffff;">Keluar</button>
            </form>
        </div>
    </nav>

    <!-- Top Banner Jumbotron (Full Width) -->
    <section class="jumbotron-banner">
        <h1 style="font-size: 48px; font-weight: 800; color: #ffffff; margin-bottom: 12px;">Detail Kasus Bimbingan</h1>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.85); max-width: 700px; margin: 0 auto; line-height: 1.6;">Informasi status kasus dan perkembangan bimbingan mahasiswa</p>
    </section>

    <!-- Content Container -->
    <div class="portal-container">
        
        <!-- Back Link -->
        <div style="margin-bottom: 32px; text-align: left;">
            <a href="{{ route('counselor.sessions') }}" style="font-size: 14px; color: #0f2942; font-weight: 700; display: inline-flex; align-items: center; gap: 6px; text-decoration: underline;">&larr; Kembali ke Sesi Aktif</a>
        </div>

        <div class="counselor-detail">
            
            <!-- Case File Information -->
            <div style="background-color: #ffffff; border-radius: var(--radius-md); padding: 40px; border: 1px solid var(--border-color); box-shadow: var(--shadow-card);">
                <h3 style="font-size: 20px; font-weight: 800; color: #0f2942; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 24px;">Dokumen Kasus Bimbingan</h3>

                <div style="margin-bottom: 20px;">
                    <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Topik Bimbingan</span>
                    <div style="font-size: 20px; font-weight: 800; color: #0f2942;">{{ $counseling->topic }}</div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
                    <div>
                        <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Nama Konseli</span>
                        <div style="font-weight: 700; color: #0f2942; font-size: 15px;">{{ $counseling->konseli->name }}</div>
                        <div style="font-size: 12px; color: #64748b;">NIM: {{ $counseling->konseli->nim }}</div>
                    </div>
                    <div>
                        <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Kategori Masalah</span>
                        <div style="font-weight: 700; color: #0f2942; font-size: 15px;">{{ $counseling->category }}</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
                    <div>
                        <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Status</span>
                        <span class="status-badge {{ $counseling->status }}">{{ $counseling->status }}</span>
                    </div>
                    <div>
                        <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Tingkat Kerawanan</span>
                        <span class="status-badge {{ $counseling->case_level ? 'completed' : 'pending' }}">{{ $counseling->case_level ?? 'Belum ditentukan' }}</span>
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Deskripsi Kebutuhan</span>
                    <div style="background-color: var(--bg-main); padding: 16px; border-radius: 4px; font-size: 14px; color: #64748b; line-height: 1.6; border: 1px solid #f1f5f9;">
                        {{ $counseling->description }}
                    </div>
                </div>

                @if($counseling->problem_description)
                    <div style="margin-bottom: 24px;">
                        <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Detail Tambahan Kasus</span>
                        <div style="background-color: var(--bg-main); padding: 16px; border-radius: 4px; font-size: 14px; color: #64748b; line-height: 1.6; border: 1px solid #f1f5f9;">
                            {{ $counseling->problem_description }}
                        </div>
                    </div>
                @endif

                @if($counseling->reports->count() > 0)
                    <div style="border-top: 1.5px solid #f1f5f9; padding-top: 24px; margin-top: 32px;">
                        <h4 style="font-size: 15px; color: var(--primary-green-dark); font-weight: 800; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 18px; height: 18px; display: inline-block; vertical-align: middle;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Laporan Bimbingan & Rekomendasi
                        </h4>
                        @foreach($counseling->reports as $report)
                            <div style="margin-bottom: 16px;">
                                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Ringkasan Sesi</span>
                                <p style="font-size: 14px; color: #64748b; margin-top: 4px; line-height: 1.6;">{{ $report->summary }}</p>
                            </div>
                            <div>
                                <span style="font-size: 11px; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px; display: block; margin-bottom: 4px;">Rekomendasi Tindak Lanjut</span>
                                <p style="font-size: 14px; color: #64748b; margin-top: 4px; line-height: 1.6;">{{ $report->recommendation }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Case Progress Timeline -->
            <div style="display: flex; flex-direction: column; gap: 24px;">
                <div style="background-color: #ffffff; border-radius: var(--radius-md); padding: 40px; border: 1px solid var(--border-color); box-shadow: var(--shadow-card);">
                    <h3 style="font-size: 20px; font-weight: 800; color: #0f2942; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 24px;">Timeline Status</h3>

                    <div style="position: relative; padding-left: 24px; margin-top: 16px;">
                        <div style="position: absolute; top: 8px; left: 4px; bottom: 8px; width: 2px; background-color: var(--border-accent);"></div>
                        
                        <!-- Step 1: Menunggu -->
                        <div style="position: relative; padding-bottom: 24px;">
                            <div style="position: absolute; left: -24px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background-color: var(--primary-green); border: 2px solid #ffffff; box-shadow: 0 0 0 2px var(--primary-green-light);"></div>
                            <div style="padding-left: 8px;">
                                <div style="font-size: 14px; font-weight: 700; color: var(--primary-green-dark);">Pengajuan Diajukan Konseli</div>
                                <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">{{ $counseling->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        </div>

                        <!-- Step 2: Diproses -->
                        <div style="position: relative; padding-bottom: 24px;">
                            <div style="position: absolute; left: -24px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background-color: {{ in_array($counseling->status, ['diproses', 'dijadwalkan', 'selesai']) ? 'var(--primary-green)' : 'var(--text-light)' }}; border: 2px solid #ffffff; box-shadow: 0 0 0 2px {{ in_array($counseling->status, ['diproses', 'dijadwalkan', 'selesai']) ? 'var(--primary-green-light)' : 'var(--border-accent)' }};"></div>
                            <div style="padding-left: 8px;">
                                <div style="font-size: 14px; font-weight: 700; color: {{ in_array($counseling->status, ['diproses', 'dijadwalkan', 'selesai']) ? 'var(--primary-green-dark)' : 'var(--text-muted)' }};">Diterima & Diproses</div>
                                <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">
                                    @if($counseling->accepted_at)
                                        {{ $counseling->accepted_at->format('d M Y, H:i') }}
                                    @else
                                        Menunggu disetujui
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Dijadwalkan -->
                        <div style="position: relative; padding-bottom: 24px;">
                            <div style="position: absolute; left: -24px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background-color: {{ in_array($counseling->status, ['dijadwalkan', 'selesai']) ? 'var(--primary-green)' : 'var(--text-light)' }}; border: 2px solid #ffffff; box-shadow: 0 0 0 2px {{ in_array($counseling->status, ['dijadwalkan', 'selesai']) ? 'var(--primary-green-light)' : 'var(--border-accent)' }};"></div>
                            <div style="padding-left: 8px;">
                                <div style="font-size: 14px; font-weight: 700; color: {{ in_array($counseling->status, ['dijadwalkan', 'selesai']) ? 'var(--primary-green-dark)' : 'var(--text-muted)' }};">Pertemuan Dijadwalkan</div>
                                <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">
                                    @if($counseling->schedule)
                                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 4px; color: var(--text-light);"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>{{ $counseling->schedule->date }} pukul {{ $counseling->schedule->time }}
                                    @else
                                        Pertemuan belum dijadwalkan
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Selesai -->
                        <div style="position: relative; padding-bottom: 0;">
                            <div style="position: absolute; left: -24px; top: 4px; width: 10px; height: 10px; border-radius: 50%; background-color: {{ $counseling->status === 'selesai' ? 'var(--primary-green)' : 'var(--text-light)' }}; border: 2px solid #ffffff; box-shadow: 0 0 0 2px {{ $counseling->status === 'selesai' ? 'var(--primary-green-light)' : 'var(--border-accent)' }};"></div>
                            <div style="padding-left: 8px;">
                                <div style="font-size: 14px; font-weight: 700; color: {{ $counseling->status === 'selesai' ? 'var(--primary-green-dark)' : 'var(--text-muted)' }};">Bimbingan Selesai & Laporan Ditulis</div>
                                <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">
                                    @if($counseling->completed_at)
                                        {{ $counseling->completed_at->format('d M Y, H:i') }}
                                    @else
                                        Menunggu bimbingan rampung
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer -->
    <footer style="background-color: #ffffff; padding: 40px 120px; border-top: 1.5px solid #f1f5f9; text-align: center; font-size: 13px; color: #94a3b8; font-weight: 500; margin-top: 80px;">
        &copy; 2025 FILKOM Sebaya
    </footer>
@endsection
