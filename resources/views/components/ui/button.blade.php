<button
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "btn btn-{$variant} {$class}"]) }}
>
    {{ $slot }}
</button>
