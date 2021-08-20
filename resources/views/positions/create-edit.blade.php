<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($isEdit)
                {{ __('Edit') }}
            @else
                {{ __('Add') }}
            @endif
            {{ __('Position') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ $isEdit ? route('positions.update', ['position' => $position->id]) : route('positions.store') }}">
                
                @csrf
                
                @if ($isEdit)
                    @method('PUT')
                @endif
                
                <div class="mb-8 max-w-md">
                    <x-label for="person" :value="__('Person')" />
                </div>
                
                <div class="mb-8 max-w-md">
                    <x-label for="org_id" :value="__('Org')" />
                    @if ($org !== null)
                        <x-input id="org" class="block mt-1 w-full" type="text" name="org_id" :value="$org->name" readonly />
                    @else
                        <div class="mt-1">
                            <x-suggest-input id="org_id"
                                name="country"
                                :initValue="old('org_id')"
                                :optionsUrl="route('orgs.api_search')"
                                placeholderText="{{ __('Search') }}"
                                :asSelect="true" />
                            {{-- <x-suggest-input id="org_id"
                                name="org_id"
                                :currentValue="old('org_id', $position->org_id)"
                                :optionsUrl="route('orgs.api_search')"
                                labelAttr="name"
                                placeholderText="{{ __('Search') }}"
                                :asSelect="true" /> --}}
                        </div>
                    @endif
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
