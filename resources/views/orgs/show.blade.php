<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-gray-500 mr-3 font-normal">
                {{ __('Org') }}
            </span>
            {{ $org->name }}
            @if ($org->short_name)
            <span class="text-gray-500 ml-3">
                — {{ $org->short_name }}
            </span>
            @endif
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <div class="mb-4">
                <x-button href="{{ route('orgs.edit', ['org' => $org->id]) }}" btncolor="blue" padding="tight" class="">
                    {{ __('Edit') }} {{ __('Info') }}
                </x-button>
            </div>
            
            @if ($org->website)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.globe class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.globe>
                    </div>
                    <div>
                        <x-link href="{{ $org->website }}" target="_blank">
                            {{ $org->website }}
                        </x-link>
                    </div>
                </div>
            @endif
            
            @if ($org->one_line_address)
                <div class="my-2 flex" x-data="{ show: false }">
                    <div class="mr-2">
                        <x-icons.location-marker class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.location-marker>
                    </div>
                    <div>
                        {{ $org->one_line_address }}
                        <x-link href="https://www.google.com/maps/search/?api=1&query={{ urlencode($org->one_line_address) }}" target="_blank" class="ml-2">
                            Maps
                        </x-link>
                        <div class="inline-block relative ml-2">
                            <x-link role="button" href="javascript:void(0)" x-on:click="copyToClipboard('{!! htmlspecialchars($org->one_line_address, ENT_QUOTES) !!}');show=true;setTimeout(() => show = false, 2000)">
                                Copy
                            </x-link>
                            <div x-show="show" x-cloak class="absolute z-10 left-1/2 -translate-x-1/2 bottom-full text-purple-500 bg-white p-1 shadow-md rounded whitespace-nowrap">
                                Copied
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
            @if ($org->email)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.at class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.at>
                    </div>
                    <div>
                        <x-link href="mailto:{{ $org->email }}" target="_blank">
                            {{ $org->email }}
                        </x-link>
                    </div>
                </div>
            @endif
            
            @if ($org->readable_phone)
                <x-phone class="my-2" :phone="$org->readable_phone"></x-phone>
            @endif
            
            @if ($org->notes)
                <x-expandable-notes :notes="$org->notes"></x-expandable-notes>
            @endif
            
            @if ($org->types->count())
                <div class="overflow-hidden">
                    <div class="text-gray-700 mt-4 -mx-1">
                        @foreach ($org->types as $orgType)
                            <div class="inline-block mx-1 py-1 px-2 bg-purple-100 text-purple-800 rounded text-sm font-semibold">
                                {{ $orgType->name }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div>
                <x-button href="{{ route('org_relationships.create', ['child' => $org->id]) }}" padding="tight" class="mt-10">
                    <x-icons.plus></x-icons.plus> {{ __('Add Parent Org') }}
                </x-button>
                <x-button href="{{ route('org_relationships.create', ['parent' => $org->id]) }}" padding="tight" class="ml-4 mt-10">
                    <x-icons.plus></x-icons.plus> {{ __('Add Child Org') }}
                </x-button>
            </div>
            
            @if ($org->child_relationships->count())
                <h3 class="mt-8 my-4 text-lg text-gray-700">{{ __('Parent Orgs') }}</h3>
            @endif
            
            @foreach ($org->child_relationships as $relationship)
                <div class="my-4">
                    <div class="text-purple-600 text-sm mb-1">
                        @if ($relationship->start_date_str)
                            @if (!$relationship->end_date_str)
                                <span class="text-gray-500 mr-1">{{ __('since') }}</span>
                            @endif
                            <span class="font-semibold mr-1">{{ $relationship->start_date_str }}</span>
                        @endif
                        @if ($relationship->end_date_str)
                            @if ($relationship->start_date_str)
                                <span class="text-gray-500 mr-1">{{ __('to') }}</span>
                            @else
                                <span class="text-gray-500 mr-1">{{ __('until') }}</span>
                            @endif
                            <span class="font-semibold">{{ $relationship->end_date_str }}</span>
                        @endif
                        @if (!$relationship->start_date_str && !$relationship->end_date_str)
                            [{{ __('unknown dates') }}]
                        @endif
                    </div>
                    <span class="font-semibold">
                        {{ $relationship->child_description }}
                    </span>
                    {{ __('of') }}
                    <x-link href="{{ route('orgs.show', ['org' => $relationship->parent_org->id]) }}">
                        {{ $relationship->parent_org->name }}
                    </x-link>
                    <x-button href="{{ route('org_relationships.edit', ['org_relationship' => $relationship->id, 'child' => $org->id]) }}" class="ml-4" padding="tight" btncolor="green">
                        {{ __('Edit') }}
                    </x-button>
                </div>
                @if ($relationship->notes)
                    <x-expandable-notes :notes="$relationship->notes"></x-expandable-notes>
                @endif
            @endforeach
            
            @if ($org->parent_relationships->count())
                <h3 class="mt-8 my-4 text-lg text-gray-700">{{ __('Child Orgs') }}</h3>
            @endif
            
            @foreach ($org->parent_relationships as $relationship)
                <div class="my-4">
                    <div class="text-purple-600 text-sm mb-1">
                        @if ($relationship->start_date_str)
                            @if (!$relationship->end_date_str)
                                <span class="text-gray-500 mr-1">{{ __('since') }}</span>
                            @endif
                            <span class="font-semibold mr-1">{{ $relationship->start_date_str }}</span>
                        @endif
                        @if ($relationship->end_date_str)
                            @if ($relationship->start_date_str)
                                <span class="text-gray-500 mr-1">{{ __('to') }}</span>
                            @else
                                <span class="text-gray-500 mr-1">{{ __('until') }}</span>
                            @endif
                            <span class="font-semibold">{{ $relationship->end_date_str }}</span>
                        @endif
                        @if (!$relationship->start_date_str && !$relationship->end_date_str)
                            [{{ __('unknown dates') }}]
                        @endif
                    </div>
                    <div class="mb-1">
                        <x-link href="{{ route('orgs.show', ['org' => $relationship->child_org->id]) }}">
                            {{ $relationship->child_org->name }}
                        </x-link>
                        {{ __('is a') }}
                        <span class="font-semibold">
                            {{ $relationship->child_description }}
                        </span>
                        <x-button href="{{ route('org_relationships.edit', ['org_relationship' => $relationship->id, 'parent' => $org->id]) }}" class="ml-4" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    @if ($relationship->notes)
                        <x-expandable-notes :notes="$relationship->notes"></x-expandable-notes>
                    @endif
                </div>
            @endforeach
            
            <div>
                <x-button href="{{ route('positions.create', ['org' => $org->id]) }}" padding="tight" class="mt-10">
                    <x-icons.plus></x-icons.plus> {{ __('Add Person') }}
                </x-button>
            </div>
            
            @foreach ($org->positions as $position)
                <div class="my-8">
                    <div class="text-purple-600 text-sm mb-1">
                        @if ($position->start_date_str)
                            @if (!$position->end_date_str)
                                <span class="text-gray-500 mr-1">{{ __('since') }}</span>
                            @endif
                            <span class="font-semibold mr-1">{{ $position->start_date_str }}</span>
                        @endif
                        @if ($position->end_date_str)
                            @if ($position->start_date_str)
                                <span class="text-gray-500 mr-1">{{ __('to') }}</span>
                            @else
                                <span class="text-gray-500 mr-1">{{ __('until') }}</span>
                            @endif
                            <span class="font-semibold">{{ $position->end_date_str }}</span>
                        @endif
                        @if (!$position->start_date_str && !$position->end_date_str)
                            [{{ __('unknown dates') }}]
                        @endif
                    </div>
                    <div>
                        <x-link href="{{ route('people.show', ['person' => $position->person->id]) }}">
                            {{ $position->person->full_name }}
                        </x-link>
                        @if ($position->title)
                            <span class="text-gray-600 mx-1">—</span>
                            <span class="mr-1">{{ $position->title }}</span>
                        @endif
                        <x-button href="{{ route('positions.edit', ['position' => $position->id, 'org' => $org->id]) }}" class="ml-4" padding="tight" btncolor="green">
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
