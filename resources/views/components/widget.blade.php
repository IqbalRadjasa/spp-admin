@props(['title', 'value', 'icon' => null])

<div
    {{ $attributes->merge([
        'class' => '
                    border-2
                    border-gray-300
                    rounded-md
                    py-6
                    px-4
                    shadow-md
                ',
    ]) }}>

    <div class="flex">
        <div class="flex-col w-2/3">
            {{-- Header --}}
            <div class="header">
                <span class="text-lg font-semibold">
                    {{ $title }}
                </span>
            </div>

            {{-- Body --}}
            <div class="body flex">
                {{-- Info --}}
                <div class="card-info flex items-center">
                    <span class="text-2xl font-bold">
                        {{ $value }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex w-1/3 justify-center">
            {{-- Icon --}}
            <div class="icon w-1/3 flex justify-center items-center">
                @if ($icon)
                    <i class="{{ $icon }} text-6xl opacity-25"></i>
                @endif
            </div>
        </div>
    </div>

</div>
