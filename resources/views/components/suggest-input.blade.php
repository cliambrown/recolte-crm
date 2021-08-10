@props([
    'currentInput',
    'options',
])

<div class="relative" x-data="suggestInput({ current_input: {{ $currentInput ? "'".$currentInput."'" : 'null' }}, options: {{ json_encode($options) }} })" x-init="init()">
    <x-input {{ $attributes }}
        type="text"
        x-model="current_input"
        x-ref="input_el"
        x-on:input.debounce="filterOptions($event.target.value)"
        x-on:keydown.enter.stop.prevent="selectOption()"
        x-on:keydown.arrow-up.prevent="optionUp"
        x-on:keydown.arrow-down.prevent="optionDown"
        x-on:keydown.arrow-down.prevent="optionDown"
        x-on:keydown.escape.prevent="show_options = false"
        x-on:click="show_options = true"
        x-on:focus="show_options = true"
        x-on:blur="show_options = false"
        />
    <div x-show="show_options" class="absolute z-10 w-full bg-white rounded-sm shadow-lg border">
        <ul x-ref="listbox" class="py-1 overflow-auto text-base leading-6 rounded-md shadow-xs max-h-60 focus:outline-none sm:text-sm sm:leading-5">
            <template x-for="(option, index) in options_filtered">
                <li>
                    <button type="button"
                        x-text="option"
                        :class="{ 'text-white bg-indigo-600': index === focused_option_index }"
                        class="block w-full text-left py-1 px-3"
                        x-on:mousedown.prevent="selectOption(index)"
                        tabindex="-1"
                        ></button>
                </li>
            </template>
        </ul>
    </div>
</div>