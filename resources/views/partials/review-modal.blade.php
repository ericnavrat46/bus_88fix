{{-- Review Modal --}}
<div id="reviewModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" x-data="{ rating: 5 }">
    <div class="flex items-center justify-center min-h-screen p-4 bg-dark/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-black text-dark">Beri Ulasan</h3>
                <button onclick="closeReviewModal()" class="text-gray-warm-400 hover:text-dark transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('review.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="reviewable_type" id="modal_reviewable_type">
                <input type="hidden" name="reviewable_id" id="modal_reviewable_id">

                {{-- Star Rating --}}
                <div class="text-center">
                    <p class="text-sm text-gray-warm-500 mb-3 font-medium">Bagaimana pengalaman perjalanan Anda?</p>
                    <div class="flex justify-center gap-2">
                        <template x-for="i in 5">
                            <button type="button" @click="rating = i" class="transition-transform active:scale-95 focus:outline-none">
                                <svg class="w-10 h-10" :class="i <= rating ? 'text-amber-400 fill-current' : 'text-gray-200'" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="rating" :value="rating">
                </div>

                {{-- Comment --}}
                <div>
                    <label class="label-field text-xs uppercase tracking-wider text-gray-warm-400">Pesan / Kesan</label>
                    <textarea name="comment" rows="4" class="input-field" placeholder="Ceritakan pengalaman Anda liburan bersama Bus 88..."></textarea>
                </div>

                {{-- Photo Upload --}}
                <div>
                    <label class="label-field text-xs uppercase tracking-wider text-gray-warm-400">Foto (Opsional)</label>
                    <input type="file" name="image" class="file:btn-secondary file:btn-xs text-xs" accept="image/*">
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn-primary w-full py-4 text-lg font-bold shadow-lg shadow-merah-600/20">KIRIM ULASAN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openReviewModal(type, id) {
        document.getElementById('modal_reviewable_type').value = type;
        document.getElementById('modal_reviewable_id').value = id;
        document.getElementById('reviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
        document.getElementById('reviewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
