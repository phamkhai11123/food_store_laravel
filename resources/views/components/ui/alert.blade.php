@props(['type' => 'info', 'message' => '', 'title' => null, 'class' => ''])

@php
    $bgColor = match ($type) {
        'success' => 'bg-green-100 border-green-500 text-green-700',
        'error' => 'bg-red-100 border-red-500 text-red-700',
        'warning' => 'bg-yellow-100 border-yellow-500 text-yellow-700',
        default => 'bg-blue-100 border-blue-500 text-blue-700',
    };

    $icon = match ($type) {
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-exclamation-circle',
        'warning' => 'fas fa-exclamation-triangle',
        default => 'fas fa-info-circle',
    };
@endphp

<div {{ $attributes->merge(['class' => "border-l-4 p-4 mb-4 {$bgColor} {$class}"]) }} role="alert">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="{{ $icon }} text-xl"></i>
        </div>
        <div class="ml-3">
            @if($title)
                <h3 class="font-medium">{{ $title }}</h3>
            @endif
            <p class="{{ $title ? 'mt-1' : '' }}">
                {{ $message }}
                {{ $slot }}
            </p>
        </div>
    </div>
</div>
