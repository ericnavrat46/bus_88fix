@php
    $config = match($status) {
        'paid', 'settlement', 'capture', 'success' => [
            'class' => 'bg-emerald-100 text-emerald-700',
            'label' => '✓ Lunas',
            'icon'  => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
        ],
        'pending', 'unpaid' => [
            'class' => 'bg-amber-100 text-amber-700',
            'label' => '⏳ Menunggu',
            'icon'  => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ],
        'expired' => [
            'class' => 'bg-gray-100 text-gray-600',
            'label' => '⏱ Kadaluarsa',
            'icon'  => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ],
        'cancelled', 'cancel', 'deny' => [
            'class' => 'bg-red-100 text-red-700',
            'label' => '✗ Dibatalkan',
            'icon'  => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
        ],
        'refunded' => [
            'class' => 'bg-blue-100 text-blue-700',
            'label' => '↩ Refund',
            'icon'  => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>'
        ],
        default => [
            'class' => 'bg-gray-100 text-gray-600',
            'label' => ucfirst($status),
            'icon'  => ''
        ],
    };
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $config['class'] }}">
    {!! $config['icon'] !!}
    {{ $config['label'] }}
</span>
