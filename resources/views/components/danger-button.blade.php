<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-rose']) }}>
    {{ $slot }}
</button>
