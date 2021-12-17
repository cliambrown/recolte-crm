<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Projects') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            
            <div class="sm:flex sm:justify-between">
                
                <div>
                    <x-button href="{{ route('projects.create') }}">
                        <x-icons.plus></x-icons.plus> {{ __('Add') }} {{ __('Project') }}
                    </x-button>
                </div>
                
                <form class="w-full sm:w-auto sm:max-w-xs flex-grow sm:flex my-3 sm:my-0" method="GET">
                    <div class="flex-grow">
                        <x-input type="text" placeholder="{{ __('Search') }}" name="q" aria-placeholder="{{ __('Search') }}" class="w-full flex-grow" value="{{ $q }}"></x-input>
                    </div>
                    <div class="ml-1">
                        <x-button type="submit">
                            <x-icons.search class="-my-1"></x-icons.search>
                        </x-button>
                    </div>
                </form>
                
            </div>
            
            @if ($q)
                <div class="my-4 text-gray-700 text-sm">
                    {{ __('Showing results for search') }} "{{ $q }}". <x-link href="{{ set_url_param(url()->current(), 'q', null) }}" class="ml-2">Clear search</x-link>
                </div>
            @endif
            
            @foreach ($projects as $project)
                
                <div class="shadow bg-white p-4 rounded my-4">
                    <div class="float-right ml-2 mb-2">
                        <x-button href="{{ route('projects.show', ['project' => $project->id]) }}" padding="tight" btncolor="blue" class="mr-2">
                            {{ __('View') }}
                        </x-button>
                        <x-button href="{{ route('projects.edit', ['project' => $project->id]) }}" padding="tight" btncolor="green">
                            {{ __('Edit') }}
                        </x-button>
                    </div>
                    <div class="font-bold text-gray-700">
                        {{ $project->name }}
                        @if ($project->short_name)
                            <span class="text-sm text-gray-500 font-normal mx-1">/</span>
                            {{ $project->short_name }}
                        @endif
                    </div>
                    <div class="text-sm text-gray-700 overflow-hidden mt-1">
                        @if ($project->description || $project->start_year || $project->end_year)
                            <div class="whitespace-nowrap text-ellipsis overflow-x-hidden">
                                @if ($project->start_year || $project->end_year)
                                    <span class="text-purple-600 mr-3">
                                        @if ($project->start_year)
                                            @if (!$project->end_year)
                                                <span class="text-gray-500 mr-1">{{ __('since') }}</span>
                                            @endif
                                            <span class="font-semibold mr-1">{{ $project->start_year }}</span>
                                        @endif
                                        @if ($project->end_year)
                                            @if ($project->start_year)
                                                <span class="text-gray-500 mr-1">{{ __('to') }}</span>
                                            @else
                                                <span class="text-gray-500 mr-1">{{ __('until') }}</span>
                                            @endif
                                            <span class="font-semibold">{{ $project->end_year }}</span>
                                        @endif
                                    </span>
                                @endif
                                @if ($project->description)
                                    {{ $project->description }}
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            
            @endforeach
            
            {{ $projects->links() }}
            
        </div>
    </div>
    
</x-app-layout>
