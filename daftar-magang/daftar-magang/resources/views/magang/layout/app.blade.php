<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PLN Suku Cadang')</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pln: {
                            green: '#00675b',  
                            dark: '#004d44',   
                            yellow: '#ffcb05', 
                            light: '#eefcf6',  
                            blue: '#0093dd',   
                        },
                        slate: {
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    {{-- Global Responsive CSS --}}
    <style>
        /* Prevent horizontal overflow globally */
        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        /* Responsive font scaling */
        @media (max-width: 767px) {
            html { font-size: 15px; }
            
            /* Full-width buttons on mobile */
            .btn-mobile-full {
                width: 100% !important;
                display: block !important;
            }

            /* Ensure touch-friendly tap targets */
            button, a, input, select, textarea {
                min-height: 40px;
            }

            /* Reduce padding on containers */
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            html { font-size: 15.5px; }
        }

        @media (min-width: 992px) {
            html { font-size: 16px; }
        }

        /* Smooth scrolling */
        html { scroll-behavior: smooth; }

        /* Better image handling */
        img { max-width: 100%; height: auto; }
    </style>

    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 font-sans bg-slate-50">

    @include('magang.components.navbar')

    @include('magang.components.notification')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('magang.components.footer')

    @stack('scripts')
</body>
</html>