@props(['title', 'value', 'icon' => null])

<div class="bg-brand-card rounded-xl shadow-sm border border-brand-border p-6 hover:-translate-y-1 hover:shadow-md active:scale-[0.98] transition-all duration-300 cursor-pointer">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-brand-text">{{ $value }}</h3>
        </div>
        @if($icon)
        <div class="p-4 bg-accent/10 rounded-full text-accent">
            {{ $icon }}
        </div>
        @endif
    </div>
</div>
