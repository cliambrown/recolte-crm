<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orgs') }}
        </h2>
    </x-slot>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <x-button href="{{ route('orgs.create') }}">
                <x-icons.plus></x-icons.plus> {{ __('Add') }} {{ __('Org') }}
            </x-button>
            
            @foreach ($orgs as $org)
                
                <div class="shadow bg-white p-4 rounded my-4">
                    <div class="float-right ml-2 mb-2">
                        <x-button href="{{ route('orgs.show', ['org' => $org->id]) }}" padding="tight" btncolor="blue" class="mr-2">
                            {{ __('View') }}
                        </x-button>
                        <x-button href="{{ route('orgs.edit', ['org' => $org->id]) }}" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    <div class="font-bold text-gray-700">
                        {{ $org->name }}
                        @if ($org->short_name)
                            <span class="text-sm text-gray-500 font-normal mx-1">/</span>
                            {{ $org->short_name }}
                        @endif
                    </div>
                    <div class="text-sm text-gray-700 overflow-hidden mt-1">
                        <div class="-mx-1">
                            @if ($org->website)
                                <a href="{{ $org->website }}" target="_blank" class="cursor-pointer mx-1">
                                    <x-icons.globe class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.globe>
                                    {{ get_domain($org->website) }}
                                </a>
                            @endif
                            @if ($org->one_line_address)
                                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($org->one_line_address) }}" target="_blank" class="cursor-pointer mx-1">
                                    <x-icons.location-marker class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.location-marker>
                                    {{ $org->one_line_address }}
                                </a>
                            @endif
                            @if ($org->readable_phone)
                                <a href="tel:{{ $org->readable_phone }}" class="cursor-pointer mx-1">
                                    <x-icons.phone class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.phone>
                                    {{ $org->readable_phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                    @if ($org->types->count())
                        <div class="text-sm text-gray-600 mt-1">
                            @foreach ($org->types as $orgType)
                                {{ $orgType->name }}
                                @if (!$loop->last)
                                    <span class="text-sm text-gray-500 font-normal mx-1"> | </span>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            
            @endforeach
            
            {{ $orgs->links() }}
            
        </div>
    </div>
    
</x-app-layout>
