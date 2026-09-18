@props(['title', 'value', 'icon' => null])

<div
    {{ $attributes->merge([
        'class' =>
            'relative overflow-hidden bg-gradient-to-br from-white via-[#FCECD8]/30 to-[#91AC67]/20 rounded-xl border border-stone-200/70 p-5 shadow-xs hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group',
    ]) }}>

    {{-- Decorative Top Accent Line --}}
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#91AC67] to-[#597928]"></div>

    <div class="flex items-start justify-between gap-4 relative z-10">

        {{-- Left Content: Title & Big Metric --}}
        <div class="flex flex-col justify-between">
            <span class="text-xs font-bold uppercase tracking-wider text-stone-500">
                {{ $title }}
            </span>

            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-stone-800 tracking-tight">
                    {{ $value }}
                </span>
            </div>
        </div>

        {{-- Right Side: Icon Container --}}
        @if ($icon)
            <div
                class="flex items-center justify-center w-12 h-12 rounded-xl bg-white/80 border border-stone-200/50 text-[#597928] shadow-xs shrink-0 group-hover:scale-105 group-hover:bg-[#597928] group-hover:text-white transition-all duration-200">
                <i class="{{ $icon }} text-xl"></i>
            </div>
        @endif

    </div>
</div>
