@props(['title', 'value', 'icon' => null])

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $value }}</h3>
        </div>
        @if($icon)
        <div class="p-3 bg-gray-50 rounded-lg text-accent">
            {{ $icon }}
        </div>
        @endif
    </div>
</div>
