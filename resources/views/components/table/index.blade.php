@props(['headers' => [], 'empty' => false, 'emptyMessage' => 'No records found.'])

<div class="w-full overflow-x-auto rounded-lg border border-stone-200/80 bg-white shadow-2xs">
    <table class="w-full text-left text-sm border-collapse">
        <thead>
            <tr class="bg-[#FCECD8]/50 text-stone-700 uppercase text-xs tracking-wider border-b border-stone-200/80">
                @foreach ($headers as $header)
                    <th
                        class="py-3.5 px-5 font-semibold {{ is_array($header) && ($header['align'] ?? '') === 'right' ? 'text-right' : '' }}">
                        {{ is_array($header) ? $header['label'] : $header }}
                    </th>
                @endforeach
            </tr>
        </thead>

        <tbody class="divide-y divide-stone-100 text-stone-700">
            @if ($empty)
                <tr>
                    <td colspan="{{ count($headers) }}" class="py-12 text-center text-stone-400 font-medium">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @else
                {{ $slot }}
            @endif
        </tbody>
    </table>
</div>
