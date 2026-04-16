<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pelamar - {{ $pendaftar->nama }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f1f5f9; }
        .modal { transition: opacity 0.25s ease; }
        html, body { overflow-x: hidden; max-width: 100vw; }
    </style>
</head>
<body class="text-slate-800 min-h-screen pb-6 sm:pb-10">

    <nav class="bg-[#00675b] text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center gap-3 sm:gap-4">
            <a href="{{ route('magang.admin.dashboard') }}" class="flex items-center gap-2 hover:bg-white/10 px-2 sm:px-3 py-1.5 rounded-lg transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="font-medium text-sm hidden sm:inline">Kembali ke Dashboard</span>
            </a>
            <div class="h-6 w-px bg-white/30"></div>
            <span class="font-bold text-base sm:text-lg tracking-wide truncate">Detail Pelamar</span>
        </div>
    </nav>

    <div class="container mx-auto px-3 sm:px-6 py-4 sm:py-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-6 md:p-8 mb-4 sm:mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 sm:gap-6">
            <div class="flex items-start gap-4 sm:gap-6">
                <div class="w-14 h-14 sm:w-20 sm:h-20 bg-slate-100 rounded-full flex items-center justify-center text-xl sm:text-2xl font-bold text-[#00675b] border-4 border-slate-50 shadow-inner shrink-0">
                    {{ substr($pendaftar->nama, 0, 1) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-1">
                        <h1 class="text-xl sm:text-2xl font-bold text-slate-800">{{ $pendaftar->nama }}</h1>
                        
                        @if($pendaftar->jenis_magang == 'fresh_graduate')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                🎓 Fresh Graduate
                            </span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                🏫 Mahasiswa Aktif
                            </span>
                            
                            @if($pendaftar->tipe_mahasiswa == 'pkl')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                    PKL/Wajib
                                </span>
                            @elseif($pendaftar->tipe_mahasiswa == 'riset')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-teal-100 text-teal-700 border border-teal-200">
                                    Riset/Penelitian
                                </span>
                            @endif
                        @endif
                    </div>

                    <p class="text-slate-500 text-sm mt-1 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        {{ $pendaftar->email }}
                    </p>
                    <p class="text-slate-500 text-sm mt-1 flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        <a href="https://wa.me/{{ $pendaftar->no_hp }}" target="_blank" class="hover:text-[#00675b] hover:underline transition">
                            {{ $pendaftar->no_hp }}
                        </a>
                    </p>
                </div>
            </div>

            <div class="text-right">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-2">Status Saat Ini</p>
                @if($pendaftar->status == 'Menunggu')
                    <span class="px-4 py-2 rounded-lg bg-yellow-100 text-yellow-800 font-bold border border-yellow-200">Menunggu Review</span>
                @elseif($pendaftar->status == 'Wawancara')
                    <span class="px-4 py-2 rounded-lg bg-blue-100 text-blue-800 font-bold border border-blue-200">Tahap Wawancara</span>
                @elseif($pendaftar->status == 'Diterima')
                    <span class="px-4 py-2 rounded-lg bg-green-100 text-green-800 font-bold border border-green-200">✓ Diterima</span>
                @elseif($pendaftar->status == 'Ditolak')
                    <span class="px-4 py-2 rounded-lg bg-red-100 text-red-800 font-bold border border-red-200">✕ Ditolak</span>
                @endif
                <p class="text-xs text-slate-400 mt-2">Daftar: {{ $pendaftar->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            
            <div class="lg:col-span-2 space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Data Akademik</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase">Asal Kampus</label>
                            <p class="text-slate-700 font-medium mt-1">{{ $pendaftar->asal_kampus }}</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase">Jurusan</label>
                            <p class="text-slate-700 font-medium mt-1">{{ $pendaftar->jurusan }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase">IPK Terakhir</label>
                            <p class="text-slate-700 font-medium mt-1">{{ $pendaftar->ipk ?? 'Tidak ada data' }}</p>
                        </div>

                        @if($pendaftar->jenis_magang == 'mahasiswa')
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase">Semester</label>
                            <p class="text-slate-700 font-medium mt-1">Semester {{ $pendaftar->semester }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase">Rencana Periode</label>
                            <p class="text-slate-700 font-medium mt-1">{{ $pendaftar->periode }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Alamat Domisili</h3>
                    <p class="text-slate-600 leading-relaxed">{{ $pendaftar->alamat }}</p>
                </div>

            </div>

            <div class="space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 sm:p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">Berkas Lampiran</h3>
                    
                    <div class="space-y-4">
                        
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="bg-red-100 text-red-600 p-2 rounded text-xs font-bold">PDF</div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Surat Permohonan</p>
                                </div>
                            </div>
                            @if($pendaftar->surat_permohonan_path)
                                <a href="{{ asset('storage/' . $pendaftar->surat_permohonan_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Lihat &rarr;</a>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="bg-red-100 text-red-600 p-2 rounded text-xs font-bold">PDF</div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Curriculum Vitae</p>
                                </div>
                            </div>
                            <a href="{{ asset('storage/' . $pendaftar->cv_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Lihat &rarr;</a>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="bg-red-100 text-red-600 p-2 rounded text-xs font-bold">PDF</div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Transkrip Nilai</p>
                                </div>
                            </div>
                            @if($pendaftar->transkrip_path)
                                <a href="{{ asset('storage/' . $pendaftar->transkrip_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Lihat &rarr;</a>
                            @else
                                <span class="text-slate-400 text-xs italic">-</span>
                            @endif
                        </div>

                        @if($pendaftar->jenis_magang == 'mahasiswa')
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-100 text-blue-600 p-2 rounded text-xs font-bold">PDF</div>
                                <div>
                                    <p class="text-sm font-bold text-slate-700">Surat Pengantar</p>
                                    <p class="text-xs text-blue-500 font-semibold">Dari Kampus</p>
                                </div>
                            </div>
                            @if($pendaftar->surat_path)
                                <a href="{{ asset('storage/' . $pendaftar->surat_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-semibold">Lihat &rarr;</a>
                            @else
                                <span class="text-slate-400 text-xs italic">Tidak ada</span>
                            @endif
                        </div>
                        @endif

                        @if(is_array($pendaftar->dokumen_pendukung_path) && count($pendaftar->dokumen_pendukung_path) > 0)
                            <div class="mt-4 pt-4 border-t border-slate-100">
                                <p class="text-xs font-bold text-slate-400 uppercase mb-3">Dokumen Tambahan ({{ count($pendaftar->dokumen_pendukung_path) }})</p>
                                
                                <div class="space-y-2">
                                    @foreach($pendaftar->dokumen_pendukung_path as $index => $path)
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-100">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-green-100 text-green-600 p-2 rounded text-xs font-bold">PDF</div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700">Lampiran {{ $index + 1 }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-green-600 hover:text-green-800 text-sm font-semibold">Lihat &rarr;</a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                        <h3 class="text-lg font-bold text-slate-800">Validasi & Keputusan</h3>
                        <p class="text-xs text-slate-500">Tentukan tahap selanjutnya untuk pelamar ini.</p>
                    </div>

                    <div class="p-4 sm:p-6 space-y-6">
                        
                        @if(session('success'))
                            <div class="p-3 bg-green-100 text-green-700 rounded-lg text-sm border border-green-200">{{ session('success') }}</div>
                        @endif

                        @if($pendaftar->status == 'Menunggu' || $pendaftar->status == 'Wawancara')
                        <div class="border border-blue-100 bg-blue-50/50 rounded-xl p-5">
                            <h4 class="font-bold text-blue-800 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> Jadwalkan Wawancara
                            </h4>
                            <form action="{{ route('magang.admin.update', $pendaftar->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="status" value="Wawancara">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div><label class="text-xs font-bold text-slate-500 uppercase">Waktu</label><input type="datetime-local" name="tgl_wawancara" required value="{{ $pendaftar->wawancara_waktu ? $pendaftar->wawancara_waktu->format('Y-m-d\TH:i') : '' }}" class="w-full mt-1 border-slate-300 rounded-lg text-sm focus:ring-blue-500"></div>
                                    <div><label class="text-xs font-bold text-slate-500 uppercase">Lokasi/Link</label><input type="text" name="lokasi" required value="{{ $pendaftar->wawancara_lokasi }}" class="w-full mt-1 border-slate-300 rounded-lg text-sm focus:ring-blue-500"></div>
                                </div>
                                <div><label class="text-xs font-bold text-slate-500 uppercase">Pesan</label><textarea name="pesan" rows="2" class="w-full mt-1 border-slate-300 rounded-lg text-sm focus:ring-blue-500">{{ $pendaftar->pesan }}</textarea></div>
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg text-sm">Kirim Undangan</button>
                            </form>
                        </div>
                        @endif

                        <hr class="border-slate-100">

                        <div>
                            <h4 class="font-bold text-slate-800 mb-3">Keputusan Final</h4>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" onclick="openModalTerima()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition text-sm flex justify-center items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Terima Magang
                                </button>

                                <form action="{{ route('magang.admin.update', $pendaftar->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" onclick="return confirm('Yakin ingin MENOLAK kandidat ini?')" class="w-full bg-white border-2 border-red-100 text-red-600 hover:bg-red-50 font-bold py-3 rounded-lg transition text-sm flex justify-center items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Tolak Lamaran
                                    </button>
                                </form>
                            </div>
                            
                            <div class="mt-3 text-center">
                                 <form action="{{ route('magang.admin.update', $pendaftar->id) }}" method="POST">
                                    @csrf <button type="submit" name="status" value="Menunggu" class="text-xs text-slate-400 hover:text-slate-600 underline">Reset status ke Menunggu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="modalTerima" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModalTerima()"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                    <div class="bg-green-600 px-4 py-4 sm:px-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">Konfirmasi Penerimaan</h3>
                        <button onclick="closeModalTerima()" class="text-green-200 hover:text-white">✕</button>
                    </div>
                    <div class="px-4 py-6 sm:px-6">
                        <form action="{{ route('magang.admin.update', $pendaftar->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Diterima">
                            <div class="mb-4">
                                <p class="text-sm text-slate-600 mb-4">Anda akan menerima <strong>{{ $pendaftar->nama }}</strong>. Silakan tulis pesan sambutan.</p>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pesan Untuk Mahasiswa</label>
                                <textarea name="pesan" rows="4" class="w-full rounded-xl border-slate-300 shadow-sm focus:border-green-500 focus:ring-green-500 text-sm" placeholder="Contoh: Selamat bergabung! Lapor tanggal..."></textarea>
                            </div>
                            <div class="flex gap-3 mt-6">
                                <button type="button" onclick="closeModalTerima()" class="w-1/2 justify-center rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50">Batal</button>
                                <button type="submit" class="w-1/2 justify-center rounded-xl bg-green-600 px-3 py-2.5 text-sm font-bold text-white hover:bg-green-700">✓ Konfirmasi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModalTerima() { document.getElementById('modalTerima').classList.remove('hidden'); }
        function closeModalTerima() { document.getElementById('modalTerima').classList.add('hidden'); }
    </script>

</body>
</html>