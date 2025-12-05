@props(['color' => 'zinc', 'text' => ''])

@php
    $colors = [
        'zinc' => 'bg-zinc-50 text-zinc-600 ring-zinc-500/10 dark:bg-zinc-400/10 dark:text-zinc-400 dark:ring-zinc-400/20',
        'lime' => 'bg-lime-50 text-lime-700 ring-lime-600/20 dark:bg-lime-500/10 dark:text-lime-400 dark:ring-lime-500/20',
        'green' => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
        'red' => 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-400/10 dark:text-red-400 dark:ring-red-400/20',
        'blue' => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20',
        'yellow' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-500/20',
        'purple' => 'bg-purple-50 text-purple-700 ring-purple-600/20 dark:bg-purple-500/10 dark:text-purple-400 dark:ring-purple-500/20',
    ];

    $colorClass = $colors[$color] ?? $colors['zinc'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset {$colorClass}"]) }}>
    {{ $text ?: $slot }}
</span>
