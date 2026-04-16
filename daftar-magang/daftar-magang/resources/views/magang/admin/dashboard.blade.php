<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | PLN Suku Cadang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body class="flex flex-col min-h-screen text-slate-700">

    @php
        $dataChartJenis = [
            'fg'  => $total_fg ?? 0,
            'mhs' => $total_mhs ?? 0
        ];

        $dataChartStatus = [
            'menunggu'  => $total_menunggu ?? 0,
            'wawancara' => $total_wawancara ?? 0,
            'diterima'  => $total_diterima ?? 0,
            'ditolak'   => $total_ditolak ?? 0 
        ];
    @endphp

    <header class="bg-[#00675b] shadow-lg sticky top-0 z-50">
        <nav class="container mx-auto px-4 sm:px-6 py-3 sm:py-4 flex justify-between items-center">
            <div class="flex items-center gap-3 sm:gap-4">
                <img src="{{ asset('images/magang/Logo PLN Suku Cadang White FHD.png') }}" alt="Logo PLN" class="h-8 sm:h-10 w-auto object-contain">
                <span class="text-white font-bold text-base sm:text-lg tracking-wide hidden sm:block border-l border-white/20 pl-3 sm:pl-4">
                    Admin Portal
                </span>
            </div>

            {{-- Desktop Nav --}}
            <div class="hidden sm:flex items-center gap-4">
                <span class="text-white/80 text-sm">Halo, Administrator</span>
                
                <form action="{{ route('magang.admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-lg flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Logout
                    </button>
                </form>
            </div>

            {{-- Mobile Hamburger --}}
            <button type="button" onclick="toggleAdminNav()" class="sm:hidden text-white p-2 rounded-lg hover:bg-white/10 transition" aria-label="Toggle menu">
                <svg id="adminNavOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                <svg id="adminNavClose" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </nav>

        {{-- Mobile Dropdown --}}
        <div id="adminNavMenu" class="hidden sm:hidden border-t border-white/20 bg-[#004d44]">
            <div class="container mx-auto px-4 py-4 space-y-3">
                <span class="block text-white/80 text-sm px-4 py-2">👤 Halo, Administrator</span>
                <form action="{{ route('magang.admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2.5 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 sm:px-6 py-6 sm:py-10">
        
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-6 sm:mb-10 gap-4 sm:gap-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Manajemen Pendaftar</h1>
                <p class="text-slate-500 font-medium mt-1 text-sm sm:text-base">Pantau data magang mahasiswa secara real-time.</p>
            </div>
            
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('magang.admin.export', request()->all()) }}" class="bg-white border border-slate-300 text-slate-700 px-4 sm:px-5 py-2.5 rounded-xl font-bold hover:bg-slate-50 transition shadow-sm flex items-center justify-center gap-2 w-full sm:w-auto text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Export Excel
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 mb-4 sm:mb-6">
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between col-span-2 sm:col-span-1">
                <div>
                    <p class="text-slate-400 text-xs font-bold uppercase tracking-wider">Total Pelamar</p>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">{{ $total_pelamar }}</h3>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-purple-600/70 text-xs font-bold uppercase tracking-wider">Fresh Graduate</p>
                    <h3 class="text-3xl font-black text-purple-600 mt-1">{{ $total_fg ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-full flex items-center justify-center text-purple-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M12 14l9-5-9-5-9 5 9 5z" /><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" /></svg>
                </div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-blue-600/70 text-xs font-bold uppercase tracking-wider">Mahasiswa Aktif</p>
                    <h3 class="text-3xl font-black text-blue-600 mt-1">{{ $total_mhs ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-10">
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-yellow-600/70 text-xs font-bold uppercase tracking-wider">Perlu Review</p>
                    <h3 class="text-3xl font-black text-yellow-500 mt-1">{{ $total_menunggu }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center text-yellow-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-indigo-600/70 text-xs font-bold uppercase tracking-wider">Tahap Wawancara</p>
                    <h3 class="text-3xl font-black text-indigo-600 mt-1">{{ $total_wawancara ?? 0 }}</h3>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" /></svg>
                </div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between col-span-2 sm:col-span-1">
                <div>
                    <p class="text-green-600/70 text-xs font-bold uppercase tracking-wider">Lolos Seleksi</p>
                    <h3 class="text-3xl font-black text-[#00675b] mt-1">{{ $total_diterima }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-[#00675b]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-10">
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-slate-800 font-bold text-lg mb-4 border-b border-slate-100 pb-2">Komposisi Pelamar</h3>
                <div class="h-64 relative">
                    <canvas id="chartJenis"></canvas>
                </div>
            </div>
            <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-sm border border-slate-200">
                <h3 class="text-slate-800 font-bold text-lg mb-4 border-b border-slate-100 pb-2">Funnel Seleksi</h3>
                <div class="h-64 relative">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-200 overflow-hidden">
            
            <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="flex flex-col gap-4">
                    <h2 class="text-lg sm:text-xl font-bold text-slate-800">Daftar Pelamar</h2>
                    
                    <form action="{{ route('magang.admin.dashboard') }}" method="GET" class="flex flex-col sm:flex-row gap-2 sm:gap-3 w-full">
                        
                        <select name="filter_jenis" onchange="this.form.submit()" class="w-full sm:w-40 bg-white border border-slate-300 text-slate-600 text-sm rounded-xl focus:ring-[#00675b] focus:border-[#00675b] p-2.5 cursor-pointer hover:bg-slate-50 transition">
                            <option value="">Semua Tipe</option>
                            <option value="fresh_graduate" {{ request('filter_jenis') == 'fresh_graduate' ? 'selected' : '' }}>Fresh Graduate</option>
                            <option value="mahasiswa" {{ request('filter_jenis') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>

                        <select name="filter_status" onchange="this.form.submit()" class="w-full sm:w-40 bg-white border border-slate-300 text-slate-600 text-sm rounded-xl focus:ring-[#00675b] focus:border-[#00675b] p-2.5 cursor-pointer hover:bg-slate-50 transition">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" {{ request('filter_status') == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="Wawancara" {{ request('filter_status') == 'Wawancara' ? 'selected' : '' }}>Wawancara</option>
                            <option value="Diterima" {{ request('filter_status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                            <option value="Ditolak" {{ request('filter_status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>

                        <div class="relative w-full sm:w-64">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..." class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#00675b] focus:border-transparent transition text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute left-3 top-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        
                        @if(request('search') || request('filter_status') || request('filter_jenis'))
                            <a href="{{ route('magang.admin.dashboard') }}" class="bg-red-50 text-red-600 hover:bg-red-100 px-3 py-2.5 rounded-xl flex items-center justify-center transition" title="Reset Filter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[700px]">
                    <thead class="bg-slate-50 text-slate-500 font-bold text-xs uppercase tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 sm:py-4">Informasi Pelamar</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 hidden md:table-cell">Asal Kampus & Jurusan</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">Periode Magang</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-center">Status</th>
                            <th class="px-4 sm:px-6 py-3 sm:py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pendaftars as $p)
                        <tr class="hover:bg-slate-50/80 transition group">
                            
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-[#00675b]/10 text-[#00675b] flex items-center justify-center font-bold text-sm">
                                        {{ substr($p->nama, 0, 1) }} 
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-800 flex items-center gap-2">
                                            {{ $p->nama }}
                                            @if($p->jenis_magang == 'fresh_graduate')
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-purple-100 text-purple-700 border border-purple-200">FG</span>
                                            @else
                                                <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-100 text-blue-700 border border-blue-200">MHS</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $p->email }}</div>
                                        <div class="text-xs text-slate-400 mt-0.5">{{ $p->no_hp }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 sm:px-6 py-3 sm:py-4 hidden md:table-cell">
                                <div class="font-semibold text-slate-700">{{ $p->asal_kampus }}</div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $p->jurusan }} 
                                    @if($p->semester)
                                        <span class="text-slate-300">•</span> Sem. {{ $p->semester }}
                                    @endif
                                </div>
                            </td>

                            <td class="px-4 sm:px-6 py-3 sm:py-4 hidden lg:table-cell">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-md text-xs font-bold border border-slate-200 whitespace-nowrap">
                                    {{ $p->periode }}
                                </span>
                            </td>

                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-center">
                                @if($p->status == 'Menunggu')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-50 text-yellow-600 border border-yellow-200">Menunggu</span>
                                @elseif($p->status == 'Wawancara')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-600 border border-indigo-200">Wawancara</span>
                                @elseif($p->status == 'Diterima')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 border border-green-200 shadow-sm">Diterima</span>
                                @elseif($p->status == 'Ditolak')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 border border-red-200">Ditolak</span>
                                @endif
                            </td>

                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-center">
                                <div class="flex justify-center gap-2 flex-wrap">
                                <a href="https://wa.me/{{ $p->no_hp }}?text=Halo {{ $p->nama }}, terkait lamaran magang di PLN Suku Cadang. Diharapkan Membuka Cek Status di laman Resmi Pendatarn Magang PLNSC" target="_blank" 
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-200 transition" title="Hubungi via WhatsApp">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('magang.admin.detail', $p->id) }}" class="inline-block text-[#00675b] hover:text-white border border-[#00675b] hover:bg-[#00675b] px-3 sm:px-4 py-1.5 rounded-lg text-xs font-bold transition shadow-sm whitespace-nowrap">Detail</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                                <div class="mx-auto w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-3xl mb-4">📂</div>
                                <h3 class="text-slate-800 font-bold text-lg">Belum Ada Data</h3>
                                <p class="text-slate-500 text-sm">Tidak ada pelamar yang cocok dengan filter ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-slate-50 px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200 text-xs text-slate-500 overflow-x-auto">
                {{ $pendaftars->links() }}
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-slate-200 py-4 sm:py-6 text-center text-xs sm:text-sm text-slate-500 mt-auto">
        &copy; {{ date('Y') }} PLN Suku Cadang. Admin Portal.
    </footer>

    <script>
    // ================================
    // 1. Inject Data dari Laravel
    // ================================
    const dataJenis = {!! json_encode($dataChartJenis) !!};
    const dataStatus = {!! json_encode($dataChartStatus) !!};

    // ================================
    // 2. Doughnut Chart - Jenis Magang
    // ================================
    const ctxJenis = document.getElementById('chartJenis');

    if (ctxJenis) {
        new Chart(ctxJenis, {
            type: 'doughnut',
            data: {
                labels: ['Fresh Graduate', 'Mahasiswa Aktif'],
                datasets: [{
                    data: [
                        dataJenis.fg ?? 0,
                        dataJenis.mhs ?? 0
                    ],
                    backgroundColor: [
                        'rgba(147, 51, 234, 0.8)', // Ungu
                        'rgba(37, 99, 235, 0.8)'   // Biru
                    ],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // ================================
    // 3. Bar Chart - Status Seleksi
    // ================================
    const ctxStatus = document.getElementById('chartStatus');

    if (ctxStatus) {
        new Chart(ctxStatus, {
            type: 'bar',
            data: {
                labels: ['Menunggu', 'Wawancara', 'Diterima', 'Ditolak'],
                datasets: [{
                    label: 'Jumlah Kandidat',
                    data: [
                        dataStatus.menunggu ?? 0,
                        dataStatus.wawancara ?? 0,
                        dataStatus.diterima ?? 0,
                        dataStatus.ditolak ?? 0
                    ],
                    backgroundColor: [
                        'rgba(234, 179, 8, 0.8)',  // Kuning
                        'rgba(79, 70, 229, 0.8)',  // Indigo
                        'rgba(22, 163, 74, 0.8)',  // Hijau
                        'rgba(220, 38, 38, 0.8)'   // Merah
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { display: false }
                    },
                    x: {
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }
</script>

<script>
    function toggleAdminNav() {
        const menu = document.getElementById('adminNavMenu');
        const iconOpen = document.getElementById('adminNavOpen');
        const iconClose = document.getElementById('adminNavClose');
        menu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    }
</script>

</body>
</html>