<nav class="bg-pln-green shadow-md sticky top-0 z-50 w-full">
    <div class="max-w-[1920px] mx-auto px-4 sm:px-6 md:px-12 py-3 md:py-4 flex justify-between items-center">
        <a href="{{ route('welcome') }}" class="hover:opacity-90 transition flex-shrink-0">
             <img src="{{ asset('images/magang/Logo PLN Suku Cadang White FHD.png') }}" 
                  alt="Logo PLN" 
                  class="h-8 sm:h-10 md:h-12 w-auto object-contain drop-shadow-md">
        </a>

        {{-- Desktop Nav Links --}}
        <div class="hidden md:flex items-center gap-6">
            <a href="{{ route('welcome') }}" class="text-white font-medium hover:text-pln-yellow transition shadow-sm">
                Beranda
            </a>
            
            <a href="{{ route('login') }}" class="bg-white/10 backdrop-blur-sm border border-white/30 text-white px-5 py-2 rounded-full font-medium hover:bg-white hover:text-pln-green transition">
                Login 
            </a>
        </div>

        {{-- Mobile Hamburger Button --}}
        <button type="button" onclick="toggleMobileNav()" class="md:hidden text-white p-2 rounded-lg hover:bg-white/10 transition focus:outline-none" aria-label="Toggle menu">
            <svg id="navIconOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <svg id="navIconClose" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    {{-- Mobile Dropdown Menu --}}
    <div id="mobileNavMenu" class="hidden md:hidden border-t border-white/20 bg-pln-dark/95 backdrop-blur-sm">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 py-4 space-y-3">
            <a href="{{ route('welcome') }}" class="block text-white font-medium py-2.5 px-4 rounded-lg hover:bg-white/10 transition">
                🏠 Beranda
            </a>
            <a href="{{ route('login') }}" class="block text-center bg-white/10 backdrop-blur-sm border border-white/30 text-white py-2.5 px-4 rounded-lg font-medium hover:bg-white hover:text-pln-green transition">
                🔐 Login
            </a>
        </div>
    </div>
</nav>

<script>
    function toggleMobileNav() {
        const menu = document.getElementById('mobileNavMenu');
        const iconOpen = document.getElementById('navIconOpen');
        const iconClose = document.getElementById('navIconClose');
        
        menu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden');
        iconClose.classList.toggle('hidden');
    }
</script>

