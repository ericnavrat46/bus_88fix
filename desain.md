# 🎨 Panduan Desain & Branding Bus 88
**"Premium, Trustworthy, & Modern Indonesian Transport System"**

Dokumen ini berfungsi sebagai panduan untuk menjaga konsistensi visual agar aplikasi tidak terlihat seperti "AI-generated" dan memiliki identitas brand yang kuat serta profesional.

---

## 1. Filosofi Desain: "Merah Putih Premium"
Kita tidak hanya membangun web pemesanan bus, tapi membangun **kepercayaan**.
*   **Professional**: Bersih, presisi, dan fungsional.
*   **Modern**: Menggunakan tren UI terbaru (Glassmorphism, Soft Shadows, Micro-interactions).
*   **Local Pride**: Mengadaptasi warna bendera Indonesia (Merah & Putih) dengan palet yang lebih elegan (bukan merah terang standar).

---

## 2. Sistem Warna (Color Palette)
Semua warna didefinisikan di `resources/css/app.css` sebagai CSS custom properties.

| Kategori | Class Tailwind | Kode HEX | Penggunaan |
| :--- | :--- | :--- | :--- |
| **Primary** | `merah-600` | `#cc0000` | Tombol utama, aksi krusial, brand mark. |
| **Secondary** | `merah-500` | `#ff2d2d` | Hover states, aksen gradasi. |
| **Background** | `cream` | `#fef9f6` | Background utama (kesan hangat & premium). |
| **Surface** | `putih` | `#ffffff` | Card, Modals, Input fields. |
| **Text** | `dark` | `#1a0505` | Teks utama (bukan hitam pekat, nyaman di mata). |
| **Neutral** | `gray-warm-*` | `#9a8577` | Border, placeholder, teks sekunder. |

> ⚠️ **DILARANG** menggunakan hex color secara langsung di file `.blade.php`. Selalu gunakan class Tailwind yang merujuk ke palet ini.

---

## 3. Tipografi (Typography)
Font: **'Inter'** (Google Fonts) — sudah di-load di `layouts/app.blade.php`.

| Elemen | Class | Contoh |
| :--- | :--- | :--- |
| Hero Heading | `text-4xl lg:text-6xl font-black leading-tight` | "Perjalanan Aman & Nyaman" |
| Section Heading | `text-3xl lg:text-4xl font-black tracking-tight` | "Mengapa Bus 88?" |
| Card Title | `text-xl font-bold` | "Aman & Terpercaya" |
| Body Text | `text-base leading-relaxed` | Deskripsi panjang |
| Label | `text-xs font-semibold uppercase tracking-wider` | "KODE PROMO", "TRANSAKSI" |
| Small Note | `text-[11px] text-gray-warm-400` | Tanggal, keterangan kecil |

> **Tips Anti-AI:** Jangan pakai terlalu banyak variasi ukuran. Konsisten dengan skala: `text-xs`, `text-sm`, `text-base`, `text-lg`, `text-xl`, `text-2xl`, `text-3xl`.

---

## 4. Komponen UI (Semua ada di `app.css`)

### A. Tombol (Buttons)
| Class | Kapan Digunakan |
| :--- | :--- |
| `.btn-primary` | Aksi utama: Bayar, Cari, Submit |
| `.btn-secondary` | Aksi alternatif: Batal, Kembali |
| `.btn-white` | Di atas background gelap/gradasi |
| `.btn-danger` | Hapus, Tolak |
| `.btn-success` | Setujui, Konfirmasi |
| `.btn-sm` | Modifier ukuran kecil |
| `.btn-cta` | Call to Action di section promosi |
| `.btn-hero-white` | Hero section, di atas gambar |
| `.btn-hero-ghost` | Hero section, style transparan |

### B. Cards & Elevasi
| Class | Kapan Digunakan |
| :--- | :--- |
| `.card` | List standar, container biasa |
| `.card-premium` | Paket unggulan, promo highlight |
| `.glass-card` | Overlay di atas gambar/hero |
| `.stat-card` | Dashboard admin statistik |
| `.table-container` | Wrapper tabel data |

### C. Forms
| Class | Elemen |
| :--- | :--- |
| `.input-field` | `<input>`, `<textarea>` |
| `.select-field` | `<select>` |
| `.label-field` | `<label>` |

### D. Badges
| Class | Warna |
| :--- | :--- |
| `.badge-success` | Hijau (paid, approved) |
| `.badge-warning` | Kuning (pending) |
| `.badge-danger` | Merah (cancelled, rejected) |
| `.badge-info` | Biru (info) |
| `.badge-gray` | Abu-abu (expired, default) |

### E. Animasi
| Class | Efek |
| :--- | :--- |
| `.animate-float` | Melayang naik-turun (dekoratif) |
| `.animate-pulse-glow` | Glow merah berkedip (CTA utama) |
| `.animate-slide-up` | Muncul dari bawah (page load) |
| `.animate-fade-in` | Fade in halus |
| `.animate-delay-100` s.d. `400` | Stagger delay untuk animasi berurutan |

### F. Interaktif (sudah di app.css)
| Class | Efek |
| :--- | :--- |
| `.feature-card` | Hover: naik + shadow + icon rotate |
| `.route-card` | Hover: naik + border merah + arrow geser |
| `.review-card` | Hover: naik + shadow + image zoom |
| `.hero-img` | Slow zoom effect di hero section |
| `.search-card` | Hover: deep shadow + naik |

---

## 5. Struktur Halaman

### Landing Page (`home.blade.php`)
```
Hero (foto bus asli + overlay gradasi + search card)
  → Features Section (3 kolom .card-premium)
  → Rute Populer (grid .route-card)
  → Banner Promo (Swiper slider)
  → Testimoni Pelanggan (.review-card)
  → CTA Sewa Bus (.gradient-merah-dark)
```

### Admin Dashboard (`admin/dashboard.blade.php`)
```
Welcome Header (nama user + tanggal)
  → Stats Grid (4 .stat-card dengan hover icon)
  → Tables Grid (Booking Terbaru + Sewa Terbaru)
  → Quick Stats (Bus Aktif, Rute, Payment Gateway)
```

---

## 6. Strategi "Anti-AI Look"

| Masalah Umum Web AI | Solusi Bus 88 |
| :--- | :--- |
| Layout "Hero + 3 Columns" yang membosankan | Layout asimetris + white space yang lega |
| Inline CSS berantakan | Semua style di `app.css` sebagai komponen reusable |
| Icon generik & warna tidak nyambung | Heroicons stroke-2 konsisten, warna dari palet |
| Copywriting kaku | Bahasa solutif: "Temukan Perjalanan Nyamanmu" |
| Gambar stock palsu | Foto unit bus asli + destinasi Indonesia |
| Tidak ada Empty State | Ilustrasi + pesan membantu saat data kosong |
| Footer pakai inline style | Semua sudah Tailwind classes |
| Hover effect tidak ada | Setiap elemen interaktif punya transisi |

---

## 7. Aturan Coding Style

1. **Tidak ada inline `style=""` di blade files** — Gunakan class Tailwind atau komponen di `app.css`.
2. **Tidak ada hex color langsung** — Selalu referensi ke palet (`text-merah-600`, bukan `text-[#cc0000]`).
3. **Setiap blade baru** wajib `@extends('layouts.app')` atau `@extends('layouts.admin')`.
4. **Semua transition minimal `duration-200`** — Tidak boleh ada perubahan visual yang instan.
5. **Empty state wajib** — Jika ada `@forelse`, bagian `@empty` harus ada ilustrasi SVG + teks.

---

## 8. Checklist Sebelum Deploy
- [ ] Semua warna konsisten (tidak ada hex langsung di blade)
- [ ] Setiap elemen interaktif punya hover effect
- [ ] Teks kontras tinggi (dark di atas cream/putih)
- [ ] Mobile responsive (cek padding & font size)
- [ ] Loading state pakai warna brand
- [ ] Empty state ada ilustrasi
- [ ] Footer menggunakan Tailwind classes (bukan inline)
- [ ] Admin sidebar smooth transition

---

## 9. File yang Sudah Di-refactor

| File | Perubahan |
| :--- | :--- |
| `resources/css/app.css` | + Komponen: feature-card, route-card, review-card, hero-img, btn-hero-*, search-card, animate-delay-*, route-price |
| `resources/views/home.blade.php` | Hapus 130+ baris inline CSS, semua pindah ke app.css |
| `resources/views/admin/dashboard.blade.php` | Redesign: welcome header, hover stat cards, empty states, icon table headers |
| `resources/views/layouts/admin.blade.php` | Polish: gradient sidebar via, shadow, tracking-widest label, tanggal di topbar |
| `resources/views/partials/footer.blade.php` | Konversi 150+ inline style → Tailwind classes, responsive mobile |

---
*Dibuat untuk menjaga integritas visual Bus 88. Terakhir diperbarui: Mei 2026.*
