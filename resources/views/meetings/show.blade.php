<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-gray-500 mr-3 font-normal">
                {{ __('Meeting') }}
            </span>
            {{ $meeting->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <div class="mb-4">
                <x-button href="{{ route('meetings.edit', ['meeting' => $meeting->id]) }}" btncolor="blue" padding="tight" class="">
                    {{ __('Edit') }} {{ __('Info') }}
                </x-button>
            </div>
            
            <div class="text-purple-600 text-sm mb-1">
                {{ $meeting->type->description }}
            </div>
            
            @if ($meeting->description)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.info class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.info>
                    </div>
                    <div>
                        {{ $meeting->description }}
                    </div>
                </div>
            @endif
            
            @if ($meeting->venue)
                <div class="my-2 flex" x-data="{ show: false }">
                    <div class="mr-2">
                        <x-icons.location-marker class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.location-marker>
                    </div>
                    <div>
                        {{ $meeting->venue }}
                    </div>
                </div>
            @endif
            
            <div class="my-2 flex" x-data="{ show: false }">
                <div class="mr-2">
                    <x-icons.calendar class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.calendar>
                </div>
                <div>
                    @if ($meeting->occurred_on_datetime)
                        {{ $meeting->occurred_on_datetime->format('Y-m-d H:i') }}
                    @else
                        {{ $meeting->occurred_on->format('Y-m-d') }}
                    @endif
                </div>
            </div>
            
            <h3 class="mt-8 my-4 text-lg text-gray-700">{{ __('Participants') }}</h3>
            
            <div class="mb-8">
                <x-label for="person_id" :value="__('Person')" />
                <div class="mt-1 max-w-md lg:flex-grow flex">
                    <div class="w-full min-w-0 mr-2">
                        <x-suggest-input id="person_id"
                            name="person_id"
                            :init-value="old('person_id')"
                            :options-url="route('people.api_search')"
                            placeholder-text="{{ __('Search') }}"
                            :as-select="true"
                            label-attr="full_name"
                            dispatch-event-name="person-selected"
                            class="w-full min-w-0"
                            @person-added.window="clearValue()" />
                    </div>
                    <form x-data="{ person_id: 0, enabled: false, loading: false }"
                        @person-selected.window="person_id=parseIntNotNaN($event.detail.current_value)"
                        @submit.prevent="loading=true;$dispatch('add-person', { person_id: person_id })"
                        x-init="$watch('person_id', value => enabled = (value > 0))"
                        @person-add-finished.window="loading=false" >
                            <x-button x-bind:disabled="!enabled">
                                <x-icons.plus></x-icons.plus> {{ __('Add') }}
                            </x-button>
                            <span x-show="loading" class="ml-2">Loading...</span>
                    </form>
                </div>
            </div>
            
            <div x-data="{
                    participant_ids: {{ $meeting->participants->pluck('id')->toJson() }},
                    participants: {{ $meeting->participants->keyBy('id') }},
                    addPerson(personID) {
                        axios.post('{{ route('meeting_participants.api_store') }}', {
                            person_id: personID,
                            meeting_id: {{ $meeting->id }},
                        })
                        .then(response => {
                            var success = _.get(response, 'data.success', false);
                            if (success !== true) {
                                var msg = _.get(response, 'data.message', '{{ __('An unknown error occurred.') }}');
                                alert(msg);
                                return false;
                            }
                            var participant = response.data.participant;
                            participant.person = response.data.person;
                            this.participants[participant.id] = participant;
                            this.participant_ids.push(participant.id);
                            this.dispatchEvent('person-added');
                        })
                        .catch(error => {
                            alert(getReadableAxiosError(error));
                        })
                        .finally(() => {
                            this.dispatchEvent('person-add-finished');
                        });
                    },
                    removeOrg(participantID, orgID) {
                        axios.post('{{ route('meeting_participants.api_remove_org') }}', {
                            participant_id: participantID,
                            org_id: orgID,
                        })
                        .then(response => {
                            var success = _.get(response, 'data.success', false);
                            if (success !== true) {
                                var msg = _.get(response, 'data.message', '{{ __('An unknown error occurred.') }}');
                                alert(msg);
                                return false;
                            }
                            var participant = _.get(this.participants, participantID);
                            if (!participant) return false;
                            participant.orgs = _.filter(participant.orgs, org => org.id !== orgID);
                        })
                        .catch(error => {
                            alert(getReadableAxiosError(error));
                        });
                    },
                    showAddOrg(participantID) {
                        let participant = this.participants[participantID];
                        this.$el.dispatchEvent(new CustomEvent('add-org-show', { bubbles: true, detail: { participant: this.participants[participantID] } }));
                    },
                    dispatchEvent(eventName) {
                        this.$el.dispatchEvent(new CustomEvent(eventName, { bubbles: true, detail: {} }));
                    },
                }"
                @add-person.window="addPerson($event.detail.person_id)">
                
                <template x-for="participantID in participant_ids">
                    <div>
                        <x-link x-bind:href="'/people/'+participantID" x-text="participants[participantID].person.full_name "></x-link>
                        <span class="text-gray-700 mx-2">
                            {{ __('representing') }}
                        </span>
                        <template x-for="(org, orgIndex, orgs) in participants[participantID].orgs">
                            <span>
                                <x-link x-bind:href="'/orgs/'+org.id" x-text="org.name"></x-link>
                                <button type="button" @click="removeOrg(participantID, org.id)">X</button>
                                <span class="text-gray-500 mx-1" x-show="orgIndex < orgs.length - 1">|</span>
                            </span>
                        </template>
                        <template x-if="!participants[participantID].orgs.length">
                            <span class="text-gray-700">[{{ __('None') }}]</span>
                        </template>
                        <button type="button" class="ml-4 text-purple-700" x-on:click="showAddOrg(participantID)">+ Add</button>
                    </div>
                </template>
                
            </div>
            
            <form x-data="{
                    show: true,
                    participant: null,
                    org_id: null,
                    addOrg() {
                        axios.post('{{ route('meeting_participants.api_add_org') }}', {
                            participant_id: participant.id,
                            org_id: org_id,
                        })
                        .then(response => {
                            var success = _.get(response, 'data.success', false);
                            if (success !== true) {
                                var msg = _.get(response, 'data.message', '{{ __('An unknown error occurred.') }}');
                                alert(msg);
                                return false;
                            }
                            {{-- var participant = response.data.participant;
                            participant.person = response.data.person;
                            this.participants[participant.id] = participant;
                            this.participant_ids.push(participant.id);
                            this.dispatchEvent('person-added'); --}}
                        })
                        .catch(error => {
                            alert(getReadableAxiosError(error));
                        })
                        .finally(() => {
                            {{-- this.dispatchEvent('person-add-finished'); --}}
                        });
                    },
                }"
                class="mt-8 max-w-md"
                @add-org-show.window="participant = $event.detail.participant"
                @set-org.window="org_id = $event.detail.current_value"
                x-on:submit.prevent="addOrg()"
            >
                <label x-text="'{{ __('Add org to ') }}' + (participant ? participant.person.full_name : '[{{ __('none selected') }}]')" class="block font-medium text-sm text-gray-700"></label>
                <div class="mt-1 flex">
                    <div class="min-w-0 w-full max-w-lg mr-2">
                        <x-suggest-input id="org_id"
                            name="org_id"
                            :init-value="old('org_id')"
                            :options-url="route('orgs.api_search')"
                            placeholder-text="{{ __('Search') }}"
                            :as-select="true"
                            label-attr="name"
                            dispatch-event-name="set-org" />
                    </div>
                    <x-button type="submit" x-bind:disabled="participant && org_id">Add</x-button>
                </div>
            </form>
            
        </div>
    </div>
    
</x-app-layout>
