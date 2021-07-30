<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orgs') }}
        </h2>
    </x-slot>
    
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    
    <!-- Validation Errors -->
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <x-button href="{{ route('orgs.create') }}">
                <x-icons.plus></x-icons.plus> {{ __('Add') }} {{ __('Org') }}
            </x-button>
            
            <table class="mt-6">
                <thead>
                    <tr>
                        <th></th>
                        <th class="text-left">{{ __('Name') }}</th>
                        <th class="text-left">{{ __('Address') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orgs as $org)
                        <tr>
                            <td class="align-text-top p-1">
                                <x-link href="{{ route('orgs.edit', ['org' => $org->id]) }}">edit</x-link>
                            </td>
                            <td class="align-text-top p-1">
                                {{ $org->name }}
                            </td>
                            <td class="align-text-top p-1">
                                @if ($org->street_address)
                                    <div>{{ $org->street_address }}</div>
                                @endif
                                @if ($org->street_address_2)
                                    <div>{{ $org->street_address_2 }}</div>
                                @endif
                                @if ($org->city || $org->province || $org->postal_code)
                                    <div>
                                        {{ $org->city }}
                                        {{ $org->province }}
                                        {{ $org->postal_code }}
                                    </div>
                                @endif
                            </td>
                            <td class="align-text-top p-1">
                                @if ($org->website)
                                    <div>
                                        <x-link :href="$org->website">
                                            {{ get_domain($org->website) }}
                                        </x-link>
                                    </div>
                                @endif
                                @if ($org->email)
                                    <div>
                                        <x-link href="mailto:{{ $org->email }}">
                                            {{ $org->email }}
                                        </x-link>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{ $orgs->links() }}
            
        </div>
    </div>
    
</x-app-layout>
