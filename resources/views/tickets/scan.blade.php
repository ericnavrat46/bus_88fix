@extends('layouts.admin')
@section('title', 'Scan E-Ticket — Bus 88')

@push('styles')
<style>
.scan-page { max-width: 560px; margin: 32px auto; padding: 0 16px; }

/* ── PAGE TITLE ── */
.page-title   { font-size: 24px; font-weight: 900; color: #1a1a1a; letter-spacing: -0.5px; }
.page-subtitle{ font-size: 13px; color: #757575; margin-top: 4px; }

/* ── CARDS ── */
.scan-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,0.08);
    margin-bottom: 16px;
}

.scan-card-head {
    padding: 14px 20px;
    font-size: 14px;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 8px;
}
.scan-card-head.red    { background: linear-gradient(135deg, #cc0000, #ff4444); }
.scan-card-head.dark   { background: #1a1a1a; }
.scan-card-head.green  { background: #2e7d32; }
.scan-card-head.danger { background: #b71c1c; }

.scan-card-body { padding: 20px; }

/* ── CAMERA ── */
.camera-box {
    position: relative;
    background: #000;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 14px;
    min-height: 280px;
}

#qr-video {
    width: 100%;
    display: block;
    border-radius: 10px;
}

.camera-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.scan-frame {
    width: 200px;
    height: 200px;
    border: 2.5px solid rgba(255,255,255,0.8);
    border-radius: 12px;
    box-shadow: 0 0 0 9999px rgba(0,0,0,0.45);
    position: relative;
}

/* corner accents */
.sf-tl, .sf-tr, .sf-bl, .sf-br {
    position: absolute;
    width: 20px; height: 20px;
}
.sf-tl { top:-2px; left:-2px;  border-top:4px solid #cc0000; border-left:4px solid #cc0000;  border-radius:4px 0 0 0; }
.sf-tr { top:-2px; right:-2px; border-top:4px solid #cc0000; border-right:4px solid #cc0000; border-radius:0 4px 0 0; }
.sf-bl { bottom:-2px; left:-2px;  border-bottom:4px solid #cc0000; border-left:4px solid #cc0000;  border-radius:0 0 0 4px; }
.sf-br { bottom:-2px; right:-2px; border-bottom:4px solid #cc0000; border-right:4px solid #cc0000; border-radius:0 0 4px 0; }

.scan-line {
    position: absolute; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, transparent, #cc0000, transparent);
    top: 0;
    animation: scanAnim 2s linear infinite;
}

@keyframes scanAnim {
    0%   { top: 0; opacity: 1; }
    90%  { top: calc(100% - 2px); opacity: 1; }
    100% { top: calc(100% - 2px); opacity: 0; }
}

.camera-placeholder {
    position: absolute; inset: 0;
    background: #111;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    border-radius: 10px;
    color: #fff;
}
.camera-placeholder-icon { font-size: 40px; margin-bottom: 8px; }
.camera-placeholder-text { font-size: 12px; color: #888; }

/* ── BUTTONS ── */
.btn-row { display: flex; gap: 10px; }

.btn {
    flex: 1; padding: 11px 16px;
    border: none; border-radius: 10px;
    font-size: 13px; font-weight: 700;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    transition: all 0.2s;
    text-decoration: none;
}
.btn:hover { transform: translateY(-1px); }

.btn-red    { background: linear-gradient(135deg, #cc0000, #ff4444); color: #fff; }
.btn-outline{ background: #fff; color: #cc0000; border: 2px solid #cc0000; }
.btn-dark   { background: #1a1a1a; color: #fff; }

/* ── MANUAL INPUT ── */
.input-row { display: flex; gap: 10px; margin-bottom: 12px; }
.manual-input {
    flex: 1; padding: 11px 14px;
    border: 1.5px solid #e0e0e0; border-radius: 10px;
    font-size: 13px; text-transform: uppercase; letter-spacing: 1px;
    font-family: 'Courier New', monospace;
    outline: none;
}
.manual-input:focus { border-color: #cc0000; box-shadow: 0 0 0 3px rgba(204,0,0,0.1); }

.type-chips { display: flex; gap: 8px; flex-wrap: wrap; }
.chip {
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 11px; font-weight: 700;
    cursor: pointer;
    border: 1.5px solid #e0e0e0;
    background: #fff;
    color: #666;
    transition: all 0.2s;
}
.chip.active { background: #cc0000; color: #fff; border-color: #cc0000; }

/* ── RESULT CARD ── */
#result-card { display: none; }

.result-status-head {
    padding: 20px;
    text-align: center;
}
.result-status-icon {
    width: 56px; height: 56px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px;
    margin: 0 auto 10px;
}
.result-status-icon.ok  { background: #e8f5e9; color: #2e7d32; }
.result-status-icon.err { background: #ffebee; color: #b71c1c; }

.result-title {
    font-size: 17px; font-weight: 900;
    margin-bottom: 3px;
}
.result-title.ok  { color: #2e7d32; }
.result-title.err { color: #b71c1c; }

.result-subtitle { font-size: 12px; color: #666; }

.today-badge     { display:block; background:#e8f5e9; color:#2e7d32; border:1.5px solid #66bb6a; border-radius:8px; padding:8px 14px; font-size:11px; font-weight:700; text-align:center; margin:0 20px 12px; }
.not-today-badge { display:block; background:#fff8e1; color:#e65100; border:1.5px solid #ffc107; border-radius:8px; padding:8px 14px; font-size:11px; font-weight:700; text-align:center; margin:0 20px 12px; }

.result-table { padding: 0 20px; margin-bottom: 16px; }
.result-table-inner { border: 1px solid #f0f0f0; border-radius: 10px; overflow: hidden; }
.result-table-head  { background: #f8f9fa; padding: 9px 14px; font-size: 10px; font-weight: 700; color: #888; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid #f0f0f0; }

.result-row { display:flex; padding:9px 14px; border-bottom:1px solid #f8f8f8; gap:8px; }
.result-row:last-child { border-bottom:none; }
.result-row-label { color:#888; font-size:11px; width:44%; flex-shrink:0; }
.result-row-value { font-weight:700; font-size:12px; color:#1a1a1a; flex:1; word-break:break-word; }
.value-paid { background:#e8f5e9; color:#2e7d32; border-radius:4px; padding:2px 8px; font-size:10px; font-weight:700; display:inline-block; }

.result-actions { padding: 0 20px 20px; display:flex; gap:10px; }
</style>
@endpush

@section('content')
<div class="scan-page">

    <div style="margin-bottom:24px;">
        <h1 class="page-title">&#128269; Scan E-Ticket</h1>
        <p class="page-subtitle">Verifikasi tiket penumpang via kamera atau input manual</p>
    </div>

    {{-- ── CAMERA SCANNER ── --}}
    <div class="scan-card">
        <div class="scan-card-head red">&#128247; Scan via Kamera</div>
        <div class="scan-card-body">
            <div class="camera-box">
                <video id="qr-video" playsinline></video>
                <div class="camera-overlay">
                    <div class="scan-frame">
                        <div class="sf-tl"></div><div class="sf-tr"></div>
                        <div class="sf-bl"></div><div class="sf-br"></div>
                        <div class="scan-line"></div>
                    </div>
                </div>
                <div class="camera-placeholder" id="camera-placeholder">
                    <div class="camera-placeholder-icon">&#128247;</div>
                    <div class="camera-placeholder-text">Kamera belum aktif</div>
                </div>
            </div>
            <div class="btn-row">
                <button id="btn-start" class="btn btn-red" onclick="startCamera()">&#9654; Aktifkan Kamera</button>
                <button id="btn-stop"  class="btn btn-outline" onclick="stopCamera()" style="display:none;">&#9632; Stop Kamera</button>
            </div>
        </div>
    </div>

    {{-- ── MANUAL INPUT ── --}}
    <div class="scan-card">
        <div class="scan-card-head dark">&#9000; Input Manual</div>
        <div class="scan-card-body">
            <p style="font-size:12px;color:#666;margin-bottom:14px;">Masukkan kode booking jika QR tidak terbaca.</p>
            <div class="input-row">
                <input id="manual-code" class="manual-input" type="text" placeholder="Kode booking...">
                <button class="btn btn-dark" onclick="verifyManual()" style="flex:0;padding:11px 18px;">Cek</button>
            </div>
            <p style="font-size:11px;color:#888;margin-bottom:8px;">Tipe tiket:</p>
            <div class="type-chips">
                <span class="chip active" id="chip-bus"    onclick="setType('bus')">&#127568; Bus</span>
                <span class="chip"        id="chip-rental" onclick="setType('rental')">&#128652; Sewa</span>
                <span class="chip"        id="chip-tour"   onclick="setType('tour')">&#127758; Wisata</span>
            </div>
        </div>
    </div>

    {{-- ── RESULT ── --}}
    <div class="scan-card" id="result-card">
        <div class="scan-card-head" id="result-card-head">Hasil Verifikasi</div>
        <div id="result-body"></div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsqr/1.4.0/jsQR.min.js"></script>
<script>
let videoStream  = null;
let scanInterval = null;
let currentType  = 'bus';

// ── TYPE ──
function setType(type) {
    currentType = type;
    ['bus','rental','tour'].forEach(t => {
        const c = document.getElementById('chip-' + t);
        c.classList.toggle('active', t === type);
    });
}

// ── CAMERA ──
async function startCamera() {
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: { ideal: 640 }, height: { ideal: 480 } }
        });

        const v = document.getElementById('qr-video');
        v.srcObject = videoStream;
        await v.play();

        document.getElementById('camera-placeholder').style.display = 'none';
        document.getElementById('btn-start').style.display = 'none';
        document.getElementById('btn-stop').style.display  = '';

        scanInterval = setInterval(() => doScanFrame(v), 250);
    } catch (err) {
        showAlert('Tidak dapat mengakses kamera: ' + err.message);
    }
}

function stopCamera() {
    clearInterval(scanInterval);
    if (videoStream) { videoStream.getTracks().forEach(t => t.stop()); videoStream = null; }
    document.getElementById('camera-placeholder').style.display = 'flex';
    document.getElementById('btn-start').style.display = '';
    document.getElementById('btn-stop').style.display  = 'none';
}

function doScanFrame(video) {
    if (video.readyState < video.HAVE_ENOUGH_DATA) return;
    const c = document.createElement('canvas');
    c.width  = video.videoWidth;
    c.height = video.videoHeight;
    c.getContext('2d').drawImage(video, 0, 0);
    const img  = c.getContext('2d').getImageData(0, 0, c.width, c.height);
    const code = jsQR(img.data, img.width, img.height, { inversionAttempts: 'dontInvert' });
    if (code) { stopCamera(); sendToServer(code.data); }
}

// ── MANUAL ──
function verifyManual() {
    const raw = document.getElementById('manual-code').value.trim().toUpperCase();
    if (!raw) { showAlert('Masukkan kode booking terlebih dahulu.'); return; }

    // Build fake verify URL so backend parser works consistently
    const token = btoa(raw + '|manual');
    const fakeUrl = window.location.origin + '/ticket/verify?type=' + currentType
                  + '&code=' + encodeURIComponent(raw)
                  + '&token=' + token;
    sendToServer(fakeUrl);
}

// ── SEND ──
async function sendToServer(qrData) {
    showLoading();
    try {
        const res  = await fetch('{{ route("admin.ticket.scan.result") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ qr_data: qrData }),
        });
        const data = await res.json();
        renderResult(data);
    } catch (e) {
        renderResult({ valid: false, message: 'Kesalahan koneksi. Coba lagi.' });
    }
}

// ── RENDER ──
function showLoading() {
    const card = document.getElementById('result-card');
    const head = document.getElementById('result-card-head');
    const body = document.getElementById('result-body');
    card.style.display = 'block';
    head.style.background = '#888';
    head.className = 'scan-card-head';
    head.textContent = '⏳ Memverifikasi...';
    body.innerHTML = '<div style="padding:32px;text-align:center;color:#888;font-size:13px;">Sedang mengecek tiket...</div>';
    card.scrollIntoView({ behavior: 'smooth' });
}

function renderResult(data) {
    const card = document.getElementById('result-card');
    const head = document.getElementById('result-card-head');
    const body = document.getElementById('result-body');
    card.style.display = 'block';

    const typeColors = { bus: '#cc0000', rental: '#1a237e', tour: '#1b5e20' };
    const typeLabels = { bus: '🎫 Detail Tiket Bus', rental: '🚌 Detail Sewa Bus', tour: '🌍 Detail Paket Wisata' };

    if (data.valid) {
        const color = typeColors[data.type] ?? '#cc0000';
        head.style.background = color;
        head.textContent = '✓ Tiket Valid';

        let todayHtml = '';
        if (data.is_today !== undefined) {
            todayHtml = data.is_today
                ? '<span class="today-badge">✓ Jadwal Hari Ini — Penumpang Boleh Naik</span>'
                : '<span class="not-today-badge">⚠ Jadwal Bukan Hari Ini — Harap Periksa Ulang</span>';
        }

        let rows = '';
        if (data.data) {
            for (const [label, value] of Object.entries(data.data)) {
                const valHtml = (label === 'Status Bayar')
                    ? '<span class="value-paid">✓ ' + value + '</span>'
                    : escapeHtml(String(value));
                rows += `<div class="result-row">
                    <span class="result-row-label">${escapeHtml(label)}</span>
                    <span class="result-row-value">${valHtml}</span>
                </div>`;
            }
        }

        body.innerHTML = `
            <div class="result-status-head">
                <div class="result-status-icon ok">&#10003;</div>
                <div class="result-title ok">${escapeHtml(data.message)}</div>
                <div class="result-subtitle">Tiket telah terverifikasi dan sah</div>
            </div>
            ${todayHtml}
            <div class="result-table">
                <div class="result-table-inner">
                    <div class="result-table-head">${typeLabels[data.type] ?? '🎫 Detail Tiket'}</div>
                    ${rows}
                </div>
            </div>
            <div class="result-actions">
                <button class="btn btn-red" onclick="resetScan()">&#128269; Scan Tiket Lagi</button>
            </div>`;
    } else {
        head.style.background = '#b71c1c';
        head.textContent = '✗ Tiket Tidak Valid';
        body.innerHTML = `
            <div class="result-status-head">
                <div class="result-status-icon err">&#10007;</div>
                <div class="result-title err">Verifikasi Gagal</div>
                <div class="result-subtitle">${escapeHtml(data.message)}</div>
            </div>
            <div class="result-actions">
                <button class="btn btn-red" onclick="resetScan()">&#128269; Coba Lagi</button>
            </div>`;
    }

    card.scrollIntoView({ behavior: 'smooth' });
}

function resetScan() {
    document.getElementById('result-card').style.display = 'none';
    document.getElementById('manual-code').value = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function showAlert(msg) { alert(msg); }

function escapeHtml(str) {
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
</script>
@endpush