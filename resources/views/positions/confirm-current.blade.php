<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Confirm Current Position') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-2 font-semibold">
                {{ $position->person->full_name }}
            </div>
            
            <div class="mb-2">
                @if ($position->title)
                    <span class="mr-2">{{ $position->title }}</span>
                    <span class="text-gray-500 mr-2">{{ __('at') }}</span>
                @endif
                <x-link href="{{ route('orgs.show', ['org' => $position->org->id]) }}">
                    {{ $position->org->name }}
                </x-link>
            </div>
            
            <div class="text-gray-600 text-sm mb-8">
                @if ($position->start_date_str)
                    <span class="font-semibold mr-2">{{ $position->start_date_str }}</span>
                @endif
                @if ($position->end_date_str)
                    <span class="text-gray-500 mr-2">{{ __('until') }}</span>
                    <span class="font-semibold mr-2">{{ $position->end_date_str }}</span>
                @endif
            </div>
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ route('positions.update_current', ['position' => $position->id]) }}">
                
                @csrf
                
                @method('PUT')
            
                <p class="text-gray-600">
                    @if ($isCurrent && $position->end_date->isPast())
                        {{ __('This position has ended. Are you sure you want it marked as current?') }}
                    @else
                        {{ __('This position has not ended. Would you like it marked as current?') }}
                    @endif
                </p>
                
                <div class="mb-8">
                    <label for="is_current" class="inline-flex items-center mt-1 pt-2 border border-transparent">
                        <input type="checkbox" id="is_current" name="is_current" value="1" {{ $isCurrent ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">
                            {{ __('Current main position') }}
                            <span class="text-purple-700">({{ __('Mark other positions as not current') }})</span>
                        </span>
                    </label>
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
