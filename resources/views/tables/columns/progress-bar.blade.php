@php
    $percent = (int) $getState();
    $percent = max(0, min(100, $percent));

    $textColor = $percent < 50 ? 'text-green-500'
              : ($percent < 80 ? 'text-yellow-500' : 'text-red-500');

    $used  = \Illuminate\Support\Number::currency($getRecord()->used ?? 0, 'BRL');
    $limit = \Illuminate\Support\Number::currency($getRecord()->limit ?? 0, 'BRL');
@endphp

<div class="flex flex-col gap-1 w-48">
    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 gap-4">
        <span>{{ $percent }}%</span>
    </div>
</div>
