<x-app-layout>
    <x-slot name="header">
        
        @if ($isEdit)
            @php
                $confirmMsg = __('Are you sure you want to delete ":itemName"?', ['itemName' => $meeting->name])
            @endphp
            <x-delete-form
                action="{{ route('meetings.destroy', ['meeting' => $meeting->id]) }}"
                class="float-right"
                :confirm-msg="$confirmMsg"
                >
            </x-delete-form>
        @endif
        
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($isEdit)
                {{ __('Edit') }}
            @else
                {{ __('Add') }}
            @endif
            {{ __('Meeting') }}
            @if ($isEdit)
                : {{ $meeting->name }}
            @endif
        </h2>
        
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ $isEdit ? route('meetings.update', ['meeting' => $meeting->id]) : route('meetings.store') }}">
                
                @csrf
                
                @if ($isEdit)
                    @method('PUT')
                @endif
                
                <div class="md:grid md:gap-8 md:grid-cols-4">
                    
                    <div class="mb-8 col-span-2">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $meeting->name)" required autofocus />
                    </div>
                    
                    <div class="mb-8 col-span-2">
                        <x-label for="venue" :value="__('Venue')" />
                        <x-input id="venue" class="block mt-1 w-full" type="text" name="venue" :value="old('venue', $meeting->venue)" />
                    </div>
                    
                </div>
                
                <div class="mb-8">
                    <x-label for="description" :value="__('Description')" />
                    <p class="text-purple-600 text-sm my-2">
                        {{ __('A brief one-sentence description') }}
                    </p>
                    <x-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description', $meeting->description)" />
                </div>
                
                <div class="md:grid md:gap-8 md:grid-cols-4">
                    
                    <div class="mb-8">
                        <x-label for="occurred_on" :value="__('Date')" />
                        <p class="text-purple-600 text-sm my-2">
                            YYYY-MM-DD
                        </p>
                        <x-input id="occurred_on" class="block mt-1 w-full" type="date" name="occurred_on" :value="old('occurred_on', $meeting->occurred_on->format('Y-m-d'))" required />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="occurred_on_time" :value="__('Time')" />
                        <p class="text-purple-600 text-sm my-2">
                            Montreal time
                        </p>
                        <x-input id="occurred_on_time" class="block mt-1 w-full" type="time" name="occurred_on_time" :value="old('occurred_on_time', optional($meeting->occurred_on_datetime)->format('H:i'))" />
                    </div>
                    
                </div>
                
                <div class="mb-8">
                    <x-label for="type" :value="__('Type')" />
                    @foreach ($types as $type)
                        <div class="">
                            <label for="type-{{ $type->value }}" class="inline-block cursor-pointer my-1">
                                <input id="type-{{ $type->value }}" name="type" type="radio" class="rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ $type->value }}" {{ $type->is($meeting->type) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ $type->getFullDescription() }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
