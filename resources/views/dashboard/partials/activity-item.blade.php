<div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-100 hover:border-red-100 hover:shadow-md transition-all group relative">
    <a href="{{ $activity['url'] }}" class="flex items-center gap-4 flex-1">
        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-xl grayscale group-hover:grayscale-0 transition-all">
            {{ $activity['icon'] }}
        </div>
        <div>
            <h4 class="text-sm font-bold text-slate-800">{{ $activity['title'] }}</h4>
            <p class="text-xs text-slate-400 font-medium">ID: {{ $activity['id'] }} • {{ $activity['date']->translatedFormat('d M') }}</p>
        </div>
    </a>
    
    <div class="flex items-center gap-3">
        @if($activity['download_url'])
        <a href="{{ $activity['download_url'] }}" target="_blank" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Cetak Tiket">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
        </a>
        @endif

        <span class="status-badge 
            @if($activity['status'] == 'paid' || $activity['status'] == 'settlement') status-paid 
            @elseif($activity['status'] == 'refunded') bg-blue-100 text-blue-600
            @elseif($activity['status'] == 'pending' || $activity['status'] == 'unpaid') status-pending 
            @elseif($activity['status'] == 'cancelled') status-cancelled 
            @else status-expired @endif">
            @if($activity['status'] == 'paid' || $activity['status'] == 'settlement') Telah Terbayar 
            @elseif($activity['status'] == 'refunded') Refunded
            @elseif($activity['status'] == 'pending' || $activity['status'] == 'unpaid') Menunggu 
            @elseif($activity['status'] == 'cancelled') Dibatalkan 
            @else Kadaluarsa @endif
        </span>
    </div>
</div>
