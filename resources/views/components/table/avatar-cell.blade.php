@props(['initials', 'name', 'subtitle' => null])

<div class="flex items-center gap-3">
    <div
        class="w-8 h-8 rounded-full bg-[#91AC67]/20 text-[#597928] flex items-center justify-center font-bold text-xs uppercase shrink-0">
        {{ $initials }}
    </div>
    <div class="flex flex-col">
        <span class="font-medium text-stone-900 leading-tight">{{ $name }}</span>
        @if ($subtitle)
            <span class="text-[11px] text-stone-400">{{ $subtitle }}</span>
        @endif
    </div>
</div>
