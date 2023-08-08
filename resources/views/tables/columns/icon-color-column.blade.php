<div
        class="filament-icon-picker-icon-column px-2 py-2 rounded-xl"
        @if(!blank($getRecord()->color))
                @style([
                    "background-color: {$getRecord()->color}" => $getRecord()->color,
                ])
        @endif
>
    @if ($icon = $getState())
        <x-filament::icon class="w-7 h-7 text-gray-100" icon="{{$getState()}}"/>
    @elseif(!blank($getRecord()->color))
        <div
                @class([
                    'fi-ta-color-item h-7 w-7 rounded-md',
                ])
                @style([
                   "background-color: {$getRecord()->color}" => $getRecord()->color,
                ])
        ></div>
    @endif
</div>
