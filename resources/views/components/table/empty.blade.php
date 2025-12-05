@props(['colspan' => 1, 'message' => 'Geen gegevens gevonden.'])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-8 text-center text-sm text-zinc-500 dark:text-zinc-400">
        {{ $message }}
    </td>
</tr>
