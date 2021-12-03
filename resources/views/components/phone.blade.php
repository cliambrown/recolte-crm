<div {{ $attributes->merge(['class' => 'flex']) }}>
    <div class="mr-2">
        <x-icons.phone class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.phone>
    </div>
    <div>
        {{ $phone }}
        <x-link href="tel:{{ urlencode($phone) }}" target="_blank" class="ml-2">
            Call
        </x-link>
        <x-copy-button class="ml-2" :textToCopy="$phone"></x-copy-button>
    </div>
</div>