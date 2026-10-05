@props(['count' => 1])

@for($i = 0; $i < $count; $i++)
<div class="bg-white rounded-2xl border border-gray-200 p-5 space-y-3 shadow-xs">
    <div class="flex items-center justify-between">
        <div class="h-4 skeleton w-28 rounded-md"></div>
        <div class="w-10 h-10 skeleton rounded-xl"></div>
    </div>
    <div class="h-7 skeleton w-20 rounded-md"></div>
    <div class="h-3 skeleton w-36 rounded-md"></div>
</div>
@endfor
