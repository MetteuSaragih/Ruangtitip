@extends('layouts.app')

@section('title', 'Opsi Pengiriman')

@push('styles')
<style>
    .checkout-page {
        min-height: calc(100vh - 64px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .checkout-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 2.5rem;
        width: 100%;
        max-width: 600px;
    }

    .checkout-title { font-size: 1.4rem; font-weight: 700; margin-bottom: 0.35rem; }
    .checkout-subtitle { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 2rem; }

    .shipping-option {
        background: var(--bg-secondary);
        border: 1.5px solid var(--border);
        border-radius: 14px;
        padding: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 1rem;
    }

    .shipping-option:hover { border-color: var(--purple-light); }
    .shipping-option.selected { border-color: var(--purple-light); background: rgba(124,58,237,0.08); }

    .shipping-icon { font-size: 2rem; flex-shrink: 0; }

    .shipping-info { flex: 1; }
    .shipping-name { font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.5rem; }
    .shipping-desc { font-size: 0.82rem; color: var(--text-muted); line-height: 1.5; }

    .instan-badge {
        background: var(--purple-light);
        color: white;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: 4px;
    }

    .shipping-price-wrap { text-align: right; }
    .price-free { color: var(--green); font-weight: 700; font-size: 0.9rem; }
    .price-calc { color: var(--purple-light); font-weight: 700; font-size: 0.9rem; }

    .radio-custom {
        width: 20px; height: 20px;
        border: 2px solid var(--border-light);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-top: 0.1rem; transition: border-color 0.2s;
    }

    .shipping-option.selected .radio-custom {
        border-color: var(--purple-light);
        background: var(--purple-light);
    }

    .shipping-option.selected .radio-custom::after {
        content: '';
        width: 8px; height: 8px;
        background: white; border-radius: 50%;
    }

    .checkout-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .btn-back {
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        border: 1.5px solid var(--border-light);
        background: transparent;
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        display: flex; align-items: center; gap: 0.4rem;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-back:hover { border-color: var(--purple-light); color: var(--purple-light); }

    .btn-next {
        flex: 1;
        padding: 0.75rem;
        border-radius: 12px;
        background: var(--border);
        color: var(--text-muted);
        border: none;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: not-allowed;
        transition: all 0.2s;
        display: flex; align-items: center; justify-content: center; gap: 0.4rem;
    }

    .btn-next.ready {
        background: linear-gradient(135deg, var(--purple-light), var(--purple-btn));
        color: white;
        cursor: pointer;
    }

    .btn-next.ready:hover { opacity: 0.9; }
</style>
@endpush

@section('content')
<div class="checkout-page">
    <div class="checkout-card">
        <h1 class="checkout-title">Opsi Pengiriman Toko</h1>
        <p class="checkout-subtitle">Pilih cara kamu menerima pesanan</p>

        <!-- Option 1: Pickup -->
        <div class="shipping-option" id="opt-pickup" onclick="selectShipping('pickup')">
            <div class="shipping-icon">🏪</div>
            <div class="shipping-info">
                <div class="shipping-name">Jemput Sendiri ke Toko</div>
                <div class="shipping-desc">Ambil pesananmu langsung di gudang RUTIP. Tidak ada biaya tambahan.</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.5rem;">
                <span class="price-free">Gratis</span>
                <div class="radio-custom" id="radio-pickup"></div>
            </div>
        </div>

        <!-- Option 2: Biteship Delivery -->
        <div class="shipping-option" id="opt-biteship" onclick="selectShipping('biteship')">
            <div class="shipping-icon">🛵</div>
            <div class="shipping-info">
                <div class="shipping-name">
                    Pengiriman Biteship
                    <span class="instan-badge">Instan</span>
                </div>
                <div class="shipping-desc">Dikirim ke alamatmu oleh kurir instan. Pilih kurir dan lihat harga di langkah berikutnya.</div>
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:0.5rem;">
                <span class="price-calc" id="biteship-price">Dihitung</span>
                <div class="radio-custom" id="radio-biteship"></div>
            </div>
        </div>

        <!-- Biteship courier options (shown when biteship selected) -->
        <div id="courierOptions" style="display:none; margin-bottom:1rem;">
            <div style="background:var(--bg-secondary);border:1px solid var(--border);border-radius:12px;padding:1rem;">
                <div style="font-size:0.85rem;font-weight:600;margin-bottom:0.75rem;color:var(--text-secondary);">Pilih Kurir</div>
                <div id="courierList">
                    <div style="display:flex;align-items:center;justify-content:center;padding:1rem;color:var(--text-muted);">
                        <span id="courierLoadText">🔄 Memuat opsi kurir...</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkout-actions">
            <a href="/toko-preloved" class="btn-back">← Kembali</a>
            <button class="btn-next" id="btnNext" onclick="goToPayment()">Lanjutkan →</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let selectedShipping = null;
    let selectedCourier = null;

    function selectShipping(type) {
        selectedShipping = type;
        selectedCourier = null;

        document.querySelectorAll('.shipping-option').forEach(el => el.classList.remove('selected'));
        document.getElementById(`opt-${type}`).classList.add('selected');

        if (type === 'biteship') {
            document.getElementById('courierOptions').style.display = 'block';
            loadCouriers();
            document.getElementById('btnNext').classList.remove('ready');
        } else {
            document.getElementById('courierOptions').style.display = 'none';
            document.getElementById('btnNext').classList.add('ready');
        }
    }

    function loadCouriers() {
        document.getElementById('courierLoadText').textContent = '🔄 Memuat opsi kurir...';

        fetch('/toko-preloved/shipping/rates', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: JSON.stringify({ destination: 'malang' })
        })
        .then(r => r.json())
        .then(data => {
            renderCouriers(data.rates || []);
        })
        .catch(() => {
            // Fallback static courier options if API fails
            renderCouriers([
                { courier_code: 'jne', courier_name: 'JNE REG', price: 9000, min_day: 1, max_day: 2 },
                { courier_code: 'jnt', courier_name: 'J&T Express', price: 8000, min_day: 1, max_day: 2 },
                { courier_code: 'sicepat', courier_name: 'SiCepat REG', price: 8500, min_day: 1, max_day: 2 },
                { courier_code: 'gosend', courier_name: 'GoSend Sameday', price: 15000, min_day: 0, max_day: 0 },
            ]);
        });
    }

    function renderCouriers(rates) {
        const list = document.getElementById('courierList');
        if (!rates.length) {
            list.innerHTML = '<div style="text-align:center;padding:1rem;color:var(--text-muted);">Tidak ada kurir tersedia</div>';
            return;
        }

        list.innerHTML = rates.map(r => `
            <div class="courier-item" onclick="selectCourier(this, '${r.courier_code}', ${r.price})"
                style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem;border-radius:10px;cursor:pointer;transition:background 0.2s;margin-bottom:0.5rem;border:1.5px solid var(--border);">
                <div>
                    <div style="font-size:0.875rem;font-weight:600;">${r.courier_name}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">${r.min_day === 0 ? 'Hari ini' : r.min_day + '-' + r.max_day + ' hari'}</div>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem;">
                    <span style="font-weight:700;color:var(--purple-light);">Rp ${Number(r.price).toLocaleString('id')}</span>
                    <div class="courier-radio" style="width:18px;height:18px;border:2px solid var(--border-light);border-radius:50%;"></div>
                </div>
            </div>
        `).join('');
    }

    function selectCourier(el, code, price) {
        document.querySelectorAll('.courier-item').forEach(i => {
            i.style.borderColor = 'var(--border)';
            i.style.background = '';
            i.querySelector('.courier-radio').style.background = '';
            i.querySelector('.courier-radio').style.borderColor = 'var(--border-light)';
        });

        el.style.borderColor = 'var(--purple-light)';
        el.style.background = 'rgba(124,58,237,0.08)';
        el.querySelector('.courier-radio').style.background = 'var(--purple-light)';
        el.querySelector('.courier-radio').style.borderColor = 'var(--purple-light)';

        selectedCourier = { code, price };
        document.getElementById('biteship-price').textContent = `Rp ${Number(price).toLocaleString('id')}`;
        document.getElementById('btnNext').classList.add('ready');

        // Store in session
        fetch('/toko-preloved/checkout/set-shipping', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: JSON.stringify({ type: 'biteship', courier: code, price: price })
        });
    }

    function goToPayment() {
        if (!selectedShipping) return;

        if (selectedShipping === 'pickup') {
            fetch('/toko-preloved/checkout/set-shipping', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ type: 'pickup', price: 0 })
            }).then(() => {
                window.location.href = '/toko-preloved/checkout/payment';
            });
        } else if (selectedCourier) {
            window.location.href = '/toko-preloved/checkout/payment';
        }
    }
</script>
@endpush
