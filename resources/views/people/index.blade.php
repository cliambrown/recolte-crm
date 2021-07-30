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
            
            <div class="mt-6">
                @foreach ($people as $person)
                    <div class="mb-1">
                        {{ $person->display_given_name }}
                        {{ $person->display_family_name }}
                        <x-link href="{{ route('people.edit', ['person' => $person->id]) }}" class="ml-2">edit</x-link>
                    </div>
                @endforeach
            </div>
            
            {{ $people->links() }}
            
        </div>
    </div>
    
</x-app-layout>
