@props(['rows' => 5, 'cols' => 5])

<div class="w-full overflow-x-auto rounded-2xl border border-gray-200 bg-white p-4 space-y-4">
    <!-- Header skeleton -->
    <div class="flex items-center justify-between pb-3 border-b border-gray-100 gap-4">
        <div class="h-5 skeleton w-48 rounded-lg"></div>
        <div class="h-9 skeleton w-32 rounded-xl"></div>
    </div>

    <!-- Table row skeletons -->
    <div class="space-y-3">
        @for($r = 0; $r < $rows; $r++)
            <div class="flex items-center gap-4 py-2 border-b border-gray-50 last:border-none">
                <div class="h-4 skeleton w-8 rounded-md"></div>
                @for($c = 1; $c < $cols; $c++)
                    <div class="h-4 skeleton flex-1 rounded-md" style="width: {{ rand(50, 95) }}%"></div>
                @endfor
            </div>
        @endfor
    </div>
</div>
