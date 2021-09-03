<div {{ $attributes }}>
    <x-icons.phone class="inline w-4 h-4 relative bottom-[1px] text-purple-500 mr-2"></x-icons.phone>
    {{ $phone }}
    <x-link href="tel:{{ urlencode($phone) }}" target="_blank" class="ml-2">
        Call
    </x-link>
    <x-copy-button class="ml-2" :textToCopy="$phone"></x-copy-button>
    {{-- <div class="inline-block relative ml-2">
        <x-link role="button" href="javascript:void(0)" x-on:click="copyToClipboard('{!! htmlspecialchars($person->readable_phone, ENT_QUOTES) !!}');show=true;setTimeout(() => show = false, 2000)">
            Copy
        </x-link>
        <div x-show="show" x-cloak class="absolute z-10 left-1/2 -translate-x-1/2 bottom-full text-gray-500 bg-white p-1 shadow-md rounded whitespace-nowrap">
            Copied
        </div>
    </div> --}}
</div>