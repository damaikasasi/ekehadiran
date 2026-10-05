<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Panduan - SIKAP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F5F5] min-h-screen flex items-center justify-center p-6 font-sans antialiased text-slate-800">
    <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200 shadow-xl text-center space-y-5">
        <div class="w-16 h-16 rounded-2xl bg-green-50 border border-green-100 flex items-center justify-center mx-auto text-[#0F5E2B]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
        </div>

        <div class="space-y-2">
            <h1 class="text-xl font-bold text-slate-900">Buku Panduan Pengguna</h1>
            <p class="text-xs text-slate-500 leading-relaxed">
                Dokumen panduan penggunaan sistem SIKAP dapat diakses di sini atau simpan file PDF panduan pada direktori:
            </p>
            <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200 text-[11px] font-mono text-slate-600 break-all">
                public/docs/buku-panduan.pdf
            </div>
        </div>

        <div class="pt-2">
            <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-full py-3 px-4 bg-[#0F5E2B] hover:bg-[#0C4E23] text-white font-bold text-xs rounded-xl transition">
                Kembali ke Halaman Login
            </a>
        </div>
    </div>
</body>
</html>
