# 🏗️ Arsitektur Sistem — Bus 88

Dokumen ini menjelaskan arsitektur lengkap aplikasi **Bus 88**, sistem pemesanan tiket bus, sewa bus charter, dan paket wisata berbasis web.

---

## 1. Gambaran Umum (High-Level Overview)

```mermaid
graph TB
    subgraph CLIENT["🖥️ Client Layer"]
        BROWSER["Browser (Chrome/Safari)"]
        MOBILE["Mobile App (Future)"]
    end

    subgraph APP["⚙️ Application Layer (Laravel 12)"]
        direction TB
        ROUTES["Routes (web.php)"]
        MIDDLEWARE["Middleware (Auth, Admin)"]
        CONTROLLERS["Controllers"]
        MODELS["Eloquent Models"]
        VIEWS["Blade Views + Vite"]
        EVENTS["Events & Broadcasting"]
    end

    subgraph DATA["🗄️ Data Layer"]
        MYSQL["MySQL Database"]
        STORAGE["File Storage (public/storage)"]
    end

    subgraph EXTERNAL["🌐 External Services"]
        MIDTRANS["Midtrans Payment Gateway"]
        GOOGLE["Google OAuth 2.0"]
        REVERB["Laravel Reverb (WebSocket)"]
        MAIL["SMTP Mail Server"]
    end

    BROWSER --> ROUTES
    MOBILE --> ROUTES
    ROUTES --> MIDDLEWARE --> CONTROLLERS
    CONTROLLERS --> MODELS --> MYSQL
    CONTROLLERS --> VIEWS
    CONTROLLERS --> EVENTS --> REVERB --> BROWSER
    CONTROLLERS --> MIDTRANS
    CONTROLLERS --> GOOGLE
    CONTROLLERS --> MAIL
    CONTROLLERS --> STORAGE
```

---

## 2. Tech Stack

| Layer | Teknologi | Versi |
| :--- | :--- | :--- |
| **Backend** | Laravel (PHP) | 12.x |
| **Frontend** | Blade + Tailwind CSS + Alpine.js | TW 4.x |
| **Build Tool** | Vite | 7.x |
| **Database** | MySQL | 8.x |
| **Payment** | Midtrans Snap API | v2 |
| **Real-time** | Laravel Reverb (WebSocket) | 1.x |
| **Auth** | Laravel Auth + Google OAuth | - |
| **OTP** | Email-based OTP | - |
| **Hosting** | Laragon (Local) | - |

---

## 3. Entity Relationship Diagram (ERD)

```mermaid
erDiagram
    users {
        int id PK
        string name
        string email UK
        string password
        string role "admin | customer"
        string phone
        string address
        string avatar
        string google_id
        string otp
        datetime expired_otp
    }

    buses {
        int id PK
        string name
        string plate_number UK
        string type "medium | big | executive"
        int capacity
        string status "active | inactive"
        text facilities
    }

    routes {
        int id PK
        string origin
        string destination
        decimal base_price
        int distance
        string duration
        string status "active | inactive"
    }

    schedules {
        int id PK
        int bus_id FK
        int route_id FK
        date departure_date
        time departure_time
        time arrival_time
        decimal price
        int available_seats
        string status "active | inactive"
    }

    bookings {
        int id PK
        string booking_code UK
        int user_id FK
        int schedule_id FK
        int promo_banner_id FK
        int total_seats
        decimal total_price
        decimal discount_amount
        string payment_status
        string snap_token
        datetime paid_at
        datetime expired_at
    }

    booking_passengers {
        int id PK
        int booking_id FK
        string name
        string phone
        string seat_number
    }

    rentals {
        int id PK
        string rental_code UK
        int user_id FK
        int promo_banner_id FK
        string destination
        date departure_date
        date return_date
        int passengers
        decimal total_price
        decimal discount_amount
        string approval_status "pending | approved | rejected"
        string payment_status
        string snap_token
    }

    tour_packages {
        int id PK
        string name
        string slug UK
        text description
        decimal price
        int duration_days
        string destination
        text itinerary
        string image
        string status "active | inactive"
    }

    tour_bookings {
        int id PK
        string booking_code UK
        int user_id FK
        int tour_package_id FK
        int promo_banner_id FK
        int participants
        decimal total_price
        decimal discount_amount
        string payment_status
        string snap_token
        datetime paid_at
    }

    payments {
        int id PK
        string payable_type "polymorphic"
        int payable_id
        string midtrans_transaction_id
        string midtrans_order_id
        decimal amount
        string status "pending | settlement | expire"
        string payment_type
        string snap_token
        json raw_response
    }

    promo_banners {
        int id PK
        string title
        string promo_code UK
        string target_type "ticket | rental | tour"
        string discount_type "percentage | fixed"
        decimal discount_value
        int quota
        int used_quota
        date start_date
        date end_date
        string image
        boolean is_active
    }

    reviews {
        int id PK
        int user_id FK
        string reviewable_type "polymorphic"
        int reviewable_id
        int rating "1-5"
        text comment
        string image
        boolean is_visible
    }

    notifications {
        int id PK
        int user_id FK
        string title
        string message
        string type
        boolean is_read
    }

    users ||--o{ bookings : "has many"
    users ||--o{ rentals : "has many"
    users ||--o{ tour_bookings : "has many"
    users ||--o{ reviews : "writes"
    users ||--o{ notifications : "receives"
    buses ||--o{ schedules : "assigned to"
    routes ||--o{ schedules : "defines"
    schedules ||--o{ bookings : "booked on"
    bookings ||--o{ booking_passengers : "has"
    bookings ||--o{ payments : "polymorphic"
    rentals ||--o{ payments : "polymorphic"
    tour_bookings ||--o{ payments : "polymorphic"
    tour_packages ||--o{ tour_bookings : "booked"
    bookings ||--o{ reviews : "polymorphic"
    promo_banners ||--o{ bookings : "applied to"
    promo_banners ||--o{ rentals : "applied to"
    promo_banners ||--o{ tour_bookings : "applied to"
```

---

## 4. Arsitektur MVC (Model-View-Controller)

### Controllers

```mermaid
graph LR
    subgraph PUBLIC["🌍 Public"]
        HC["HomeController"]
        PPC["PublicPromoController"]
        PC["PageController"]
    end

    subgraph AUTH["🔐 Auth"]
        AC["AuthController"]
        SAC["SocialAuthController"]
        FPC["ForgotPasswordController"]
    end

    subgraph USER["👤 User (auth)"]
        DC["DashboardController"]
        BC["BookingController"]
        RC["RentalController"]
        TC["TourController"]
        PAY["PaymentController"]
        PROF["ProfileWebController"]
        REV["ReviewController"]
        TIC["TicketController"]
    end

    subgraph ADMIN["🛡️ Admin"]
        ADC["AdminDashboardController"]
        BUS["BusController"]
        RTC["RouteController"]
        SC["ScheduleController"]
        TPC["TourPackageController"]
        PBC["PromoBannerController"]
        TRC["TransactionController"]
        RPT["ReportController"]
    end
```

### Models & Polymorphic Relationships

```mermaid
graph TD
    Payment["💳 Payment (polymorphic)"]
    Review["⭐ Review (polymorphic)"]

    Booking --> Payment
    Rental --> Payment
    TourBooking --> Payment
    Booking --> Review

    User --> Booking
    User --> Rental
    User --> TourBooking
    User --> Notification

    Schedule --> Booking
    Bus --> Schedule
    Route --> Schedule
    TourPackage --> TourBooking

    PromoBanner -.->|"discount"| Booking
    PromoBanner -.->|"discount"| Rental
    PromoBanner -.->|"discount"| TourBooking
```

---

## 5. Alur Bisnis Utama

### A. Booking Tiket Bus

```mermaid
sequenceDiagram
    actor U as User
    participant W as Web (Blade)
    participant BC as BookingController
    participant MT as Midtrans
    participant RV as Reverb (WS)

    U->>W: Pilih rute & tanggal
    W->>BC: GET /search
    BC-->>W: Tampilkan jadwal
    U->>W: Pilih jadwal & kursi
    W->>BC: POST /booking/store
    BC->>MT: Create Snap Token
    MT-->>BC: snap_token
    BC-->>W: Redirect ke checkout
    U->>MT: Bayar via Midtrans Snap
    MT->>BC: POST /payment/notification (webhook)
    BC->>RV: broadcast(PaymentStatusUpdated)
    RV-->>W: Real-time update status
    W-->>U: "Pembayaran Berhasil!" + E-Ticket
```

### B. Sewa Bus (Charter)

```mermaid
sequenceDiagram
    actor U as User
    actor A as Admin
    participant RC as RentalController
    participant TC as TransactionController
    participant MT as Midtrans

    U->>RC: POST /charter (form sewa)
    RC-->>U: Status: Pending Approval
    A->>TC: Review sewa masuk
    A->>TC: POST /rental/{id}/approve + set harga
    TC-->>U: Notifikasi: Disetujui
    U->>RC: GET /charter/{id}/pay
    RC->>MT: Create Snap Token
    U->>MT: Bayar
    MT->>RC: Webhook → paid
```

### C. Booking Paket Wisata

```mermaid
sequenceDiagram
    actor U as User
    participant TC as TourController
    participant MT as Midtrans

    U->>TC: GET /tour (lihat paket)
    U->>TC: POST /tour/{slug}/book
    TC->>MT: Create Snap Token
    TC-->>U: Redirect checkout
    U->>MT: Bayar
    MT->>TC: Webhook → settlement
    TC-->>U: Konfirmasi + Tiket
```

---

## 6. Middleware & Security

```mermaid
graph LR
    REQ["HTTP Request"] --> WEB["web middleware"]
    WEB --> GUEST["guest middleware"]
    WEB --> AUTH["auth middleware"]
    AUTH --> CUSTOMER["CustomerMiddleware"]
    AUTH --> ADMIN["AdminMiddleware"]

    GUEST --> LOGIN["Login/Register"]
    CUSTOMER --> DASHBOARD["User Dashboard"]
    ADMIN --> ADMINPANEL["Admin Panel"]
```

| Middleware | File | Fungsi |
| :--- | :--- | :--- |
| `auth` | Laravel built-in | Memastikan user sudah login |
| `guest` | Laravel built-in | Hanya untuk user belum login |
| `AdminMiddleware` | `app/Http/Middleware/AdminMiddleware.php` | Cek `role === 'admin'` |
| `CustomerMiddleware` | `app/Http/Middleware/CustomerMiddleware.php` | Cek `role === 'customer'` |

---

## 7. Real-time Broadcasting

```mermaid
graph LR
    subgraph SERVER
        EVENT["PaymentStatusUpdated Event"]
        REVERB["Laravel Reverb Server"]
    end

    subgraph CLIENT
        ECHO["Laravel Echo (JS)"]
        UI["Update UI Otomatis"]
    end

    MIDTRANS["Midtrans Webhook"] --> EVENT
    EVENT -->|"broadcast"| REVERB
    REVERB -->|"WebSocket"| ECHO
    ECHO --> UI
```

**Channel:** `payment.{payment_id}`
**Event:** `.payment.updated`

---

## 8. Struktur Direktori

```
bus_88/
├── app/
│   ├── Events/                    # PaymentStatusUpdated, TestEvent
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/             # 9 controllers (CRUD admin)
│   │   │   ├── Api/Mobile/        # Future mobile API
│   │   │   ├── Auth/              # AuthController, SocialAuth, ForgotPassword
│   │   │   ├── BookingController  # Booking tiket bus
│   │   │   ├── PaymentController  # Midtrans webhook + finish
│   │   │   ├── RentalController   # Sewa bus charter
│   │   │   ├── TourController     # Paket wisata
│   │   │   └── ...
│   │   └── Middleware/            # AdminMiddleware, CustomerMiddleware
│   ├── Models/                    # 14 Eloquent models
│   └── Providers/
├── database/
│   └── migrations/                # 31 migration files
├── resources/
│   ├── css/app.css                # Design System (palet, komponen, 3D animations)
│   ├── js/app.js                  # Alpine.js + Laravel Echo
│   └── views/
│       ├── admin/                 # Dashboard, CRUD views
│       ├── layouts/               # app.blade.php, admin.blade.php
│       ├── partials/              # navbar, footer
│       └── ...                    # home, schedules, rental, tour, auth, tickets
├── routes/
│   └── web.php                    # ~210 baris, 50+ routes
├── public/
│   ├── build/                     # Vite compiled assets
│   └── images/                    # Static assets
└── config/
    └── broadcasting.php           # Reverb/Pusher config
```

---

## 9. Integrasi Pihak Ketiga

| Service | Kegunaan | Integrasi |
| :--- | :--- | :--- |
| **Midtrans** | Payment Gateway (VA, E-Wallet, CC) | Snap API + Server Notification Webhook |
| **Google OAuth** | Social Login | `SocialAuthController` via Socialite |
| **Laravel Reverb** | Real-time WebSocket | Broadcasting events via Echo |
| **SMTP** | Kirim OTP Reset Password | Laravel Mail |
| **SweetAlert2** | UI Notification/Dialog | CDN script |
| **Swiper.js** | Promo Banner Slider | CDN script |
| **ui-avatars.com** | Generated User Avatars | External API |

---

## 10. Ringkasan Statistik Kode

| Metrik | Jumlah |
| :--- | :--- |
| **Models** | 14 |
| **Controllers** | 24 (12 user + 9 admin + 3 auth) |
| **Migrations** | 31 |
| **Routes** | 50+ endpoints |
| **Blade Views** | 30+ files |
| **Events** | 2 (PaymentStatusUpdated, TestEvent) |
| **Middleware** | 2 custom (Admin, Customer) |

---
*Arsitektur Bus 88 — Dibuat Mei 2026*
