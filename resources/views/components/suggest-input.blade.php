@props([
    'initValue' => null,
    'valueAttr' => 'id',
    'labelAttr' => 'name',
    'name',
    'options' => [],
    'optionsUrl' => null,
    'id',
    'asSelect' => false,
    'placeholderText' => __('Select...'),
    'dispatchEventName' => null,
])

@php
    $initLabel = old($name.'_label');
    $initInput = old($name.'_input', $initValue);
    if (!$asSelect && old($name.'_input')) {
        $initValue = old($name.'_input');
    }
    $alpineData = [
        'as_select' => !!$asSelect,
        'id' => $id,
        'init_value' => $initValue,
        'init_input' => old($name.'_input', $initValue),
        'init_label' => old($name.'_label'),
        'options' => $options,
        'options_url' => $optionsUrl,
        'value_attr' => $valueAttr,
        'label_attr' => $labelAttr,
        'dispatch_event_name' => $dispatchEventName,
    ];
@endphp

<div class="relative" x-data="suggestInput({!! htmlspecialchars(json_encode($alpineData), ENT_QUOTES) !!})" x-init="init()">
    
    @if ($asSelect)
        <input type="hidden" name="{{ $name }}" x-model="current_value">
    @else
        <input type="hidden" name="{{ $name }}" x-model="current_input">
    @endif
    
    <div class="relative">
        <div class="relative group" x-show="show_input" x-ref="input_group" {{ $asSelect ? 'x-cloak' : '' }} x-on:focusout="onLeaveInput">
            {{-- The input is repeated because optional attributes (debounce time) break blade components --}}
            @if ($optionsUrl)
                <x-input {{ $attributes->merge(['class' => 'block w-full min-w-0']) }}
                    :id="$id"
                    name="{{ $name }}_input"
                    type="text"
                    x-model="current_input"
                    x-ref="input_el"
                    x-on:input.debounce.1s="filterOptions($event.target.value, true)"
                    x-on:keydown.enter.stop.prevent="selectOption()"
                    x-on:keydown.arrow-up.prevent="optionNav(-1)"
                    x-on:keydown.arrow-down.prevent="optionNav(1)"
                    x-on:keydown.escape.prevent="show_options = false"
                    x-on:click="show_options = true"
                    x-on:focus="show_options = true"
                    />
            @else
                <x-input {{ $attributes->merge(['class' => 'block w-full min-w-0']) }}
                    :id="$id"
                    type="text"
                    x-model="current_input"
                    x-ref="input_el"
                    x-on:input.debounce.250ms="filterOptions($event.target.value, true)"
                    x-on:keydown.enter.stop.prevent="selectOption()"
                    x-on:keydown.arrow-up.prevent="optionNav(-1)"
                    x-on:keydown.arrow-down.prevent="optionNav(1)"
                    x-on:keydown.escape.prevent="show_options = false"
                    x-on:click="show_options = true"
                    x-on:focus="show_options = true"
                    />
            @endif
            <button type="button"
                class="absolute h-full w-10 p-2 inset-y-0 right-0 my-auto text-gray-500 hidden rounded-md group-focus-within:block focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                x-on:click="clearInput"
                x-on:keydown.tab="onClearInputTabAway">
                    <x-icons.x></x-icons.x>
            </button>
        </div>
        @if ($asSelect)
            <input type="hidden" name="{{ $name }}_label" x-model="current_label">
            <button type="button"
                x-ref="select_button"
                x-show="!show_input"
                class="bg-white block w-full text-left py-2 pl-3 pr-8 outline-none rounded-md shadow-sm border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                x-on:click="onSelectButtonClick"
                x-on:keydown.arrow-down.prevent="onSelectButtonClick">
                    <span x-text="current_label"></span>
                    <span x-show="!current_label" class="text-gray-500">{{ $placeholderText }}</span>
                    <span class="opacity-0">&nbsp;</span>
                    <x-icons.down class="text-gray-500 absolute inset-y-0 right-2 my-auto z-10"></x-icons.down>
            </button>
        @endif
    </div>
    
    <div x-show="show_options" x-cloak class="absolute z-10 w-full bg-white rounded-sm shadow-lg border">
        @if ($optionsUrl)
            <ul x-show="options_loading" class="py-1 overflow-auto text-sm leading-5 max-h-60 focus:outline-none">
                <li class="py-2 px-3 text-gray-500">
                    {{ __('Loading') }}...
                </li>
            </ul>
        @endif
        <ul x-show="!options_loading" x-ref="listbox" class="py-1 overflow-auto text-sm leading-5 max-h-60 focus:outline-none">
            <li x-show="!filtered_option_ids.length" class="py-2 px-3 text-gray-500">
                {{ __('No results') }}
            </li>
            <template x-if="!!current_value">
                <li x-bind:id="'{{ $id }}-option-clear'">
                    <button type="button"
                        :class="{ 'text-white bg-indigo-600': focused_option_index === -1, 'hover:bg-indigo-50': focused_option_index !== -1 }"
                        class="block w-full text-left py-1 px-3 text-base"
                        x-on:mousedown.prevent="clearValue"
                        tabindex="-1"
                        >
                        [{{ __('Clear') }}]
                    </button>
                </li>
            </template>
            <template x-for="(optionID, index) in filtered_option_ids">
                <li x-bind:id="'{{ $id }}-option-'+index">
                    <button type="button"
                        x-text="options[optionID].label"
                        :class="{ 'text-white bg-indigo-600': index === focused_option_index, 'hover:bg-indigo-50': index !== focused_option_index }"
                        class="block w-full text-left py-1 px-3 text-base"
                        x-on:mousedown.prevent="selectOption(index)"
                        tabindex="-1"
                        ></button>
                </li>
            </template>
        </ul>
    </div>
    
</div>