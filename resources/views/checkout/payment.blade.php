@extends('layouts.dashboard')

@section('title', 'Ringkasan Pembayaran')

@section('content')
<style>
    .checkout-wrap {
        min-height: calc(100vh - 80px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 28px 20px 140px;
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

    .summary-box {
        background: #1a1a2e;
        border: 1px solid #2a2a45;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .summary-box h3 { font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #fff; }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        padding: 6px 0;
    }

    .summary-line .label { color: #a0a0c0; }
    .summary-line .value { font-weight: 500; color: #fff; }
    .summary-line.free .value { color: #22c55e; }

    .summary-divider { border: none; border-top: 1px solid #2a2a45; margin: 12px 0; }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 4px;
    }

    .summary-total .label { font-size: 16px; font-weight: 700; color: #fff; }
    .summary-total .value { font-size: 22px; font-weight: 800; color: #a855f7; }

    /* Payment Methods */
    .payment-methods {
        background: #1a1a2e;
        border: 1px solid #2a2a45;
        border-radius: 14px;
        padding: 20px;
    }

    .payment-methods h3 { font-size: 16px; font-weight: 700; margin-bottom: 16px; color: #fff; }

    .payment-option {
        background: rgba(255,255,255,0.025);
        border: 1.5px solid #3a3450;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 14px;
        cursor: pointer;
        margin-bottom: 10px;
        transition: all 0.2s;
    }

    .payment-option:last-child { margin-bottom: 0; }
    .payment-option:hover { border-color: #7c3aed; }
    .payment-option.selected { border-color: #7c3aed; background: rgba(124,58,237,0.15); }

    .payment-icon {
        width: 40px;
        height: 40px;
        background: rgba(124,58,237,0.15);
        border: 1px solid rgba(124,58,237,0.3);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .payment-info { flex: 1; }
    .payment-name { font-size: 15px; font-weight: 600; color: #fff; }
    .payment-desc { font-size: 12px; color: #6060a0; margin-top: 2px; }

    .payment-radio {
        width: 20px;
        height: 20px;
        border: 2px solid #333355;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .payment-option.selected .payment-radio {
        border-color: #7c3aed;
        background: #7c3aed;
    }

    .payment-radio-inner { width: 8px; height: 8px; background: white; border-radius: 50%; display: none; }
    .payment-option.selected .payment-radio-inner { display: block; }

    /* Fixed Bottom */
    .checkout-bottom {
        position: fixed;
        bottom: 28px;
        left: 50%;
        width: min(600px, calc(100vw - 40px));
        transform: translateX(-50%);
        background: transparent;
        backdrop-filter: blur(20px);
        display: flex;
        gap: 14px;
        z-index: 50;
    }

    .checkout-bottom-back {
        width: 128px;
        min-height: 72px;
        padding: 0 22px;
        background: #12121f;
        border: 1.5px solid #3a3450;
        border-radius: 14px;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.2s;
        text-decoration: none;
        white-space: nowrap;
    }
    .checkout-bottom-back:hover { color: #8b5cf6; }

    .checkout-bottom-main {
        flex: 1;
        min-height: 72px;
        padding: 12px 18px;
        background: #12121f;
        border: 1.5px solid rgba(124,58,237,0.55);
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .checkout-bottom-total { flex: 1; }
    .checkout-bottom-total p { font-size: 12px; color: #6060a0; margin: 0 0 2px; }
    .checkout-bottom-total span { font-size: 20px; font-weight: 800; color: #a855f7; }

    .checkout-bottom-pay {
        min-width: 128px;
        min-height: 48px;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(135deg, #7c3aed, #6366f1);
        border: none;
        color: white;
        padding: 0 26px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: opacity 0.2s;
        font-family: inherit;
        white-space: nowrap;
    }
    .checkout-bottom-pay:not(:disabled):hover { opacity: 0.9; }
    .checkout-bottom-pay:disabled {
        background: #3b2c73;
        color: rgba(255,255,255,0.45);
        cursor: not-allowed;
    }

    @media (max-width: 640px) {
        .checkout-card { padding: 24px 18px; }
        .checkout-bottom {
            bottom: 16px;
            width: calc(100vw - 24px);
            gap: 10px;
        }
        .checkout-bottom-back {
            width: 108px;
            padding: 0 16px;
            font-size: 14px;
        }
        .checkout-bottom-main {
            padding: 10px 12px;
            gap: 10px;
        }
        .checkout-bottom-total span { font-size: 18px; }
        .checkout-bottom-pay {
            min-width: 94px;
            padding: 0 16px;
            font-size: 14px;
        }
    }

    .spinner {
        width: 16px; height: 16px;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        display: inline-block;
        animation: spin 0.8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="checkout-wrap">
    <div class="checkout-card">
        <h2>Ringkasan Pembayaran</h2>
        <p>Tinjau dan selesaikan pembayaran</p>

        {{-- Order Summary --}}
        <div class="summary-box">
            <h3>Total Harga Produk</h3>

            @foreach($cart as $item)
            <div class="summary-line">
                <span class="label">{{ $item['name'] }} × {{ $item['qty'] }}</span>
                <span class="value">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
            </div>
            @endforeach

            <hr class="summary-divider">

            <div class="summary-line">
                <span class="label">Subtotal Produk</span>
                <span class="value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="summary-line">
                <span class="label">Biaya Layanan dan Platform</span>
                <span class="value">Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
            </div>
            <div class="summary-line {{ $shippingCost === 0 ? 'free' : '' }}">
                <span class="label">Biaya Pengiriman ({{ $shipping['method'] === 'pickup' ? 'Jemput Sendiri' : 'Biteship' }})</span>
                <span class="value">{{ $shippingCost === 0 ? 'Gratis' : 'Rp ' . number_format($shippingCost, 0, ',', '.') }}</span>
            </div>

            <hr class="summary-divider">

            <div class="summary-total">
                <span class="label">Total Keseluruhan</span>
                <span class="value">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="payment-methods">
            <h3>Metode Pembayaran</h3>

            <div class="payment-option" id="pay-qris" onclick="selectPayment('qris')">
                <div class="payment-icon">📱</div>
                <div class="payment-info">
                    <div class="payment-name">QRIS</div>
                    <div class="payment-desc">Semua e-wallet</div>
                </div>
                <div class="payment-radio"><div class="payment-radio-inner"></div></div>
            </div>

            <div class="payment-option" id="pay-va" onclick="selectPayment('va')">
                <div class="payment-icon">🏦</div>
                <div class="payment-info">
                    <div class="payment-name">Virtual Account</div>
                    <div class="payment-desc">BCA, BRI, Mandiri</div>
                </div>
                <div class="payment-radio"><div class="payment-radio-inner"></div></div>
            </div>

            <div class="payment-option" id="pay-ewallet" onclick="selectPayment('ewallet')">
                <div class="payment-icon">💜</div>
                <div class="payment-info">
                    <div class="payment-name">E-Wallet</div>
                    <div class="payment-desc">GoPay, OVO, Dana</div>
                </div>
                <div class="payment-radio"><div class="payment-radio-inner"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- Fixed Bottom Bar --}}
<div class="checkout-bottom">
    <a href="{{ route('checkout.shipping') }}" class="checkout-bottom-back">← Kembali</a>
    <div class="checkout-bottom-main">
        <div class="checkout-bottom-total">
            <p>Total Pembayaran</p>
            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
        </div>
        <button class="checkout-bottom-pay" id="pay-btn" onclick="processPayment()" disabled>
            Bayar →
        </button>
    </div>
</div>

<script>
const csrfToken = '{{ csrf_token() }}';
let selectedPayment = null;

function selectPayment(method) {
    selectedPayment = method;
    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
    document.getElementById('pay-' + method).classList.add('selected');
    document.getElementById('pay-btn').disabled = false;
}

function processPayment() {
    if (!selectedPayment) return;

    const btn = document.getElementById('pay-btn');
    btn.innerHTML = '<span class="spinner"></span> Memproses...';
    btn.disabled = true;

    fetch('{{ route("checkout.process") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ payment_method: selectedPayment })
    })
    .then(r => r.json())
    .then(data => {
        if (data.redirect) {
            window.location.href = data.redirect;
        } else {
            btn.innerHTML = 'Bayar →';
            btn.disabled = false;
        }
    })
    .catch(() => {
        btn.innerHTML = 'Bayar →';
        btn.disabled = false;
    });
}
</script>
@endsection
