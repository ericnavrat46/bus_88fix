<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoBanner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class PromoBannerController extends Controller
{
    public function index(Request $request)
    {
        $query = PromoBanner::query();

        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true)->where('end_date', '>=', now());
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', now());
            }
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('promo_code', 'like', '%' . $request->search . '%');
            });
        }

        $sortBy  = $request->get('sort', 'sort_order');
        $sortDir = $request->get('dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $banners = $query->paginate(10);

        return view('admin.promo-banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.promo-banners.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image'           => 'required|image|mimes:jpg,png,webp|max:2048',
            'title'           => 'required|string|max:100',
            'description'     => 'nullable|string|max:200',
            'promo_code'      => 'required|string|alpha_num|max:20',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'link'            => 'nullable|url',
            'is_active'       => 'nullable|boolean',
            'sort_order'      => 'required|integer|min:0',
            'target_type'     => 'required|in:all,ticket,rental,tour',
            'discount_type'   => 'required|in:percent,fixed',
            'discount_value'  => 'nullable|numeric|min:0',
            'min_transaction' => 'nullable|numeric|min:0',
            'max_discount'    => 'nullable|numeric|min:0',
            'quota'           => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('promo_banners', $filename, 'public');
            $validated['image'] = $filename;
        }

        $validated['promo_code'] = strtoupper($validated['promo_code']);
        $validated['is_active']  = $request->has('is_active');

        $promo = PromoBanner::create($validated);
        if ($promo->is_active) {
            $this->broadcastPromoNotif($promo);
        }

        return redirect()->route('admin.promo-banners.index')
            ->with('success', 'Banner promo berhasil ditambahkan.');
    }

    public function edit(PromoBanner $promoBanner)
    {
        return view('admin.promo-banners.form', ['banner' => $promoBanner]);
    }

    public function update(Request $request, PromoBanner $promoBanner)
    {
        $validated = $request->validate([
            'image'           => 'nullable|image|mimes:jpg,png,webp|max:2048',
            'title'           => 'required|string|max:100',
            'description'     => 'nullable|string|max:200',
            'promo_code'      => 'required|string|alpha_num|max:20',
            'start_date'      => 'required|date',
            'end_date'        => 'required|date|after_or_equal:start_date',
            'link'            => 'nullable|url',
            'is_active'       => 'nullable|boolean',
            'sort_order'      => 'required|integer|min:0',
            'target_type'     => 'required|in:all,ticket,rental,tour',
            'discount_type'   => 'required|in:percent,fixed',
            'discount_value'  => 'nullable|numeric|min:0',
            'min_transaction' => 'nullable|numeric|min:0',
            'max_discount'    => 'nullable|numeric|min:0',
            'quota'           => 'required|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            if ($promoBanner->image) {
                Storage::disk('public')->delete('promo_banners/' . $promoBanner->image);
            }
            $filename = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->storeAs('promo_banners', $filename, 'public');
            $validated['image'] = $filename;
        }

        $validated['promo_code'] = strtoupper($validated['promo_code']);

        $baruDiaktifkan = !$promoBanner->is_active && $request->has('is_active');

        $validated['is_active'] = $request->has('is_active');
        $promoBanner->update($validated);
        if ($baruDiaktifkan) {
            $this->broadcastPromoNotif($promoBanner->fresh());
        }

        return redirect()->route('admin.promo-banners.index')
            ->with('success', 'Banner promo berhasil diperbarui.');
    }

    public function destroy(PromoBanner $promoBanner)
    {
        if ($promoBanner->image) {
            Storage::disk('public')->delete('promo_banners/' . $promoBanner->image);
        }
        $promoBanner->delete();

        return redirect()->route('admin.promo-banners.index')
            ->with('success', 'Banner promo berhasil dihapus.');
    }

    public function toggleStatus(PromoBanner $promoBanner)
    {
        $wasInactive = !$promoBanner->is_active;

        $promoBanner->update(['is_active' => !$promoBanner->is_active]);
        if ($wasInactive && $promoBanner->is_active) {
            $this->broadcastPromoNotif($promoBanner);
        }

        return response()->json(['success' => true, 'is_active' => $promoBanner->is_active]);
    }
    private function broadcastPromoNotif(PromoBanner $promo): void
{
    $users = User::where('role', 'customer')
        ->whereNotNull('fcm_token')
        ->get();

    \Log::info('=== BROADCAST PROMO ===', [
        'promo'       => $promo->title,
        'is_active'   => $promo->is_active,
        'total_users' => $users->count(),
    ]);

    if ($users->isEmpty()) {
        \Log::warning('TIDAK ADA USER DENGAN FCM TOKEN');
        return;
    }

    $discountLabel = $promo->discount_type === 'percentage'
        ? "{$promo->discount_value}% OFF"
        : 'Diskon Rp ' . number_format($promo->discount_value, 0, ',', '.');

    $title = "🎉 Promo Baru: {$discountLabel}!";
    $body  = "{$promo->title} · Kode: {$promo->promo_code}";

    foreach ($users as $user) {
        \App\Helpers\NotificationHelper::send(
            $user->id, $title, $body, 'new_promo',
            ['promo_id' => (string) $promo->id]
        );
    }

    $tokens = $users->pluck('fcm_token')->toArray();
    foreach (array_chunk($tokens, 500) as $chunk) {
        $this->sendFcm($chunk, $title, $body, $promo->id);
    }
}

private function sendFcm(array $tokens, string $title, string $body, int $promoId): void
{
    $projectId   = config('services.firebase.project_id');
    $accessToken = $this->getFirebaseAccessToken();

    \Log::info('KIRIM FCM', [
        'project_id'   => $projectId,
        'total_tokens' => count($tokens),
        'access_token' => substr($accessToken, 0, 20) . '...',
    ]);

    foreach ($tokens as $token) {
        $response = Http::withToken($accessToken)
            ->post(
                "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send",
                [
                    'message' => [
                        'token'        => $token,
                        'notification' => [
                            'title' => $title,
                            'body'  => $body,
                        ],
                        'data' => [
                            'type'     => 'new_promo',
                            'promo_id' => (string) $promoId,
                        ],
                        'android' => [
                            'notification' => [
                                'channel_id' => 'high_importance_channel',
                                'sound'      => 'default',
                            ],
                        ],
                    ],
                ]
            );

        // ← LOG RESPONSE FCM
        \Log::info('FCM RESPONSE', [
            'status' => $response->status(),
            'body'   => $response->json(),
        ]);
    }
}

    private function getFirebaseAccessToken(): string
    {
        $path           = storage_path('app/firebase-service-account.json');
        $serviceAccount = json_decode(file_get_contents($path), true);

        $now = time();

        $header  = rtrim(strtr(base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])), '+/', '-_'), '=');
        $payload = rtrim(strtr(base64_encode(json_encode([
            'iss'   => $serviceAccount['client_email'],
            'sub'   => $serviceAccount['client_email'],
            'aud'   => 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        ])), '+/', '-_'), '=');

        openssl_sign("{$header}.{$payload}", $sig, $serviceAccount['private_key'], 'SHA256');

        $jwt = "{$header}.{$payload}." . rtrim(strtr(base64_encode($sig), '+/', '-_'), '=');

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => $jwt,
        ]);

        return $response->json('access_token');
    }
}