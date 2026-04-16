<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | PLN Suku Cadang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f1f5f9; }
        .glass { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        html, body { overflow-x: hidden; max-width: 100vw; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 sm:p-6">
    <div class="w-full max-w-md">
        <div class="glass rounded-2xl sm:rounded-[2rem] p-6 sm:p-10 shadow-2xl border border-white">
            <div class="text-center mb-8">
                <img src="{{ asset('images/magang/Logo PLN Suku Cadang White FHD.png') }}" 
                     alt="Logo" class="h-12 mx-auto mb-4 p-2 bg-[#00675b] rounded-xl">
                <h1 class="text-2xl font-bold text-slate-800">Admin Portal</h1>
                <p class="text-slate-400 text-sm">Masuk untuk manajemen magang</p>
            </div>

            @if(session('error'))
                <div class="mb-4 p-3 bg-red-100 text-red-600 text-sm rounded-lg border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('magang.admin.login.submit') }}" method="POST" class="space-y-6">
                @csrf <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Email</label>
                    <input type="email" name="email" required class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#00675b] focus:outline-none transition-all bg-white" placeholder="admin@pln.co.id">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Password</label>
                    <input type="password" name="password" required class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#00675b] focus:outline-none transition-all bg-white" placeholder="••••••••">
                </div>
                <button type="submit" class="w-full bg-[#00675b] hover:bg-[#004d44] text-white font-bold py-4 rounded-xl shadow-lg shadow-teal-900/20 transition-all active:scale-95">
                    Masuk Sekarang
                </button>
            </form>
        </div>
    </div>
</body>
</html>