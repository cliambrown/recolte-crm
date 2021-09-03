<div {{ $attributes->merge(['class' => 'inline-block relative']) }} x-data="{ show: false }">
    <x-link role="button" href="javascript:void(0)" x-on:click="copyToClipboard('{!! htmlspecialchars($textToCopy, ENT_QUOTES) !!}');show=true;setTimeout(() => show = false, 2000)">
        {{ __('Copy') }}
    </x-link>
    <div x-show="show" x-cloak class="absolute z-10 left-1/2 -translate-x-1/2 bottom-full text-gray-500 bg-white p-1 shadow-md rounded whitespace-nowrap">
        {{ __('Copied') }}
    </div>
</div>