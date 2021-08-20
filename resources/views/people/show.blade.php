<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-gray-500 mr-3 font-normal">
                {{ __('Person') }}
            </span>
            {{ $person->name }}
            @if ($person->short_name)
            <span class="text-gray-500 ml-3">
                — {{ $person->short_name }}
            </span>
            @endif
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div>
                <h3 class="inline-block font-semibold text-lg text-gray-800 leading-tight mb-3">
                    {{ __('Info') }}
                </h3>
                <x-button href="{{ route('people.edit', ['person' => $person->id]) }}" btncolor="blue" padding="tight" class="relative bottom-1 ml-8">
                    Edit
                </x-button>
            </div>
            
            @if ($person->website)
                <div class="my-2">
                    <x-icons.link class="inline w-4 h-4 relative bottom-[1px] text-gray-500"></x-icons.link>
                    <x-link href="{{ $person->website }}" target="_blank">
                        {{ get_domain($person->website) }}
                    </x-link>
                </div>
            @endif
            
            @if ($person->one_line_address)
                <div class="my-2" x-data="{ show: false }">
                    <x-icons.location-marker class="inline w-4 h-4 relative bottom-[1px] text-gray-400"></x-icons.location-marker>
                    {{ $person->one_line_address }}
                    <x-link href="https://www.google.com/maps/search/?api=1&query={{ urlencode($person->one_line_address) }}" target="_blank" class="ml-2">
                        Maps
                    </x-link>
                    <div class="inline-block relative ml-2">
                        <x-link role="button" href="javascript:void(0)" x-on:click="copyToClipboard('{!! htmlspecialchars($person->one_line_address, ENT_QUOTES) !!}');show=true;setTimeout(() => show = false, 2000)">
                            Copy
                        </x-link>
                        <div x-show="show" x-cloak class="absolute z-10 left-1/2 -translate-x-1/2 bottom-full text-gray-500 bg-white p-1 shadow-md rounded whitespace-nowrap">
                            Copied
                        </div>
                    </div>
                </div>
            @endif
            
            @if ($person->readable_phone)
                <div class="my-2" x-data="{ show: false }">
                    <x-icons.phone class="inline w-4 h-4 relative bottom-[1px] text-gray-400"></x-icons.phone>
                    {{ $person->readable_phone }}
                    <x-link href="https://www.google.com/maps/search/?api=1&query={{ urlencode($person->one_line_address) }}" target="_blank" class="ml-2">
                        Call
                    </x-link>
                    <div class="inline-block relative ml-2">
                        <x-link role="button" href="javascript:void(0)" x-on:click="copyToClipboard('{!! htmlspecialchars($person->readable_phone, ENT_QUOTES) !!}');show=true;setTimeout(() => show = false, 2000)">
                            Copy
                        </x-link>
                        <div x-show="show" x-cloak class="absolute z-10 left-1/2 -translate-x-1/2 bottom-full text-gray-500 bg-white p-1 shadow-md rounded whitespace-nowrap">
                            Copied
                        </div>
                    </div>
                </div>
            @endif
            
            <div>
                <h3 class="inline-block font-semibold text-lg text-gray-800 leading-tight mt-8 mb-3">
                    {{ __('Positions') }}
                </h3>
                <x-button href="{{ route('positions.create') }}?person={{ $person->id }}" padding="tight" class="relative bottom-1 ml-8">
                    <x-icons.plus></x-icons.plus> {{ __('Add') }}
                </x-button>
            </div>
            
        </div>
    </div>
    
</x-app-layout>
