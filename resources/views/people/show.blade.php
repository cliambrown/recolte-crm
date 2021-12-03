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
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <div class="w-full mb-8 px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg">
                
                @if ($currentPosition)
                    <div>
                        @if ($currentPosition->title)
                            <span class="mr-2">{{ $currentPosition->title }}</span>
                            <span class="text-gray-500 mr-2">{{ __('at') }}</span>
                        @endif
                        <x-link href="{{ route('orgs.show', ['org' => $currentPosition->org->id]) }}">
                            {{ $currentPosition->org->name }}
                        </x-link>
                    </div>
                    <div class="text-gray-600 text-sm mt-2">
                        @if ($currentPosition->start_date_str)
                        @if (!$currentPosition->end_date_str)
                            <span class="text-gray-500 mr-2">{{ __('since') }}</span>
                        @endif
                            <span class="font-semibold mr-2">{{ $currentPosition->start_date_str }}</span>
                        @endif
                        @if ($currentPosition->end_date_str)
                            <span class="text-gray-500 mr-2">{{ __('until') }}</span>
                            <span class="font-semibold mr-2">{{ $currentPosition->end_date_str }}</span>
                        @endif
                    </div>
                @else
                    <div class="text-gray-800 italic">
                        [{{ __('No current position.') }}]
                    </div>
                @endif
                
                @if ($person->current_email)
                    <div class="mt-2 flex">
                        <div class="mr-2">
                            <x-icons.at class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.at>
                        </div>
                        <div>
                            <x-link href="mailto:{{ $person->current_email }}" target="_blank">
                                {{ $person->current_email }}
                            </x-link>
                        </div>
                    </div>
                @endif
                
                @if ($person->current_readable_phone)
                    <x-phone class="mt-2" :phone="$person->current_readable_phone"></x-phone>
                @endif
                
            </div>
            
            <div class="mb-4">
                <x-button href="{{ route('people.edit', ['person' => $person->id]) }}" btncolor="blue" padding="tight" class="">
                    {{ __('Edit') }} {{ __('Info') }}
                </x-button>
            </div>
            
            @if ($person->website)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.globe class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.globe>
                    </div>
                    <div>
                        <x-link href="{{ $person->website }}" target="_blank">
                            {{ get_domain($person->website) }}
                        </x-link>
                    </div>
                </div>
            @endif
            
            @if ($person->one_line_address)
                <div class="my-2 flex" x-data="{ show: false }">
                    <div class="mr-2">
                        <x-icons.location-marker class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.location-marker>
                    </div>
                    <div>
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
                </div>
            @endif
            
            @if ($person->email)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.at class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.at>
                    </div>
                    <div>
                        <x-link href="mailto:{{ $person->email }}" target="_blank">
                            {{ $person->email }}
                        </x-link>
                    </div>
                </div>
            @endif
            
            @if ($person->readable_phone)
                <x-phone class="my-2" :phone="$person->readable_phone"></x-phone>
            @endif
            
            @if ($person->pronouns)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.happy class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.happy>
                    </div>
                    <div>
                        {{ $person->pronouns }}
                    </div>
                </div>
            @endif
            
            @if ($person->notes)
                <x-expandable-notes :notes="$person->notes"></x-expandable-notes>
            @endif
            
            <div>
                <x-button href="{{ route('positions.create', ['person' => $person->id]) }}" padding="tight" class="mt-10">
                    <x-icons.plus></x-icons.plus> {{ __('Add Position') }}
                </x-button>
            </div>
            
            @foreach ($person->positions as $position)
                <div class="my-8">
                    <div class="text-purple-600 text-sm mb-1">
                        @if ($position->is_current)
                            <span class="inline-block text-green-800 bg-green-100 px-2 py-1 -my-1 font-semibold uppercase mr-2 rounded text-xs">
                                {{ __('Current') }}
                            </span>
                        @endif
                        @if ($position->start_date_str)
                            @if (!$position->end_date_str)
                                <span class="text-gray-600 mr-1">{{ __('since') }}</span>
                            @endif
                            <span class="font-semibold mr-1">{{ $position->start_date_str }}</span>
                        @endif
                        @if ($position->end_date_str)
                            @if ($position->start_date_str)
                                <span class="text-gray-600 mr-1">{{ __('to') }}</span>
                            @else
                                <span class="text-gray-600 mr-1">{{ __('until') }}</span>
                            @endif
                            <span class="font-semibold">{{ $position->end_date_str }}</span>
                        @endif
                        @if (!$position->start_date_str && !$position->end_date_str)
                            [{{ __('unknown dates') }}]
                        @endif
                    </div>
                    <div>
                        @if ($position->title)
                            <span class="mr-1">{{ $position->title }}</span>
                            <span class="text-gray-600 mr-1">{{ __('at') }}</span>
                        @endif
                        <x-link href="{{ route('orgs.show', ['org' => $position->org->id]) }}">
                            {{ $position->org->name }}
                        </x-link>
                        
                        <x-button href="{{ route('positions.edit', ['position' => $position->id, 'person' => $person->id]) }}" class="ml-4" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    @if ($position->email)
                        <div class="mt-1">
                            <x-icons.at class="inline w-4 h-4 relative bottom-[1px] text-purple-500 mr-2"></x-icons.at>
                            <x-link href="mailto:{{ $position->email }}" target="_blank">
                                {{ $position->email }}
                            </x-link>
                        </div>
                    @endif
                    @if ($position->readable_phone)
                        <x-phone class="mt-1" :phone="$position->readable_phone"></x-phone>
                    @endif
                    @if ($position->notes)
                        <x-expandable-notes :notes="$position->notes"></x-expandable-notes>
                    @endif
                </div>
            @endforeach
            
        </div>
    </div>
    
</x-app-layout>
