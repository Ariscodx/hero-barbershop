@props([
    'headers' => [],
])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full text-left border-collapse']) }}>
        @if(count($headers) > 0)
        <thead>
            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                @foreach($headers as $header)
                <th class="px-6 py-4 font-medium">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        @endif
        
        <tbody class="divide-y divide-gray-100 text-sm">
            {{ $slot }}
        </tbody>
    </table>
</div>
