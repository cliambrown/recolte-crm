<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-gray-500 mr-3 font-normal">
                {{ __('Person') }}
            </span>
            {{ $person->full_name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <x-button href="{{ route('people.edit', ['person' => $person->id]) }}" btncolor="blue" padding="tight" class="">
                    Edit Info
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
                <x-button href="{{ route('positions.create') }}?person={{ $person->id }}" padding="tight" class="mt-10">
                    <x-icons.plus></x-icons.plus> {{ __('Add Position') }}
                </x-button>
            </div>
            
            @foreach ($person->positions as $position)
                <div class="my-6">
                    @if ($position->is_current)
                        <div class="text-purple-500 font-semibold uppercase text-sm mb-1">
                            {{ __('Current') }}
                        </div>
                    @endif
                    <div>
                        @if ($position->title)
                            <span class="mr-2">{{ $position->title }}</span>
                            <span class="text-gray-500 mr-2">{{ __('at') }}</span>
                        @endif
                        <x-link href="{{ route('orgs.show', ['org' => $position->org->id]) }}">
                            {{ $position->org->name }}
                        </x-link>
                        
                        <x-button href="{{ route('positions.edit', ['position' => $position->id]) }}" class="ml-4" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    <div class="text-gray-600 text-sm">
                        @if ($position->start_date_str)
                            <span class="font-semibold mr-2">{{ $position->start_date_str }}</span>
                        @endif
                        @if ($position->end_date_str)
                            <span class="text-gray-500 mr-2">{{ __('until') }}</span>
                            <span class="font-semibold mr-2">{{ $position->end_date_str }}</span>
                        @endif
                    </div>
                    <div class="mt-1">
                        {{-- Contact info --}}
                    </div>
                </div>
            @endforeach
            
        </div>
    </div>
    
</x-app-layout>
