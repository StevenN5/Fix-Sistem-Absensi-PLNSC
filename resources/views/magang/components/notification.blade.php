@if(session('success'))
<div class="max-w-4xl mx-auto px-4 mt-6">
    <div class="bg-green-50 border-l-4 border-pln-green p-4 flex items-start gap-3 rounded shadow-sm animate-fade-in-up">
        <div class="text-pln-green text-xl font-bold">✓</div>
        <div>
            <h4 class="font-bold text-green-900 text-sm">Berhasil!</h4>
            <p class="text-green-700 text-sm">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('error') || $errors->any())
<div class="max-w-4xl mx-auto px-4 mt-6">
    <div class="bg-red-50 border-l-4 border-red-500 p-4 flex items-start gap-3 rounded shadow-sm animate-fade-in-up">
        <div class="text-red-500 text-xl font-bold">⚠</div>
        <div>
            <h4 class="font-bold text-red-900 text-sm">Terjadi Kesalahan!</h4>
            <p class="text-red-700 text-sm">
                {{ session('error') ?? 'Pastikan data yang diisi benar dan format file sesuai (PDF).' }}
            </p>
        </div>
    </div>
</div>
@endif