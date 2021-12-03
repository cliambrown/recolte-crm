@props([
    'action' => '',
    'confirmMsg' => __('Are you sure you want to delete this item?'),
    'buttonText' => __('Delete')
])

<form method="POST" action="{{ $action }}" {{ $attributes }} x-data="deleteForm({ confirm_msg: '{!! htmlspecialchars($confirmMsg, ENT_QUOTES) !!}' })" @submit.prevent="onSubmit" x-ref="form">
    @csrf
    @method('DELETE')
    {{ $slot }}
    <x-button btncolor="red">{{ $buttonText }}</x-button>
</form>