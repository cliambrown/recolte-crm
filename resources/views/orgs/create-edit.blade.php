<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($isEdit)
                {{ __('Edit') }}
            @else
                {{ __('Add') }}
            @endif
            {{ __('Org') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ $isEdit ? route('orgs.update', ['org' => $org->id]) : route('orgs.store') }}">
                
                @csrf
                
                @if ($isEdit)
                    @method('PUT')
                @endif
                
                <div class="mb-8">
                    <x-label for="name" :value="__('Name')" />
                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $org->name)" required autofocus />
                </div>
                
                <div class="md:grid md:gap-8 md:grid-cols-2 mt-4">
                
                    <div class="mb-8">
                        <x-label for="street_address" :value="__('Street Address')" />
                        <x-input id="street_address" class="block mt-1 w-full" type="text" name="street_address" :value="old('street_address', $org->street_address)" />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="street_address_2" :value="__('Street Address 2')" />
                        <x-input id="street_address_2" class="block mt-1 w-full" type="text" name="street_address_2" :value="old('street_address_2', $org->street_address_2)" />
                    </div>
                    
                </div>
                
                <div class="md:grid md:gap-8 md:grid-cols-3 mt-4">
                
                    <div class="mb-8">
                        <x-label for="city" :value="__('City')" />
                        <x-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city', $org->city)" />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="province" :value="__('Province')" />
                        <x-input id="province" class="block mt-1 w-full" type="text" name="province" :value="old('province', $org->province)" />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="postal_code" :value="__('Postal Code')" />
                        <x-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code', $org->postal_code)" />
                    </div>
                    
                </div>
                
                <div class="mb-8" x-data="{}">
                    <x-label for="notes" :value="__('Notes')" />
                    <textarea id="notes"
                        class="autoresize w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="notes"
                        maxlength="1000"
                        x-init="resizeTextarea($el)"
                        x-on:input="resizeTextarea($event.target)"
                        >{{ old('notes', $org->notes) }}</textarea>
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
