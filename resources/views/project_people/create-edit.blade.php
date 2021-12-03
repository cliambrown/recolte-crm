<x-app-layout>
    <x-slot name="header">
        
        @if ($isEdit)
            <x-delete-form
                action="{{ route('projects.people.destroy', ['project' => $project->id, 'project_person' => $projectPerson->id]) }}"
                class="float-right"
                confirm-msg="{{ __('Are you sure you want to remove :personName from :projectName?', ['personName' => $person->full_name, 'projectName' => $project->name]) }}"
                button-text="{{ __('Remove') }}"
                >
            </x-delete-form>
        @endif
        
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($isEdit)
                {{ __('Edit') }}
            @else
                {{ __('Add') }}
            @endif
            {{ __('Project Person') }}
            — {{ $project->name }}
        </h2>
        
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ $isEdit ? route('projects.people.update', ['project' => $project->id, 'project_person' => $projectPerson->id]) : route('projects.people.store', ['project' => $project->id]) }}">
                
                @csrf
                
                @if ($isEdit)
                    @method('PUT')
                @endif
                
                <div class="mb-8 max-w-md">
                    <x-label for="person_id" :value="__('Person')" />
                    @if ($person !== null)
                        {{ $person->full_name }}
                        <input type="hidden" name="person_id" value="{{ $person->id }}">
                    @else
                        <div class="mt-1">
                            <x-suggest-input id="person_id"
                                name="person_id"
                                :initValue="old('person_id')"
                                :optionsUrl="route('people.api_search')"
                                placeholderText="{{ __('Search') }}"
                                :asSelect="true"
                                labelAttr="full_name" />
                        </div>
                    @endif
                </div>
                
                <div class="md:grid md:gap-4 md:grid-cols-2">
                    
                    <div class="mb-8">
                        <x-label for="role" :value="__('Role on this Project')" />
                        <x-input id="role" class="block mt-1 w-full" type="text" name="role" :value="old('role', $projectPerson->role)" />
                    </div>
                    
                </div>
                
                <div class="md:grid md:gap-4 md:grid-cols-2">
                    
                    <div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_year" :value="__('Start Year')" />
                            <x-select class="mt-1" name="start_year" id="start_year">
                                <option value="" {{ !old('start_year', $projectPerson->start_year) ? 'selected' : '' }}></option>
                                @for ($year=get_max_year(); $year>=get_min_year(); --$year)
                                    <option value="{{ $year }}" {{ $year == old('start_year', $projectPerson->start_year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_month" :value="__('Month')" />
                            <x-select class="mt-1" name="start_month" id="start_month">
                                <option value="" {{ !old('start_month', $projectPerson->start_month) ? 'selected' : '' }}></option>
                                @for ($month=1; $month<=12; ++$month)
                                    <option value="{{ $month }}" {{ $month == old('start_month', $projectPerson->start_month) ? 'selected' : '' }}>{{ $month }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_day" :value="__('Day')" />
                            <x-select class="mt-1" name="start_day" id="start_day">
                                <option value="" {{ !old('start_day', $projectPerson->start_day) ? 'selected' : '' }}></option>
                                @for ($day=1; $day<=31; ++$day)
                                    <option value="{{ $day }}" {{ $day == old('start_day', $projectPerson->start_day) ? 'selected' : '' }}>{{ $day }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                    </div>
                    
                    <div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_year" :value="__('End Year')" />
                            <x-select class="mt-1" name="end_year" id="end_year">
                                <option value="" {{ !old('end_year', $projectPerson->end_year) ? 'selected' : '' }}></option>
                                @for ($year=get_max_year(); $year>=get_min_year(); --$year)
                                    <option value="{{ $year }}" {{ $year == old('end_year', $projectPerson->end_year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_month" :value="__('Month')" />
                            <x-select class="mt-1" name="end_month" id="end_month">
                                <option value="" {{ !old('end_month', $projectPerson->end_month) ? 'selected' : '' }}></option>
                                @for ($month=1; $month<=12; ++$month)
                                    <option value="{{ $month }}" {{ $month == old('end_month', $projectPerson->end_month) ? 'selected' : '' }}>{{ $month }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_day" :value="__('Day')" />
                            <x-select class="mt-1" name="end_day" id="end_day">
                                <option value="" {{ !old('end_day', $projectPerson->end_day) ? 'selected' : '' }}></option>
                                @for ($day=1; $day<=31; ++$day)
                                    <option value="{{ $day }}" {{ $day == old('end_day', $projectPerson->end_day) ? 'selected' : '' }}>{{ $day }}</option>
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
                        >{{ old('notes', $projectPerson->notes) }}</textarea>
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
