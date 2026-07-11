@props(['name', 'show' => false, 'maxWidth' => 'md'])

@php $id = $name ?? \Illuminate\Support\Str::random(8); @endphp

<div x-data="{ show: @js($show) }" x-on:open-modal.window="show = ($event.detail === '{{ $id }}')"
    x-on:close-modal.window="show = false" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false"
    x-show="show" class="modal-overlay" style="display: none;" x-cloak>
    <div class="modal" @click.outside="show = false">
        {{ $slot }}
    </div>
</div>
