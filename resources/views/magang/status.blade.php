@extends('magang.layout.app')

@section('title', 'Status Lamaran - PLN Suku Cadang')

@section('content')
@php
    // --- 1. MENANGKAP DATA DARI DATABASE ---
    if(!isset($pendaftar)) {
        // Placeholder jika diakses langsung tanpa data
        $namaPelamar = "Tamu";
        $posisi      = "IT Support"; 
        $statusDB    = "Menunggu"; 
        $tglDaftar   = date('d M Y');
        $wawancara   = null;
    } else {
        // Data Asli
        $namaPelamar = $pendaftar->nama;
        $posisi      = "Magang - " . ($pendaftar->jurusan ?? 'Umum'); 
        $statusDB    = $pendaftar->status;
        $tglDaftar   = $pendaftar->created_at->format('d M Y');
        $wawancara   = $pendaftar->wawancara_waktu; // Cek apakah ada jadwal
    }

    // --- 2. LOGIKA TIMELINE DINAMIS (UPDATE FITUR WAWANCARA) ---
    
    // Default Status (Baru Daftar)
    $step1_status = 'completed'; // Pendaftaran
    $step2_status = 'current';   // Seleksi Berkas
    $step3_status = 'upcoming';  // Wawancara
    $step4_status = 'upcoming';  // Pengumuman

    if ($statusDB == 'Wawancara') {
        // Sedang tahap wawancara
        $step1_status = 'completed';
        $step2_status = 'completed';
        $step3_status = 'current';  // Highlight di Wawancara
        $step4_status = 'upcoming';
    } 
    elseif ($statusDB == 'Diterima') {
        // Semua Selesai Sukses
        $step1_status = 'completed';
        $step2_status = 'completed';
        $step3_status = 'completed';
        $step4_status = 'completed';
    } 
    elseif ($statusDB == 'Ditolak') {
        // Gagal (Berhenti di tahap terakhir)
        $step1_status = 'completed';
        $step2_status = 'completed';
        $step3_status = 'upcoming'; 
        $step4_status = 'upcoming';
    }
    
    // Timeline Data
    $timeline = [
        ["step" => "Pendaftaran", "status" => $step1_status, "date" => $tglDaftar],
        ["step" => "Seleksi Berkas", "status" => $step2_status, "date" => ($statusDB == 'Menunggu' ? "Sedang Berjalan" : "Selesai")], 
        ["step" => "Wawancara User", "status" => $step3_status, "date" => ($statusDB == 'Wawancara' ? "Dijadwalkan" : "-")],
        ["step" => "Pengumuman Final", "status" => $step4_status, "date" => ($statusDB == 'Diterima' ? date('d M Y') : "-")],
    ];
    
    // --- 3. LOGIKA BADGE STATUS ---
    $statusClass = "bg-yellow-50 text-yellow-600 border border-yellow-200"; 
    $statusLabel = "Menunggu Review";

    if ($statusDB == "Wawancara") {
        $statusClass = "bg-blue-50 text-blue-600 border border-blue-200 shadow-sm";
        $statusLabel = "Tahap Wawancara";
    } elseif ($statusDB == "Diterima") {
        $statusClass = "bg-pln-green text-white border border-pln-green shadow-md";
        $statusLabel = "Diterima";
    } elseif ($statusDB == "Ditolak") {
        $statusClass = "bg-red-100 text-red-600 border border-red-200";
        $statusLabel = "Tidak Lolos";
    }
@endphp

<main class="container mx-auto px-3 sm:px-4 py-6 sm:py-10 max-w-4xl flex-grow">
    
    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8 mb-6 sm:mb-8 border-l-8 border-pln-green relative overflow-hidden animate-fade-in-up">
        <div class="absolute top-0 right-0 w-32 h-32 bg-pln-yellow opacity-5 rounded-bl-full"></div>

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Halo, {{ $namaPelamar }}!</h2>
                <p class="text-slate-500 mt-1">Posisi: <span class="font-semibold text-pln-green">{{ $posisi }}</span></p>
            </div>
            
            <div class="text-left md:text-right">
                <span class="block text-xs text-slate-400 mb-1 font-semibold uppercase tracking-wider">Status Terkini</span>
                <span class="inline-block px-6 py-2 rounded-full text-sm font-bold {{ $statusClass }}">
                    {{ $statusLabel }}
                </span>
            </div>
        </div>
    </div>

    @if(isset($pendaftar) && $pendaftar->status == 'Diterima' && $pendaftar->pesan)
    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8 animate-fade-in-up">
        <div class="flex items-start gap-4">
            <div class="bg-green-100 text-green-600 p-3 rounded-full hidden sm:block">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
            </div>
            <div class="flex-grow">
                <h3 class="text-lg font-bold text-green-800 mb-2">Pesan Dari HRD</h3>
                <div class="text-sm text-slate-700 bg-white p-4 rounded-lg border border-green-100 shadow-sm leading-relaxed italic">
                    "{{ $pendaftar->pesan }}"
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(isset($pendaftar) && $pendaftar->status == 'Wawancara' && $pendaftar->wawancara_waktu)
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.1s">
        <div class="flex items-start gap-4">
            <div class="bg-blue-100 text-blue-600 p-3 rounded-full hidden sm:block">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div class="flex-grow">
                <h3 class="text-lg font-bold text-blue-800 mb-1">Undangan Wawancara</h3>
                <p class="text-blue-600 text-sm mb-4">Selamat! Anda lolos tahap berkas. Silakan hadiri wawancara berikut:</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-4 rounded-lg border border-blue-100 shadow-sm">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Waktu</p>
                        <p class="font-bold text-slate-800 text-lg">
                            {{ $pendaftar->wawancara_waktu->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-sm text-slate-600">Pukul {{ $pendaftar->wawancara_waktu->format('H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase">Lokasi / Link</p>
                        <p class="font-bold text-slate-800 text-base break-all">
                            {{ $pendaftar->wawancara_lokasi }}
                        </p>
                    </div>
                </div>

                @if($pendaftar->pesan)
                    <div class="mt-4 text-sm text-slate-600 bg-white p-3 rounded border border-slate-100 italic">
                        <span class="font-bold not-italic text-slate-800">Pesan Admin:</span> "{{ $pendaftar->pesan }}"
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 md:p-8 border border-slate-100 animate-fade-in-up" style="animation-delay: 0.2s">
        <h3 class="text-lg font-bold text-slate-800 mb-8 pb-4 border-b border-slate-100">Timeline Seleksi</h3>
        
        <div class="space-y-0">
            @foreach ($timeline as $index => $item)
                <div class="flex gap-6 pb-8 last:pb-0 relative">
                    @if ($index < count($timeline) - 1)
                        <div class="absolute left-[15px] top-8 bottom-0 w-0.5 bg-gray-200"></div>
                    @endif

                    <div class="relative z-10 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300
                        {{ $item['status'] == 'completed' ? 'bg-pln-green text-white ring-4 ring-green-50' : 
                          ($item['status'] == 'current' ? 'bg-white border-2 border-pln-green text-pln-green ring-4 ring-green-50' : 'bg-gray-100 text-gray-400 border border-gray-200') }}">
                        {{ $item['status'] == 'completed' ? '✓' : $index + 1 }}
                    </div>

                    <div class="{{ $item['status'] == 'upcoming' ? 'opacity-50' : '' }}">
                        <h4 class="font-bold text-base sm:text-lg text-slate-800 {{ $item['status'] == 'current' ? 'text-pln-green' : '' }}">
                            {{ $item['step'] }}
                        </h4>
                        <p class="text-sm text-slate-500">{{ $item['date'] }}</p>
                        
                        @if ($item['status'] == 'current')
                            <p class="text-sm text-pln-green mt-2 font-medium bg-green-50 inline-block px-3 py-1 rounded-md animate-pulse">
                                @if($statusDB == 'Ditolak')
                                    Mohon maaf, Anda belum lolos tahap ini.
                                @elseif($statusDB == 'Wawancara')
                                    Silakan cek jadwal wawancara di atas.
                                @else
                                    Sedang diproses oleh tim HR.
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 sm:mt-10 pt-6 sm:pt-8 border-t border-slate-100">
            <div class="bg-white border border-slate-100 rounded-2xl p-8 text-center shadow-sm relative group overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-pln-light/30 rounded-full transition-transform group-hover:scale-150 duration-700"></div>
                <p class="relative z-10 text-slate-700 font-bold text-xl block leading-relaxed">
                    @if($statusDB == 'Diterima')
                        Selamat bergabung! Cek email Anda untuk info selanjutnya.
                    @elseif($statusDB == 'Ditolak')
                        Tetap semangat! Masih banyak kesempatan lain menanti.
                    @elseif($statusDB == 'Wawancara')
                        Persiapkan diri Anda sebaik mungkin untuk wawancara!
                    @else
                        Semoga hasilnya memuaskan kamu ya!
                    @endif
                </p>
                <div class="relative z-10 flex items-center justify-center gap-2 mt-3">
                    <span class="h-px w-8 bg-slate-200"></span>
                    <p class="text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold">Recruitment Team</p>
                    <span class="h-px w-8 bg-slate-200"></span>
                </div>
            </div>

            <div class="text-center mt-6">
                <a href="{{ route('welcome') }}" class="inline-flex items-center gap-2 text-slate-500 font-semibold hover:text-pln-green transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
