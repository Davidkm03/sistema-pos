@php
    $businessSettings = \App\Models\BusinessSetting::first();
    $businessName = $businessSettings->business_name ?? config('app.name', 'Sistema POS');
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-center']) }}>
    @if($businessSettings && $businessSettings->logo_path)
        <img src="{{ asset('storage/' . $businessSettings->logo_path) }}" 
             alt="{{ $businessName }}" 
             class="w-full h-full object-contain">
    @else
        {{-- Logo por defecto cuando no hay logo configurado --}}
        <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg">
            <span class="text-white font-bold text-2xl">
                {{ substr($businessName, 0, 3) }}
            </span>
        </div>
    @endif
</div>
