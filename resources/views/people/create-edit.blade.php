<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($isEdit)
                {{ __('Edit') }}
            @else
                {{ __('Add') }}
            @endif
            {{ __('Person') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ $isEdit ? route('people.update', ['person' => $person->id]) : route('people.store') }}">
                
                @csrf
                
                @if ($isEdit)
                    @method('PUT')
                @endif
                
                <div class="md:grid md:gap-8 md:grid-cols-2 mt-4">
                    
                    <div class="mb-8">
                        <x-label for="given_name" :value="__('Given Name')" />
                        <x-input id="given_name" class="block mt-1 w-full" type="text" name="given_name" :value="old('given_name', $person->given_name)" autofocus />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="family_name" :value="__('Family Name')" />
                        <x-input id="family_name" class="block mt-1 w-full" type="text" name="family_name" :value="old('family_name', $person->family_name)" />
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
                        >{{ old('notes', $person->notes) }}</textarea>
                </div>
                
                <h3 class="font-semibold text-lg text-gray-800 leading-tight mb-2">
                    {{ __('Personal Info') }}
                </h3>
                
                <p class="text-purple-600 mb-6">
                    {{ __('The fields below are for PERSONAL info only (e.g. name@gmail.com). Details related to a specific org (e.g. position@company.org) should be saved with this person\'s positions.') }}
                </p>
                
                <div class="md:grid md:gap-4 md:grid-cols-2">
                    
                    <div class="mb-8">
                        <x-label for="email" :value="__('Personal Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email', $person->email)" />
                    </div>
                
                    <div class="mb-8">
                        <x-label for="phone" :value="__('Personal Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $person->phone)" />
                    </div>
                    
                </div>
                
                <div class="mb-8">
                    <x-label for="website" :value="__('Personal Website')" />
                    <x-input id="website" class="block mt-1 w-full" type="text" name="website" :value="old('website', $person->website)" />
                </div>
                
                <div class="md:grid md:gap-8 md:grid-cols-2">
                
                    <div class="mb-8">
                        <x-label for="street_address" :value="__('Street Address')" />
                        <x-input id="street_address" class="block mt-1 w-full" type="text" name="street_address" :value="old('street_address', $person->street_address)" />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="street_address_2" :value="__('Street Address 2')" />
                        <x-input id="street_address_2" class="block mt-1 w-full" type="text" name="street_address_2" :value="old('street_address_2', $person->street_address_2)" />
                    </div>
                    
                </div>
                
                <div class="md:grid md:gap-4 md:grid-cols-4">
                
                    <div class="mb-8 max-w-xs">
                        <x-label for="city" :value="__('City')" />
                        <div class="mt-1">
                            <x-suggest-input id="city" name="city" :initValue="old('city', $person->city)" :options="$cityOptions" />
                        </div>
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="province" :value="__('Province')" />
                        <div class="mt-1">
                            <x-suggest-input id="province" name="province" :initValue="old('province', $person->province)" :options="$provinceOptions" />
                        </div>
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="country" :value="__('Country')" />
                        <div class="mt-1">
                            <x-suggest-input id="country" name="country" :initValue="old('country', $person->country)" :options="$countryOptions" :asSelect="true" />
                        </div>
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="postal_code" :value="__('Postal Code')" />
                        <x-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code', $person->postal_code)" />
                    </div>
                    
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
