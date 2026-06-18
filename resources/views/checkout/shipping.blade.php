@extends('layouts.dashboard')

@section('title', 'Opsi Pengiriman')

@section('content')
<style>
    .checkout-wrap {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 40px 20px;
        background: #080810;
    }

    .checkout-card {
        background: #12121f;
        border: 1px solid #2a2a45;
        border-radius: 20px;
        padding: 36px;
        width: 100%;
        max-width: 600px;
    }

    .checkout-card h2 { font-size: 22px; font-weight: 700; margin-bottom: 6px; color: #fff; }
    .checkout-card > p { color: #6060a0; font-size: 14px; margin-bottom: 28px; }

    .shipping-option {
        background: #1a1a2e;
        border: 1.5px solid #2a2a45;
        border-radius: 14px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        cursor: pointer;
        margin-bottom: 14px;
        transition: all 0.2s;
    }

    .shipping-option:hover { border-color: #7c3aed; }
    .shipping-option.selected { border-color: #7c3aed; background: rgba(124,58,237,0.15); }

    .shipping-option-icon {
        width: 48px;
        height: 48px;
        background: #12121f;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }

    .shipping-option-body { flex: 1; }
    .shipping-option-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .instan-badge {
        background: #7c3aed;
        color: white;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }

    .shipping-option-desc { font-size: 13px; color: #6060a0; margin-top: 4px; line-height: 1.5; }

    .shipping-option-price { font-size: 15px; font-weight: 700; color: #22c55e; flex-shrink: 0; }
    .shipping-option-price.calculated { color: #8b5cf6; }

    .radio-circle {
        width: 20px;
        height: 20px;
        border: 2px solid #333355;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .shipping-option.selected .radio-circle {
        border-color: #7c3aed;
        background: #7c3aed;
    }

    .radio-inner { width: 8px; height: 8px; background: white; border-radius: 50%; display: none; }
    .shipping-option.selected .radio-inner { display: block; }

    .courier-selector {
        display: none;
        background: #0a0a14;
        border: 1px solid #2a2a45;
        border-radius: 12px;
        padding: 16px;
        margin-top: 14px;
    }
    .courier-selector.show { display: block; }
    .courier-selector h4 {
        font-size: 13px;
        font-weight: 700;
        color: #6060a0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 12px;
    }

    .form-group { margin-bottom: 12px; }
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #a0a0c0;
        margin-bottom: 6px;
        display: block;
    }
    .form-input {
        width: 100%;
        background: #1a1a2e;
        border: 1px solid #2a2a45;
        border-radius: 10px;
        padding: 10px 14px;
        color: #fff;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        font-family: inherit;
    }
    .form-input:focus { border-color: #7c3aed; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

    .btn-load-couriers {
        width: 100%;
        background: #1a1a2e;
        border: 1px solid #7c3aed;
        color: #8b5cf6;
        border-radius: 10px;
        padding: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 10px;
        font-family: inherit;
    }
    .btn-load-couriers:hover { background: rgba(124,58,237,0.15); }

    .courier-list { display: flex; flex-direction: column; gap: 8px; margin-top: 14px; }

    .courier-item {
        background: #1a1a2e;
        border: 1.5px solid #2a2a45;
        border-radius: 10px;
        padding: 12px 16px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
    }
    .courier-item:hover { border-color: #7c3aed; }
    .courier-item.selected { border-color: #7c3aed; background: rgba(124,58,237,0.15); }
    .courier-info h5 { font-size: 14px; font-weight: 600; color: #fff; }
    .courier-info p { font-size: 12px; color: #6060a0; }
    .courier-price { font-size: 15px; font-weight: 700; color: #a855f7; }

    .checkout-actions { display: flex; gap: 12px; margin-top: 28px; }

    .btn-back {
        background: transparent;
        border: 1.5px solid #333355;
        color: #fff;
        border-radius: 14px;
        padding: 14px 24px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-back:hover { border-color: #7c3aed; color: #8b5cf6; }

    .btn-next {
        flex: 1;
        background: #1a1a2e;
        border: none;
        color: #6060a0;
        border-radius: 14px;
        padding: 14px;
        font-size: 15px;
        font-weight: 700;
        cursor: not-allowed;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
        font-family: inherit;
    }
    .btn-next.ready {
        background: linear-gradient(135deg, #7c3aed, #8b5cf6);
        color: white;
        cursor: pointer;
    }
    .btn-next.ready:hover { opacity: 0.9; }
</style>

<div class="checkout-wrap">
    <div class="checkout-card">
        <h2>Opsi Pengiriman Toko</h2>
        <p>Pilih cara kamu menerima pesanan</p>

        <div class="shipping-option" id="opt-pickup" onclick="selectShipping('pickup')">
            <div class="shipping-option-icon">🏪</div>
            <div class="shipping-option-body">
                <div class="shipping-option-title">Jemput Sendiri ke Toko</div>
                <div class="shipping-option-desc">Ambil pesananmu langsung di gudang RUTIP. Tidak ada biaya tambahan.</div>
            </div>
            <div class="shipping-option-price">Gratis</div>
            <div class="radio-circle" id="radio-pickup"><div class="radio-inner"></div></div>
        </div>

        <div class="shipping-option" id="opt-biteship" onclick="selectShipping('biteship')">
            <div class="shipping-option-icon">🛵</div>
            <div class="shipping-option-body">
                <div class="shipping-option-title">
                    Pengiriman Biteship
                    <span class="instan-badge">Instan</span>
                </div>
                <div class="shipping-option-desc">Dikirim ke alamatmu oleh kurir instan. Pilih kurir dan lihat harga di langkah berikutnya.</div>
            </div>
            <div class="shipping-option-price calculated" id="biteship-price">Dihitung</div>
            <div class="radio-circle" id="radio-biteship"><div class="radio-inner"></div></div>
        </div>

        <div class="courier-selector" id="courier-selector">
            <h4>Masukkan Alamat Pengiriman</h4>
            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <input type="text" class="form-input" id="address" placeholder="Jl. Veteran No. 1, Lowokwaru">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kota</label>
                    <input type="text" class="form-input" id="city" placeholder="Malang" value="Malang">
                </div>
                <div class="form-group">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" class="form-input" id="postal" placeholder="65145" value="65145">
                </div>
            </div>
            <button class="btn-load-couriers" onclick="loadCouriers()">Cek Ongkos Kirim →</button>
            <div class="courier-list" id="courier-list"></div>
        </div>

        <div class="checkout-actions">
            <a href="{{ route('preloved.index') }}" class="btn-back">← Kembali</a>
            <button class="btn-next" id="btn-next" onclick="goToPayment()">Lanjutkan →</button>
        </div>
    </div>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';
let selectedMethod = null;
let selectedCourier = null;
let shippingCost = 0;

function selectShipping(method) {
    selectedMethod = method;
    ['opt-pickup', 'opt-biteship'].forEach(id => document.getElementById(id).classList.remove('selected'));
    document.getElementById('opt-' + method).classList.add('selected');

    if (method === 'biteship') {
        document.getElementById('courier-selector').classList.add('show');
        document.getElementById('btn-next').classList.remove('ready');
    } else {
        document.getElementById('courier-selector').classList.remove('show');
        shippingCost = 0;
        selectedCourier = null;
        document.getElementById('btn-next').classList.add('ready');
    }
}

function loadCouriers() {
    const btn = document.querySelector('.btn-load-couriers');
    btn.textContent = 'Memuat...';
    btn.disabled = true;

    setTimeout(() => {
        renderCouriers(getMockCouriers());
        btn.textContent = 'Cek Ongkos Kirim →';
        btn.disabled = false;
    }, 800);
}

function getMockCouriers() {
    return [
        { courier_name: 'JNE', courier_service_name: 'Reguler', duration: '1-2 hari', price: 12000, courier_code: 'jne', courier_service_code: 'reg' },
        { courier_name: 'J&T Express', courier_service_name: 'Express', duration: '1-2 hari', price: 11000, courier_code: 'jnt', courier_service_code: 'exp' },
        { courier_name: 'SiCepat', courier_service_name: 'Halu', duration: '1-2 hari', price: 10000, courier_code: 'sicepat', courier_service_code: 'halu' },
        { courier_name: 'GoSend', courier_service_name: 'Same Day', duration: '3-5 jam', price: 25000, courier_code: 'gosend', courier_service_code: 'sameday' },
    ];
}

function renderCouriers(couriers) {
    document.getElementById('courier-list').innerHTML = couriers.map(c => `
        <div class="courier-item" onclick="selectCourier(this, ${c.price}, '${c.courier_code}', '${c.courier_service_code}', '${c.courier_name} ${c.courier_service_name}')">
            <div class="courier-info">
                <h5>${c.courier_name} - ${c.courier_service_name}</h5>
                <p>Estimasi ${c.duration}</p>
            </div>
            <div class="courier-price">Rp ${c.price.toLocaleString('id-ID')}</div>
        </div>
    `).join('');
}

function selectCourier(el, price, code, service, label) {
    document.querySelectorAll('.courier-item').forEach(i => i.classList.remove('selected'));
    el.classList.add('selected');
    selectedCourier = { code, service, label, price };
    shippingCost = price;
    document.getElementById('biteship-price').textContent = 'Rp ' + price.toLocaleString('id-ID');
    document.getElementById('btn-next').classList.add('ready');
}

function goToPayment() {
    if (!selectedMethod) return;
    if (!document.getElementById('btn-next').classList.contains('ready')) return;

    fetch('{{ route("checkout.shipping.save") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({
            shipping_method: selectedMethod,
            address: {
                full: document.getElementById('address')?.value || '',
                city: document.getElementById('city')?.value || 'Malang',
                postal_code: document.getElementById('postal')?.value || '65145',
            },
            shipping_cost: shippingCost,
            courier: selectedCourier?.code,
            service: selectedCourier?.service,
        })
    })
    .then(r => r.json())
    .then(data => { if (data.success) window.location.href = data.redirect; });
}
</script>
@endsection