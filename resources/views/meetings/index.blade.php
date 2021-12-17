<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Meetings') }}
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
                    <x-button href="{{ route('meetings.create') }}">
                        <x-icons.plus></x-icons.plus> {{ __('Add') }} {{ __('Meeting') }}
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
            
            @foreach ($meetings as $meeting)
                
                <div class="shadow bg-white p-4 rounded my-4">
                    <div class="float-right ml-2 mb-2">
                        <x-button href="{{ route('meetings.show', ['meeting' => $meeting->id]) }}" padding="tight" btncolor="blue" class="mr-2">
                            {{ __('View') }}
                        </x-button>
                        <x-button href="{{ route('meetings.edit', ['meeting' => $meeting->id]) }}" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    <div class="font-bold text-gray-700">
                        <span class="inline-block bg-gray-300 rounded px-1 text-sm font-normal mr-2">
                            {{ $meeting->type->key }}
                        </span>
                        {{ $meeting->name }}
                    </div>
                    <div class="text-sm text-gray-700 overflow-hidden mt-1">
                        <div class="whitespace-nowrap text-ellipsis overflow-x-hidden">
                            <span class="text-purple-600 mr-3">
                                {{ $meeting->date_str }}
                            </span>
                            @if ($meeting->description)
                                {{ $meeting->description }}
                            @endif
                        </div>
                    </div>
                </div>
            
            @endforeach
            
            {{ $meetings->links() }}
            
        </div>
    </div>
    
</x-app-layout>
