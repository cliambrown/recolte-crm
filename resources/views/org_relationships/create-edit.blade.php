<x-app-layout>
    <x-slot name="header">
        
        @if ($isEdit)
            @php
                $confirmMsg = __('Are you sure you want to remove the relationship between :parentOrgName and :childOrgName?', ['parentOrgName' => $parentOrg->name, 'childOrgName' => $childOrg->name])
            @endphp
            <x-delete-form
                action="{{ route('org_relationships.destroy', ['org_relationship' => $relationship->id]) }}"
                class="float-right"
                :confirm-msg="$confirmMsg"
                >
                <input type="hidden" name="redirect_url" value="{{ $redirectUrl }}">
            </x-delete-form>
        @endif
        
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($isEdit)
                {{ __('Edit') }}
            @else
                {{ __('Add') }}
            @endif
            {{ __('Org Relationship') }}
        </h2>
        
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <form method="POST" action="{{ $isEdit ? route('org_relationships.update', ['org_relationship' => $relationship->id]) : route('org_relationships.store') }}">
                
                @csrf
                
                @if ($isEdit)
                    @method('PUT')
                @endif
                
                <input type="hidden" name="redirect_url" value="{{ $redirectUrl }}">
                
                <div class="mb-8 max-w-md">
                    <x-label for="parent" :value="__('Parent Org')" />
                    @if ($parentOrg !== null)
                        {{ $parentOrg->name }}
                        <input type="hidden" name="parent_org_id" value="{{ $parentOrg->id }}">
                    @else
                        <div class="mt-1">
                            <x-suggest-input id="parent_org_id"
                                name="parent_org_id"
                                :init-value="old('parent_org_id')"
                                :options-url="route('orgs.api_search')"
                                placeholder-text="{{ __('Search') }}"
                                :as-select="true"
                                label-attr="name_with_short_name"
                                dispatch-event-name="set-parent-org" />
                        </div>
                    @endif
                </div>
                
                <div class="mb-8 max-w-md">
                    <x-label for="child_org_id" :value="__('Child Org')" />
                    @if ($childOrg !== null)
                        {{ $childOrg->name }}
                        <input type="hidden" name="child_org_id" value="{{ $childOrg->id }}">
                    @else
                        <div class="mt-1">
                            <x-suggest-input id="child_org_id"
                                name="child_org_id"
                                :init-value="old('child_org_id')"
                                :options-url="route('orgs.api_search')"
                                placeholder-text="{{ __('Search') }}"
                                :as-select="true"
                                label-attr="name_with_short_name"
                                dispatch-event-name="set-child-org" />
                        </div>
                    @endif
                </div>
                
                <div class="mb-8">
                    <x-label for="child_description" :value="__('Relationship Type')" />
                    <div class="mt-1">
                        <span class="font-semibold">
                            @if ($childOrg !== null)
                                {{ $childOrg->name }}
                            @else
                                <span x-data="{ child_org: '[{{ __('Child Org') }}]', null_child_org: '[{{ __('Child Org') }}]' }" @set-child-org.window="child_org = (!!$event.detail.current_label ? $event.detail.current_label : null_child_org) " x-text="child_org"></span>
                            @endif
                        </span>
                        <span class="text-gray-700">{{ __('is a') }}</span>
                    </div>
                    <x-input id="child_description" class="inline-block w-full max-w-sm" type="text" name="child_description" :value="old('child_description', $relationship->child_description)" />
                    <div class="text-purple-600 text-sm">
                        ({{ __('ex. membre, division, bureau') }})
                    </div>
                    <div>
                        <span class="text-gray-700">{{ __('of') }}</span>
                        <span class="font-semibold">
                            @if ($parentOrg !== null)
                                {{ $parentOrg->name }}
                            @else
                                <span x-data="{ parent_org: '[{{ __('Parent Org') }}]', null_parent_org: '[{{ __('Parent Org') }}]' }" @set-parent-org.window="parent_org = (!!$event.detail.current_label ? $event.detail.current_label : null_parent_org) " x-text="parent_org"></span>
                            @endif
                        </span>
                    </div>
                </div>
                
                <div class="md:grid md:gap-4 md:grid-cols-2">
                    
                    <div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_year" :value="__('Start Year')" />
                            <x-select class="mt-1" name="start_year" id="start_year">
                                <option value="" {{ !old('start_year', $relationship->start_year) ? 'selected' : '' }}></option>
                                @for ($year=get_max_year(); $year>=get_min_year(); --$year)
                                    <option value="{{ $year }}" {{ $year == old('start_year', $relationship->start_year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_month" :value="__('Month')" />
                            <x-select class="mt-1" name="start_month" id="start_month">
                                <option value="" {{ !old('start_month', $relationship->start_month) ? 'selected' : '' }}></option>
                                @for ($month=1; $month<=12; ++$month)
                                    <option value="{{ $month }}" {{ $month == old('start_month', $relationship->start_month) ? 'selected' : '' }}>{{ $month }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="start_day" :value="__('Day')" />
                            <x-select class="mt-1" name="start_day" id="start_day">
                                <option value="" {{ !old('start_day', $relationship->start_day) ? 'selected' : '' }}></option>
                                @for ($day=1; $day<=31; ++$day)
                                    <option value="{{ $day }}" {{ $day == old('start_day', $relationship->start_day) ? 'selected' : '' }}>{{ $day }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                    </div>
                    
                    <div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_year" :value="__('End Year')" />
                            <x-select class="mt-1" name="end_year" id="end_year">
                                <option value="" {{ !old('end_year', $relationship->end_year) ? 'selected' : '' }}></option>
                                @for ($year=get_max_year(); $year>=get_min_year(); --$year)
                                    <option value="{{ $year }}" {{ $year == old('end_year', $relationship->end_year) ? 'selected' : '' }}>{{ $year }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_month" :value="__('Month')" />
                            <x-select class="mt-1" name="end_month" id="end_month">
                                <option value="" {{ !old('end_month', $relationship->end_month) ? 'selected' : '' }}></option>
                                @for ($month=1; $month<=12; ++$month)
                                    <option value="{{ $month }}" {{ $month == old('end_month', $relationship->end_month) ? 'selected' : '' }}>{{ $month }}</option>
                                @endfor
                            </x-select>
                        </div>
                        
                        <div class="mb-8 inline-block align-top mr-2">
                            <x-label for="end_day" :value="__('Day')" />
                            <x-select class="mt-1" name="end_day" id="end_day">
                                <option value="" {{ !old('end_day', $relationship->end_day) ? 'selected' : '' }}></option>
                                @for ($day=1; $day<=31; ++$day)
                                    <option value="{{ $day }}" {{ $day == old('end_day', $relationship->end_day) ? 'selected' : '' }}>{{ $day }}</option>
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
                        >{{ old('notes', $relationship->notes) }}</textarea>
                </div>
                
                <div class="mb-8">
                    <x-button>{{ __('Save') }}</x-button>
                </div>
                
            </form>
            
        </div>
    </div>
    
</x-app-layout>
