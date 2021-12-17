<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <span class="text-gray-500 mr-3 font-normal">
                {{ __('Project') }}
            </span>
            {{ $project->name }}
            @if ($project->short_name)
                <span class="text-gray-500 ml-3">
                    — {{ $project->short_name }}
                </span>
            @endif
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <div class="mb-4">
                <x-button href="{{ route('projects.edit', ['project' => $project->id]) }}" btncolor="blue" padding="tight" class="">
                    {{ __('Edit') }} {{ __('Info') }}
                </x-button>
            </div>
            
            <div class="text-purple-600 text-sm mb-1">
                @if ($project->start_date_str)
                    @if (!$project->end_date_str)
                        <span class="text-gray-500 mr-1">{{ __('since') }}</span>
                    @endif
                    <span class="font-semibold mr-1">{{ $project->start_date_str }}</span>
                @endif
                @if ($project->end_date_str)
                    @if ($project->start_date_str)
                        <span class="text-gray-500 mr-1">{{ __('to') }}</span>
                    @else
                        <span class="text-gray-500 mr-1">{{ __('until') }}</span>
                    @endif
                    <span class="font-semibold">{{ $project->end_date_str }}</span>
                @endif
                @if (!$project->start_date_str && !$project->end_date_str)
                    [{{ __('unknown dates') }}]
                @endif
            </div>
            
            @if ($project->description)
                <div class="my-2 flex">
                    <div class="mr-2">
                        <x-icons.info class="inline w-4 h-4 relative bottom-[1px] text-purple-500"></x-icons.info>
                    </div>
                    <div>
                        {{ $project->description }}
                    </div>
                </div>
            @endif
            
            @if ($project->notes)
                <x-expandable-notes :notes="$project->notes"></x-expandable-notes>
            @endif
            
            <div>
                <x-button href="{{ route('projects.orgs.create', ['project' => $project->id]) }}" padding="tight" class="mt-10">
                    <x-icons.plus></x-icons.plus> {{ __('Add Org') }}
                </x-button>
            </div>
            
            @if ($project->project_orgs->count())
                <h3 class="mt-8 my-4 text-lg text-gray-700">{{ __('Orgs') }}</h3>
            @endif
            
            @foreach ($project->project_orgs as $projectOrg)
                <div class="my-4">
                    <div class="text-purple-600 text-sm mb-1">
                        @if ($projectOrg->start_date_str)
                            @if (!$projectOrg->end_date_str)
                                <span class="text-gray-500 mr-1">{{ __('since') }}</span>
                            @endif
                            <span class="font-semibold mr-1">{{ $projectOrg->start_date_str }}</span>
                        @endif
                        @if ($projectOrg->end_date_str)
                            @if ($projectOrg->start_date_str)
                                <span class="text-gray-500 mr-1">{{ __('to') }}</span>
                            @else
                                <span class="text-gray-500 mr-1">{{ __('until') }}</span>
                            @endif
                            <span class="font-semibold">{{ $projectOrg->end_date_str }}</span>
                        @endif
                        @if (!$projectOrg->start_date_str && !$projectOrg->end_date_str)
                            [{{ __('unknown dates') }}]
                        @endif
                    </div>
                    <div class="mb-1">
                        <x-link href="{{ route('orgs.show', ['org' => $projectOrg->org->id]) }}">
                            {{ $projectOrg->org->name }}
                        </x-link>
                        @if ($projectOrg->role)
                            —
                            <span class="font-semibold">
                                {{ $projectOrg->role }}
                            </span>
                        @endif
                        <x-button href="{{ route('projects.orgs.edit', ['project' => $project->id, 'project_org' => $projectOrg->id]) }}" class="ml-4" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    @if ($projectOrg->notes)
                        <x-expandable-notes :notes="$projectOrg->notes"></x-expandable-notes>
                    @endif
                </div>
            @endforeach
            
            <div>
                <x-button href="{{ route('projects.people.create', ['project' => $project->id]) }}" padding="tight" class="mt-10">
                    <x-icons.plus></x-icons.plus> {{ __('Add Person') }}
                </x-button>
            </div>
            
            @if ($project->project_people->count())
                <h3 class="mt-8 my-4 text-lg text-gray-700">{{ __('People') }}</h3>
            @endif
            
            @foreach ($project->project_people as $projectPerson)
                <div class="my-4">
                    <div class="text-purple-600 text-sm mb-1">
                        @if ($projectPerson->start_date_str)
                            @if (!$projectPerson->end_date_str)
                                <span class="text-gray-500 mr-1">{{ __('since') }}</span>
                            @endif
                            <span class="font-semibold mr-1">{{ $projectPerson->start_date_str }}</span>
                        @endif
                        @if ($projectPerson->end_date_str)
                            @if ($projectPerson->start_date_str)
                                <span class="text-gray-500 mr-1">{{ __('to') }}</span>
                            @else
                                <span class="text-gray-500 mr-1">{{ __('until') }}</span>
                            @endif
                            <span class="font-semibold">{{ $projectPerson->end_date_str }}</span>
                        @endif
                        @if (!$projectPerson->start_date_str && !$projectPerson->end_date_str)
                            [{{ __('unknown dates') }}]
                        @endif
                    </div>
                    <div class="mb-1">
                        <x-link href="{{ route('people.show', ['person' => $projectPerson->person->id]) }}">
                            {{ $projectPerson->person->full_name }}
                        </x-link>
                        @if ($projectPerson->role)
                            —
                            <span class="font-semibold">
                                {{ $projectPerson->role }}
                            </span>
                        @endif
                        <x-button href="{{ route('projects.people.edit', ['project' => $project->id, 'project_person' => $projectPerson->id]) }}" class="ml-4" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    @if ($projectPerson->notes)
                        <x-expandable-notes :notes="$projectPerson->notes"></x-expandable-notes>
                    @endif
                </div>
            @endforeach
            
        </div>
    </div>
    
</x-app-layout>
