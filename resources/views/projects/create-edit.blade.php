<x-app-layout>
    <x-slot name="header">
        
        @if ($isEdit)
            @php
                $confirmMsg = __('Are you sure you want to delete ":projectName"?', ['projectName' => $project->name])
            @endphp
            <x-delete-form
                action="{{ route('projects.destroy', ['project' => $project->id]) }}"
                class="float-right"
                :confirm-msg="$confirmMsg"
                >
            </x-delete-form>
        @endif
        
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($isEdit)
                {{ __('Edit') }}
            @else
                {{ __('Add') }}
            @endif
            {{ __('Project') }}
            @if ($isEdit)
                : {{ $project->name }}
            @endif
        </h2>
        
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ $isEdit ? route('projects.update', ['project' => $project->id]) : route('projects.store') }}">
                
                @csrf
                
                @if ($isEdit)
                    @method('PUT')
                @endif
                
                <div class="md:grid md:gap-8 md:grid-cols-4">
                    
                    <div class="mb-8 col-span-3">
                        <x-label for="name" :value="__('Name')" />
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $project->name)" required autofocus />
                    </div>
                    
                    <div class="mb-8 max-w-xs">
                        <x-label for="short_name" :value="__('Short Name')" />
                        <x-input id="short_name" class="block mt-1 w-full" type="text" name="short_name" :value="old('short_name', $project->short_name)" />
                    </div>
                    
                </div>
                
                <div class="mb-8">
                    <x-label for="description" :value="__('Description')" />
                    <p class="text-purple-600 text-sm my-2">
                        {{ __('A brief one-sentence description') }}
                    </p>
                    <x-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description', $project->description)" />
                </div>
                
                <div class="md:grid md:gap-4 md:grid-cols-2">
                    
                    <div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_year" :value="__('Start Year')" />
                            <x-select class="mt-1" name="start_year" id="start_year">
                                <option value="" {{ !old('start_year', $project->start_year) ? 'selected' : '' }}></option>
                                @for ($year=get_max_year(); $year>=get_min_year(); --$year)
                                    <option value="{{ $year }}" {{ $year == old('start_year', $project->start_year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_month" :value="__('Month')" />
                            <x-select class="mt-1" name="start_month" id="start_month">
                                <option value="" {{ !old('start_month', $project->start_month) ? 'selected' : '' }}></option>
                                @for ($month=1; $month<=12; ++$month)
                                    <option value="{{ $month }}" {{ $month == old('start_month', $project->start_month) ? 'selected' : '' }}>{{ $month }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_day" :value="__('Day')" />
                            <x-select class="mt-1" name="start_day" id="start_day">
                                <option value="" {{ !old('start_day', $project->start_day) ? 'selected' : '' }}></option>
                                @for ($day=1; $day<=31; ++$day)
                                    <option value="{{ $day }}" {{ $day == old('start_day', $project->start_day) ? 'selected' : '' }}>{{ $day }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                    </div>
                    
                    <div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_year" :value="__('End Year')" />
                            <x-select class="mt-1" name="end_year" id="end_year">
                                <option value="" {{ !old('end_year', $project->end_year) ? 'selected' : '' }}></option>
                                @for ($year=get_max_year(); $year>=get_min_year(); --$year)
                                    <option value="{{ $year }}" {{ $year == old('end_year', $project->end_year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_month" :value="__('Month')" />
                            <x-select class="mt-1" name="end_month" id="end_month">
                                <option value="" {{ !old('end_month', $project->end_month) ? 'selected' : '' }}></option>
                                @for ($month=1; $month<=12; ++$month)
                                    <option value="{{ $month }}" {{ $month == old('end_month', $project->end_month) ? 'selected' : '' }}>{{ $month }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_day" :value="__('Day')" />
                            <x-select class="mt-1" name="end_day" id="end_day">
                                <option value="" {{ !old('end_day', $project->end_day) ? 'selected' : '' }}></option>
                                @for ($day=1; $day<=31; ++$day)
                                    <option value="{{ $day }}" {{ $day == old('end_day', $project->end_day) ? 'selected' : '' }}>{{ $day }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                    </div>
                    
                </div>
                
                <div class="mb-8" x-data="{}">
                    <x-label for="notes" :value="__('Notes')" />
                    <textarea id="notes"
                        class="autoresize w-full mt-1 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                        name="notes"
                        maxlength="1000"
                        x-init="resizeTextarea($el)"
                        x-on:input="resizeTextarea($event.target)"
                        >{{ old('notes', $project->notes) }}</textarea>
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
