@props(['hover' => true])

<tr {{ $attributes->merge(['class' => $hover ? 'hover:bg-zinc-50 dark:hover:bg-zinc-800' : '']) }}>
    {{ $slot }}
</tr>
