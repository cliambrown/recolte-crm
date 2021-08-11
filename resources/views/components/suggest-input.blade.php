@props([
    'currentInput' => null,
    'currentValue' => null,
    'options',
    'id',
    'asSelect' => false,
])

<div class="relative" x-data="suggestInput({ as_select: {{ $asSelect ? 'true' : 'false' }}, id: '{{ $id }}', current_value: {{ $currentValue ? "'".$currentValue."'" : 'null' }}, current_input: {{ $currentInput ? "'".$currentInput."'" : 'null' }}, options: {{ json_encode($options) }} })" x-init="init()">
    <div class="relative">
        @if ($asSelect)
            <button type="button"
                x-ref="select_button"
                x-show="!show_options"
                class="rounded-md bg-purple-500 bg-opacity-20 absolute block w-full inset-0 text-left pt-2 pb-2 px-3 outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                x-on:click="$refs.input_el.focus()"
                x-on:keydown.arrow-down.prevent="$refs.input_el.focus();"
                x-text="current_value"></button>
        @endif
        <x-input {{ $attributes->merge(['class' => 'block w-full']) }}
            :id="$id"
            type="text"
            x-model="current_input"
            x-ref="input_el"
            x-on:input.debounce="filterOptions($event.target.value, true)"
            x-on:keydown.enter.stop.prevent="selectOption()"
            x-on:keydown.arrow-up.prevent="optionUp"
            x-on:keydown.arrow-down.prevent="optionDown"
            x-on:keydown.arrow-down.prevent="optionDown"
            x-on:keydown.escape.prevent="show_options = false"
            x-on:click="show_options = true"
            x-on:focus="show_options = true"
            x-on:blur="show_options = false"
            />
    </div>
    <div x-show="show_options" x-cloak class="absolute z-10 w-full bg-white rounded-sm shadow-lg border">
        <ul x-show="!!options_filtered.length" x-ref="listbox" class="py-1 overflow-auto text-base leading-6 rounded-md shadow-xs max-h-60 focus:outline-none sm:text-sm sm:leading-5">
            <template x-for="(option, index) in options_filtered">
                <li x-bind:id="'{{ $id }}-option-'+index">
                    <button type="button"
                        x-text="option.item.name"
                        :class="{ 'text-white bg-indigo-600': index === focused_option_index, 'hover:bg-indigo-50': index !== focused_option_index }"
                        class="block w-full text-left py-1 px-3 text-base"
                        x-on:mousedown.prevent="selectOption(index)"
                        tabindex="-1"
                        ></button>
                </li>
            </template>
        </ul>
        <div x-show="!options_filtered.length" class="py-2 px-3 text-gray-500">
            {{ __('Nothing to show') }}
        </div>
    </div>
</div>