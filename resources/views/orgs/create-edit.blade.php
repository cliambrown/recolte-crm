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
                
                <div class="md:grid md:gap-8 md:grid-cols-4">
                    
                    <div class="mb-8 col-span-3">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $org->name)" required autofocus />
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="short_name" :value="__('Short Name')" />
                        <x-input id="short_name" class="block mt-1 w-full" type="text" name="short_name" :value="old('short_name', $org->short_name)" />
                    </div>
                    
                </div>
                
                <div class="md:grid md:gap-8 md:grid-cols-2">
                
                    <div class="mb-8">
                        <x-label for="street_address" :value="__('Street Address')" />
                        <x-input id="street_address" class="block mt-1 w-full" type="text" name="street_address" :value="old('street_address', $org->street_address)" />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="street_address_2" :value="__('Street Address 2')" />
                        <x-input id="street_address_2" class="block mt-1 w-full" type="text" name="street_address_2" :value="old('street_address_2', $org->street_address_2)" />
                    </div>
                    
                </div>
                
                <div class="md:grid md:gap-4 md:grid-cols-4">
                
                    <div class="mb-8 max-w-xs">
                        <x-label for="city" :value="__('City')" />
                        <div class="mt-1">
                            <x-suggest-input id="city" name="city" :currentInput="old('city', $org->city)" :options="$cityOptions" />
                        </div>
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="province" :value="__('Province')" />
                        <div class="mt-1">
                            <x-suggest-input id="province" name="province" :currentInput="old('province', $org->province)" :options="$provinceOptions" />
                        </div>
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="country" :value="__('Country')" />
                        {{-- <x-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country', $org->country)" /> --}}
                        {{-- <x-select id="country" class="block mt-1 w-full" name="country">
                            <option value="" {{ old('country', $org->country) === null ? 'selected' : '' }}></option>
                            @foreach (get_all_countries() as $country)
                                <option value="{{ $country }}" {{ old('country', $org->country) === $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        </x-select> --}}
                        <div class="mt-1">
                            <x-suggest-input id="country" name="country" :currentValue="old('country', $org->country)" :currentInput="old('country', $org->country)" :options="$countryOptions" :asSelect="true" />
                        </div>
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="postal_code" :value="__('Postal Code')" />
                        <x-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code', $org->postal_code)" />
                    </div>
                    
                </div>
                
                <div class="mb-8">
                    <x-label for="website" :value="__('Website')" />
                    <x-input id="website" class="block mt-1 w-full" type="text" name="website" :value="old('website', $org->website)" />
                </div>
                
                <div class="md:grid md:gap-4 md:grid-cols-2">
                
                    <div class="mb-8">
                        <x-label for="phone" :value="__('Phone')" />
                        <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $org->phone)" />
                    </div>
                    
                    <div class="mb-8">
                        <x-label for="email" :value="__('Main Email')" />
                        <x-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email', $org->email)" />
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
                    <x-label for="types" :value="__('Categories')" />
                    @foreach ($orgTypes as $orgType)
                        <div class="">
                            <label for="types-{{ $orgType->id }}" class="inline-block cursor-pointer my-1">
                                <input id="types-{{ $orgType->id }}" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="type_ids[]" value="{{ $orgType->id }}" {{ in_array($orgType->id, old('type_ids', $org->type_ids)) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-600">{{ $orgType->name }}</span>
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
