@props([
    'notes' => '',
])

<div class="flex mt-1" x-data="expandableNotes()" x-init="init()">
    <div class="mr-2">
        <x-icons.document-text class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.document-text>
    </div>
    <div class="border-l-2 pl-2 border-purple-200" :class="{ 'whitespace-nowrap overflow-x-hidden text-ellipsis no-br' : !expand }" x-ref="notes">
        {!! nl2br(e($notes)) !!}
    </div>
    <div class="ml-2">
        <button type="button" @click="expand = !expand" class="w-6 h-6" :disabled="!hasOverflowed">
            <x-icons.down x-cloak x-show="!expand && hasOverflowed" class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.down>
            <x-icons.up x-cloak x-show="expand && hasOverflowed" class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.up>
        </button>
    </div>
</div>