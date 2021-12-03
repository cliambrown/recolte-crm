<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('People') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <div class="sm:flex sm:justify-between">
                
                <div>
                    <x-button href="{{ route('people.create') }}">
                        <x-icons.plus></x-icons.plus> {{ __('Add') }} {{ __('Person') }}
                    </x-button>
                </div>
                
                <form class="w-full sm:w-auto sm:max-w-xs flex-grow sm:flex my-3 sm:my-0" method="GET">
                    <div class="flex-grow">
                        <x-input type="text" placeholder="{{ __('Search') }}" name="q" aria-placeholder="{{ __('Search') }}" class="w-full flex-grow" value="{{ $q }}"></x-input>
                    </div>
                    <div class="ml-1">
                        <x-button type="submit">
                            <x-icons.search class="-my-1"></x-icons.search>
                        </x-button>
                    </div>
                </form>
                
            </div>
            
            @if ($q)
                <div class="my-4 text-gray-700 text-sm">
                    {{ __('Showing results for search') }} "{{ $q }}". <x-link href="{{ set_url_param(url()->current(), 'q', null) }}" class="ml-2">Clear search</x-link>
                </div>
            @endif
            
            @foreach ($people as $person)
            
                <div class="shadow bg-white p-4 rounded my-4">
                    <div class="float-right ml-2 mb-2">
                        <x-button href="{{ route('people.show', ['person' => $person->id]) }}" padding="tight" btncolor="blue" class="mr-2">
                            {{ __('View') }}
                        </x-button>
                        <x-button href="{{ route('people.edit', ['person' => $person->id]) }}" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    <div class="font-bold text-gray-700">
                        {{ $person->display_given_name }}
                        {{ $person->display_family_name }}
                    </div>
                    <div class="text-sm text-gray-700 overflow-hidden mt-1">
                        <div class="-mx-1">
                            @if ($person->current_position)
                                <span class="mx-1">
                                    @if ($person->current_position->title)
                                        <span class="text-gray-800">{{ $person->current_position->title }}</span>
                                        <span class="text-gray-600">{{ __('at') }}</span>
                                    @endif
                                    <x-link href="{{ route('orgs.show', ['org' => $person->current_position->org->id]) }}">
                                        {{ $person->current_position->org->name }}
                                    </x-link>
                                </span>
                            @endif
                            @if ($person->current_email)
                                <a href="mailto:{{ $person->current_email }}" class="cursor-pointer mx-1">
                                    <x-icons.at class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.at>
                                    {{ $person->current_email }}
                                </a>
                            @endif
                            @if ($person->current_readable_phone)
                                <a href="tel:{{ $person->current_readable_phone }}" class="cursor-pointer mx-1">
                                    <x-icons.phone class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.phone>
                                    {{ $person->current_readable_phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                
            @endforeach
            
            {{ $people->links() }}
            
        </div>
    </div>
    
</x-app-layout>
