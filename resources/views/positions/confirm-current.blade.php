<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Current Position') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-2 font-semibold">
                {{ $person->full_name }}
            </div>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ route('positions.update_current', ['person' => $person->id]) }}">
                
                @csrf
                
                @method('PUT')
                
                <input type="hidden" name="redirect_url" value="$redirectUrl">
            
                <p class="text-gray-600">
                    @if ($problem === 'current_expired')
                        {{ __('The position marked as "current" for this person has ended. Please confirm their current main position from the list below.') }}
                    @else
                        {{ __('Please confirm that this person does not have a current position, or choose a current main position from the list below.') }}
                    @endif
                </p>
                
                <div class="mb-8">
                    
                    <label for="position-null" class="flex items-center mt-1 pt-2 border border-transparent">
                        <input type="radio" id="position-null" name="current_position_id" value="" {{ !$currentPosition ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">
                            {{ __('No current position') }}
                        </span>
                    </label>
                    
                    @foreach ($person->positions as $position)
                        <label for="position-{{ $position->id }}" class="flex items-center mt-1 pt-2 border border-transparent">
                            <input type="radio" id="position-{{ $position->id }}" name="current_position_id" value="{{ $position->id }}" {{ $position->is_current ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-600">
                                {{ $position->start_date_str ? $position->start_date_str : '[?]' }}
                                {{ __('to') }}
                                {{ $position->end_date_str ? $position->end_date_str : '[--]' }}
                                —
                                {{ $position->title }}
                                {{ __('at') }}
                                {{ $position->org->name }}
                            </span>
                        </label>
                    @endforeach
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
