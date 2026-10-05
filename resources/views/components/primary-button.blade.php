<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 rounded-full bg-[#0b602b] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#084d22] transition shadow-sm hover:shadow']) }}>
    {{ $slot }}
</button>

