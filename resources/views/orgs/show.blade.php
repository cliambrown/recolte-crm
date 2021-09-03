<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-gray-500 mr-3 font-normal">
                {{ __('Org') }}
            </span>
            {{ $org->name }}
            @if ($org->short_name)
            <span class="text-gray-500 ml-3">
                — {{ $org->short_name }}
            </span>
            @endif
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-4">
                <x-button href="{{ route('orgs.edit', ['org' => $org->id]) }}" btncolor="blue" padding="tight" class="">
                    Edit Info
                </x-button>
            </div>
            
            @if ($org->website)
                <div class="my-2">
                    <x-icons.globe class="inline w-4 h-4 relative bottom-[1px] text-purple-500 mr-2"></x-icons.globe>
                    <x-link href="{{ $org->website }}" target="_blank">
                        {{ $org->website }}
                    </x-link>
                </div>
            @endif
            
            @if ($org->one_line_address)
                <div class="my-2" x-data="{ show: false }">
                    <x-icons.location-marker class="inline w-4 h-4 relative bottom-[1px] text-purple-500 mr-2"></x-icons.location-marker>
                    {{ $org->one_line_address }}
                    <x-link href="https://www.google.com/maps/search/?api=1&query={{ urlencode($org->one_line_address) }}" target="_blank" class="ml-2">
                        Maps
                    </x-link>
                    <div class="inline-block relative ml-2">
                        <x-link role="button" href="javascript:void(0)" x-on:click="copyToClipboard('{!! htmlspecialchars($org->one_line_address, ENT_QUOTES) !!}');show=true;setTimeout(() => show = false, 2000)">
                            Copy
                        </x-link>
                        <div x-show="show" x-cloak class="absolute z-10 left-1/2 -translate-x-1/2 bottom-full text-purple-500 bg-white p-1 shadow-md rounded whitespace-nowrap">
                            Copied
                        </div>
                    </div>
                </div>
            @endif
            
            @if ($org->email)
                <div class="my-2">
                    <x-icons.at class="inline w-4 h-4 relative bottom-[1px] text-purple-500 mr-2"></x-icons.at>
                    <x-link href="mailto:{{ $org->email }}" target="_blank">
                        {{ $org->email }}
                    </x-link>
                </div>
            @endif
            
            @if ($org->readable_phone)
                <x-phone class="my-2" :phone="$org->readable_phone"></x-phone>
            @endif
            
            @if ($org->notes)
                <div class="flex">
                    <div>
                        <x-icons.document-text class="inline w-4 h-4 relative bottom-[1px] text-purple-500 mr-2"></x-icons.document-text>
                    </div>
                    <div class="ml-2 border-l-2 pl-2 border-purple-200">
                        {!! nl2br(e($org->notes)) !!}
                    </div>
                </div>
            @endif
            
            @if ($org->types->count())
                <div class="overflow-hidden">
                    <div class="text-gray-700 mt-4 -mx-1">
                        @foreach ($org->types as $orgType)
                            <div class="inline-block mx-1 py-1 px-2 bg-purple-100 text-purple-800 rounded text-sm font-semibold">
                                {{ $orgType->name }}
                            </div>
                            {{-- @if (!$loop->last)
                                <span class="text-sm text-gray-600 font-normal mx-1"> | </span>
                            @endif --}}
                        @endforeach
                    </div>
                </div>
            @endif
            
        </div>
    </div>
    
</x-app-layout>
