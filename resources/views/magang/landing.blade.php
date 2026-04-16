@extends('magang.layout.app')

@section('title', 'Program Magang | PLN Suku Cadang')

@push('styles')
<style>
    /* CLASS UNTUK BACKGROUND SELEBAR LAYAR PENUH */
    .hero-full-bg {
        background-image: url("{{ asset('images/magang/New Back.png') }}");
        /* Memaksa lebar gambar selalu 100% dari lebar layar */
        background-size: 100% auto;
        background-position: center top;
        background-repeat: no-repeat;
        /* Opsional: tambahkan 'background-attachment: fixed;' untuk efek parallax saat scroll */
    }

    /* Penyesuaian khusus untuk layar HP agar gambar tidak terdistorsi */
    @media (max-width: 768px) {
        .hero-full-bg {
            background-size: cover;
            background-position: center top;
        }
    }

    /* Animasi Masuk */
    .animate-fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
        opacity: 0; /* Mulai dari tidak terlihat */
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')

<div class="hero-full-bg w-full min-h-screen flex items-center relative overflow-hidden">

    <div class="container mx-auto px-4 sm:px-6 md:px-12 py-10 sm:py-16 lg:py-24 relative z-10">

        @if(session('error'))
            <div class="max-w-lg mb-8 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm animate-fade-in-up" role="alert">
                <div class="flex">
                    <div class="py-1"><svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                    <div>
                        <p class="font-bold">Maaf!</p>
                        <p class="text-sm">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center pb-8 sm:pb-16">

            <div class="text-left space-y-6 max-w-xl animate-fade-in-up">

                <span class="inline-block py-2 px-5 rounded-full bg-pln-light text-pln-green text-sm font-bold shadow-sm border border-green-200/50">
                    {{ $batchInfo ?? 'Program Magang Batch 2025/2026' }}
                </span>

                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 leading-tight font-poppins">
                    Wujudkan Potensi <br/>
                    Profesionalmu Bersama <br/>
                    <span class="text-pln-green">PLN Suku Cadang</span>
                </h1>

                <p class="text-base md:text-lg text-slate-600 leading-relaxed font-medium">
                    Bergabunglah dalam Program Magang intensif untuk mahasiswa dan fresh graduate.
                    Dapatkan pengalaman nyata di industri ketenagalistrikan nasional.
                </p>

                <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 pt-4">
                    <a href="{{ route('magang.form') }}"
                       class="bg-pln-green hover:bg-pln-dark text-white text-sm md:text-base font-bold py-3 px-8 rounded-full shadow-lg shadow-pln-green/20 transition-all transform hover:-translate-y-1 text-center flex items-center justify-center w-full sm:w-auto">
                       Daftar Sekarang
                    </a>

                    <button onclick="openModal()"
                       class="bg-white hover:bg-gray-50 text-pln-green border-2 border-pln-green text-sm md:text-base font-bold py-3 px-8 rounded-full shadow-sm hover:shadow-md transition-all text-center flex items-center justify-center w-full sm:w-auto">
                       Cek Status
                    </button>
                </div>
            </div>

            <div class="hidden lg:block"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 relative z-10 animate-fade-in-up" style="animation-delay: 300ms;">
            <div class="p-6 bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-white/50 flex items-start gap-4 hover:shadow-md transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">🚀</div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Real Project</h3>
                    <p class="text-slate-600 text-sm mt-2 leading-relaxed">Terlibat langsung dalam pengembangan sistem internal perusahaan.</p>
                </div>
            </div>

            <div class="p-6 bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-white/50 flex items-start gap-4 hover:shadow-md transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 bg-green-50 text-pln-green rounded-full flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">🤝</div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Mentorship</h3>
                    <p class="text-slate-600 text-sm mt-2 leading-relaxed">Bimbingan intensif langsung dari para profesional ahli di bidangnya.</p>
                </div>
            </div>

            <div class="p-6 bg-white/90 backdrop-blur-sm rounded-2xl shadow-sm border border-white/50 flex items-start gap-4 hover:shadow-md transition-all group hover:-translate-y-1">
                <div class="w-12 h-12 bg-yellow-50 text-pln-yellow rounded-full flex items-center justify-center text-2xl flex-shrink-0 group-hover:scale-110 transition-transform">📜</div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800">Sertifikat Resmi</h3>
                    <p class="text-slate-600 text-sm mt-2 leading-relaxed">Memperoleh sertifikat resmi tanda kelulusan program magang dari PLN Suku Cadang.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="statusModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-pln-green px-4 py-6 sm:px-6 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 text-white/10">
                        <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="60" cy="60" r="60" fill="currentColor"/></svg>
                    </div>
                    <h3 class="text-xl font-bold leading-6 text-white relative z-10 font-poppins" id="modal-title">Cek Status Lamaran</h3>
                    <p class="text-green-100 text-sm mt-2 relative z-10">Masukkan alamat email yang Anda gunakan saat mendaftar.</p>
                    <button type="button" onclick="closeModal()" class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors z-20">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="px-6 py-8">
                    <form action="{{ route('magang.cek-status.result') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Alamat Email</label>
                            <input type="email" name="email" id="email" required class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-pln-green focus:ring-pln-green py-3 px-4 text-slate-800 bg-slate-50/50" placeholder="contoh@email.com">
                        </div>
                        <button type="submit" class="w-full justify-center rounded-xl bg-pln-green px-4 py-3.5 text-sm font-bold text-white shadow-md hover:bg-pln-dark transition-all flex items-center gap-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pln-green">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            Lacak Lamaran Saya
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('statusModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Mencegah scroll di body saat modal terbuka
    }
    function closeModal() {
        document.getElementById('statusModal').classList.add('hidden');
        document.body.style.overflow = 'auto'; // Mengaktifkan kembali scroll
    }
</script>
@endsection