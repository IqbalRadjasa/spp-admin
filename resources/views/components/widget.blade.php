@props([
    'title',
    'value',
    'icon' => null,
    'description' => null, // e.g., 'Total registered', 'Active this term'
    'variant' => 'olive', // 'olive', 'espresso', or 'cream'
])

@php
    $variantClasses = match ($variant) {
        'espresso' => 'bg-[#6E3511] text-white border-[#6E3511]',
        'cream' => 'bg-[#FCECD8] text-[#6E3511] border-[#FCECD8]',
        default => 'bg-[#597928] text-white border-[#597928]',
    };

    $iconBgClasses = match ($variant) {
        'espresso' => 'bg-white/15 text-white',
        'cream' => 'bg-[#6E3511]/10 text-[#6E3511]',
        default => 'bg-white/20 text-white',
    };

    $subtextClasses = match ($variant) {
        'cream' => 'text-[#6E3511]/70',
        default => 'text-white/80',
    };
@endphp

<div
    {{ $attributes->merge([
        'class' => "relative rounded-2xl p-6 border shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden {$variantClasses}",
    ]) }}>

    {{-- Background Decorative Shape --}}
    <div class="absolute -right-6 -bottom-6 w-24 h-24 rounded-full bg-white/5 pointer-events-none"></div>

    <div class="flex items-start justify-between gap-4 relative z-10">

        {{-- Text Details --}}
        <div>
            <p class="text-xs font-bold uppercase tracking-wider opacity-90">
                {{ $title }}
            </p>

            <h3 class="mt-2 text-3xl font-black tracking-tight">
                {{ $value }}
            </h3>

            @if ($description)
                <p class="mt-1 text-xs font-medium {{ $subtextClasses }}">
                    {{ $description }}
                </p>
            @endif
        </div>

        {{-- Icon Container --}}
        @if ($icon)
            <div
                class="flex items-center justify-center w-12 h-12 rounded-xl {{ $iconBgClasses }} backdrop-blur-xs shrink-0 shadow-2xs">
                <i class="{{ $icon }} text-2xl"></i>
            </div>
        @endif

    </div>
</div>
