@props([
    'title' => null,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative flex flex-col h-full']) }}>
    
    @if($title || isset($icon))
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
        @if($title)
        <h3 class="text-lg font-semibold text-gray-900 font-poppins">{{ $title }}</h3>
        @endif
        
        @if(isset($icon))
        <div class="text-accent">
            {{ $icon }}
        </div>
        @endif
    </div>
    @endif
    
    <div class="p-6 flex-1">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $footer }}
    </div>
    @endif
</div>
