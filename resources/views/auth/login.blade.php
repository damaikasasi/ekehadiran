<x-guest-layout>
    <div x-data="{
        nip: '{{ old('nip') }}',
        password: '',
        remember: false,
        showLoginPassword: false
    }" class="relative w-full">

        <!-- 1. LOGIN CARD -->
        <div class="space-y-6">
            <!-- Welcome Header -->
            <div class="text-left space-y-2">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-white">
                    Selamat Datang
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-300">
                    Silakan masuk dengan NIP Anda untuk mengakses sistem
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-200/50 dark:border-green-800/30 text-sm" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- NIP Input -->
                <div class="space-y-1.5 text-left">
                    <label for="nip" class="text-xs font-bold tracking-wider text-slate-700 dark:text-slate-200 uppercase">
                        Nomor Induk Pegawai (NIP)
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-green-700 transition-colors duration-200">
                            <!-- ID Card Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm-7.5 7.5a5.625 5.625 0 0 1 11.25 0v1.5a.75.75 0 0 1-.75.75h-9.75a.75.75 0 0 1-.75-.75v-1.5Z" />
                            </svg>
                        </div>
                        <input id="nip" 
                               type="text" 
                               name="nip" 
                               x-model="nip"
                               value="{{ old('nip') }}"
                               required 
                               autofocus 
                               autocomplete="username"
                               placeholder="Contoh: 19850101XXXXXXXXX"
                               class="block w-full pl-10 pr-4 py-3 bg-[#F8FAFC] dark:bg-slate-800/40 border {{ $errors->has('nip') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-300 dark:border-slate-800 focus:ring-green-600 focus:border-green-600' }} rounded-lg text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-1 transition duration-200 text-sm" />
                    </div>
                    <x-input-error :messages="$errors->get('nip')" class="mt-1.5 text-xs font-medium" />
                </div>

                <!-- Password Input -->
                <div class="space-y-1.5 text-left">
                    <label for="password" class="text-xs font-bold tracking-wider text-slate-700 dark:text-slate-200 uppercase">
                        Kata Sandi
                    </label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-green-700 transition-colors duration-200">
                            <!-- Lock Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <input id="password" 
                               :type="showLoginPassword ? 'text' : 'password'" 
                               name="password" 
                               x-model="password"
                               required 
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="block w-full pl-10 pr-10 py-3 bg-[#F8FAFC] dark:bg-slate-800/40 border {{ $errors->has('password') ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : 'border-slate-300 dark:border-slate-800 focus:ring-green-600 focus:border-green-600' }} rounded-lg text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-slate-400 focus:outline-none focus:ring-1 transition duration-200 text-sm" />
                        <button type="button" 
                                @click="showLoginPassword = !showLoginPassword"
                                :aria-label="showLoginPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                aria-label="Tampilkan kata sandi"
                                title="Tampilkan atau sembunyikan kata sandi"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 transition-colors focus:outline-none">
                            <!-- Eye Open Icon -->
                            <svg x-show="!showLoginPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 016 0Z" />
                            </svg>
                            <!-- Eye Closed Icon -->
                            <svg x-show="showLoginPassword" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.822 7.822 3 3m-3-3-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs font-medium" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center text-left">
                    <input id="remember_me" 
                           type="checkbox" 
                           name="remember"
                           x-model="remember"
                           class="w-4 h-4 text-green-700 border-slate-300 dark:border-slate-700 rounded bg-white dark:bg-slate-900 focus:ring-green-600 focus:ring-2 focus:ring-offset-0 transition duration-150 cursor-pointer">
                    <label for="remember_me" class="ml-2.5 text-xs text-slate-700 dark:text-slate-300 select-none cursor-pointer">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2 space-y-3">
                    <button type="submit" 
                            class="relative w-full py-3.5 px-4 bg-[#0F5E2B] hover:bg-[#0C4E23] active:bg-[#0A3F1C] text-white font-bold text-sm rounded-lg flex items-center justify-center gap-2 shadow-lg shadow-green-950/20 hover:scale-[1.01] active:scale-[0.99] transition duration-150 focus:outline-none">
                        <span>Masuk ke Sistem</span>
                        <!-- Sign-In Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                        </svg>
                    </button>

                    <!-- Button Buku Panduan -->
                    <a href="{{ route('buku-panduan') }}" 
                       target="_blank"
                       class="relative w-full py-3 px-4 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/70 text-[#0F5E2B] dark:text-green-400 font-bold text-sm rounded-lg flex items-center justify-center gap-2 border border-[#0F5E2B]/30 dark:border-green-800/40 hover:border-[#0F5E2B] shadow-xs hover:scale-[1.01] active:scale-[0.99] transition duration-150 focus:outline-none">
                        <!-- Book / Document Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-[#0F5E2B] dark:text-green-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        <span>Buku Panduan</span>
                    </a>
                </div>
            </form>
        </div>

    </div>
</x-guest-layout>