@props(['colspan' => null, 'bold' => false, 'center' => false])

<td
    @if($colspan) colspan="{{ $colspan }}" @endif
    {{ $attributes->merge([
        'class' => 'px-6 py-4 whitespace-nowrap text-sm ' .
                   ($bold ? 'font-medium text-zinc-900 dark:text-zinc-100' : 'text-zinc-500 dark:text-zinc-400') .
                   ($center ? ' text-center' : '')
    ]) }}
>
    {{ $slot }}
</td>
