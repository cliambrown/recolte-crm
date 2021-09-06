<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('People') }}
        </h2>
    </x-slot>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <x-button href="{{ route('people.create') }}">
                <x-icons.plus></x-icons.plus> {{ __('Add') }} {{ __('Person') }}
            </x-button>
            
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
