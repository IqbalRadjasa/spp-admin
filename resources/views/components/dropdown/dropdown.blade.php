@props(['align' => 'right', 'width' => '56', 'contentClasses' => 'py-1.5 bg-white'])

@php
    $alignmentClasses = match ($align) {
        'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
        'top' => 'origin-top',
        default => 'ltr:origin-top-right rtl:origin-top-left end-0',
    };

    $widthClass = match ($width) {
        '48' => 'w-48',
        '56' => 'w-56',
        '64' => 'w-64',
        default => $width,
    };
@endphp

<div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    {{-- Trigger Button --}}
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    {{-- Dropdown Panel --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
        class="absolute z-50 mt-2 {{ $widthClass }} rounded-2xl border border-stone-200/80 shadow-xl overflow-hidden {{ $alignmentClasses }}"
        style="display: none;" @click="open = false">

        <div class="divide-y divide-stone-100 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
