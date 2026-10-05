<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#BA1A1A] border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#961313] active:bg-[#961313] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
