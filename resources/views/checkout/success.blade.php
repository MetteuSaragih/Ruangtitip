@extends('layouts.dashboard')

@section('title', 'Pembayaran Berhasil')

@section('content')
<style>
    .success-page {
        min-height: calc(100vh - 80px);
        display: flex;
        justify-content: center;
        padding: 0 20px 72px;
        color: #fff;
    }

    .success-shell {
        width: 100%;
        max-width: 620px;
        background: #120a24;
        border-left: 1px solid rgba(139,92,246,0.16);
        border-right: 1px solid rgba(139,92,246,0.16);
        border-bottom: 1px solid rgba(139,92,246,0.16);
        border-radius: 0 0 28px 28px;
        padding: 34px 28px 36px;
        box-shadow: 0 28px 80px rgba(18,10,36,0.62);
    }

    .success-hero {
        text-align: center;
        padding: 0 16px 28px;
    }

    .success-icon {
        width: 110px;
        height: 110px;
        margin: 0 auto 32px;
        border: 4px solid rgba(45,212,191,0.42);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #34d399;
        background: rgba(20,184,166,0.10);
        box-shadow: 0 0 56px rgba(45,212,191,0.16);
        animation: popIn 0.45s cubic-bezier(.34,1.56,.64,1);
    }

    .success-icon svg {
        width: 58px;
        height: 58px;
        stroke-width: 2.6;
    }

    @keyframes popIn {
        from { transform: scale(.86); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .success-title {
        margin: 0 0 12px;
        font-size: 28px;
        line-height: 1.15;
        font-weight: 800;
        color: #fff;
    }

    .success-subtitle {
        margin: 0 0 12px;
        font-size: 16px;
        color: rgba(255,255,255,0.48);
    }

    .order-id {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
        color: #a78bfa;
        letter-spacing: .04em;
    }

    .notif-box {
        display: flex;
        gap: 16px;
        padding: 24px;
        margin-bottom: 26px;
        background: linear-gradient(135deg, rgba(20,184,166,0.12), rgba(14,116,144,0.10));
        border: 1px solid rgba(16,185,129,0.45);
        border-radius: 18px;
        text-align: left;
    }

    .notif-icon {
        width: 48px;
        height: 48px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(16,185,129,0.20);
        color: #34d399;
    }

    .notif-icon svg {
        width: 24px;
        height: 24px;
    }

    .notif-title {
        margin-bottom: 8px;
        font-size: 17px;
        font-weight: 800;
        color: #fff;
    }

    .notif-desc {
        max-width: 470px;
        margin: 0;
        color: rgba(255,255,255,0.58);
        font-size: 14px;
        line-height: 1.7;
    }

    .notif-desc strong { color: #fff; }

    .notif-link {
        margin-top: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #34d399;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
    }

    .notif-link svg {
        width: 17px;
        height: 17px;
    }

    .summary-box {
        padding: 22px 24px;
        margin-bottom: 36px;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 18px;
        text-align: left;
    }

    .summary-box h4 {
        margin: 0 0 18px;
        font-size: 16px;
        font-weight: 800;
        color: #fff;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        padding: 10px 0;
        font-size: 14px;
    }

    .summary-line .label {
        color: rgba(255,255,255,0.50);
    }

    .summary-line .value {
        color: #fff;
        font-weight: 800;
        white-space: nowrap;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-top: 8px;
        padding-top: 14px;
        border-top: 1px solid rgba(255,255,255,0.10);
        font-size: 16px;
        font-weight: 800;
    }

    .summary-total .value {
        font-size: 20px;
        color: #a78bfa;
        white-space: nowrap;
    }

    .btn-view-orders {
        width: 100%;
        min-height: 58px;
        border: none;
        border-radius: 16px;
        background: linear-gradient(135deg, #7c3aed, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        font-size: 16px;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 18px 48px rgba(124,58,237,0.38);
        transition: opacity .2s, transform .2s;
    }

    .btn-view-orders:hover {
        opacity: .92;
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-view-orders svg {
        width: 20px;
        height: 20px;
    }

    @media (max-width: 640px) {
        .success-page {
            padding: 0 0 80px;
        }

        .success-shell {
            border-radius: 0 0 22px 22px;
            padding: 28px 18px 28px;
        }

        .success-icon {
            width: 92px;
            height: 92px;
            margin-bottom: 26px;
        }

        .success-title {
            font-size: 24px;
        }

        .notif-box {
            padding: 18px;
            gap: 12px;
        }

        .notif-icon {
            width: 42px;
            height: 42px;
        }

        .summary-box {
            padding: 18px;
        }
    }
</style>

<div class="success-page">
    <div class="success-shell">
        <div class="success-hero">
            <div class="success-icon">
                <x-lucide-check />
            </div>

            <h1 class="success-title">Pembayaran Berhasil!</h1>
            <p class="success-subtitle">Pesananmu telah dikonfirmasi</p>
            <p class="order-id">#{{ $orderId }}</p>
        </div>

        <div class="notif-box">
            <div class="notif-icon">
                <x-lucide-message-circle />
            </div>
            <div>
                <div class="notif-title">Notifikasi Tracking via WhatsApp</div>
                <p class="notif-desc">
                    Pesanan Anda sedang diproses. Status pelacakan (tracking) dan resi pengiriman instant dari Biteship akan dikirimkan secara otomatis melalui <strong>WhatsApp Anda.</strong>
                </p>
                @if(($order['shipping']['method'] ?? 'pickup') === 'pickup')
                    <a href="#" class="notif-link">
                        <x-lucide-store />
                        Siapkan pesananmu untuk dijemput di Gudang RUTIP
                    </a>
                @else
                    <a href="#" class="notif-link">
                        <x-lucide-package-check />
                        Lacak pesananmu via Biteship
                    </a>
                @endif
            </div>
        </div>

        <div class="summary-box">
            <h4>Ringkasan Pesanan</h4>

            @foreach($order['cart'] as $item)
                <div class="summary-line">
                    <span class="label">{{ $item['name'] }} x {{ $item['qty'] }}</span>
                    <span class="value">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                </div>
            @endforeach

            <div class="summary-line">
                <span class="label">Biaya Layanan dan Platform</span>
                <span class="value">Rp {{ number_format($order['service_fee'], 0, ',', '.') }}</span>
            </div>

            <div class="summary-total">
                <span>Total Dibayar</span>
                <span class="value">Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="#" class="btn-view-orders">
            <x-lucide-package />
            Lihat Pesanan Saya
        </a>
    </div>
</div>
@endsection
