@props(['title'])

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ $title }}</h1>
    @if(isset($slot) && !$slot->isEmpty())
        <div class="flex items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
