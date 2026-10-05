@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 bg-white text-gray-900 focus:border-[#0b602b] focus:ring-[#0b602b] rounded-xl shadow-sm']) }}>
