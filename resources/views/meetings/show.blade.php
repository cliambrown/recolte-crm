<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-gray-500 mr-3 font-normal">
                {{ __('Meeting') }}
            </span>
            {{ $meeting->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <div class="mb-4">
                <x-button href="{{ route('meetings.edit', ['meeting' => $meeting->id]) }}" btncolor="blue" padding="tight" class="">
                    {{ __('Edit') }} {{ __('Info') }}
                </x-button>
            </div>
            
            <div class="text-purple-600 text-sm mb-1">
                {{ $meeting->type->description }}
            </div>
            
            @if ($meeting->description)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.info class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.info>
                    </div>
                    <div>
                        {{ $meeting->description }}
                    </div>
                </div>
            @endif
            
            @if ($meeting->venue)
                <div class="my-2 flex" x-data="{ show: false }">
                    <div class="mr-2">
                        <x-icons.location-marker class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.location-marker>
                    </div>
                    <div>
                        {{ $meeting->venue }}
                    </div>
                </div>
            @endif
            
            <div class="my-2 flex" x-data="{ show: false }">
                <div class="mr-2">
                    <x-icons.calendar class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.calendar>
                </div>
                <div>
                    @if ($meeting->occurred_on_datetime)
                        {{ $meeting->occurred_on_datetime->format('Y-m-d H:i') }}
                    @else
                        {{ $meeting->occurred_on_datetime->format('Y-m-d') }}
                    @endif
                </div>
            </div>
            
        </div>
    </div>
    
</x-app-layout>
