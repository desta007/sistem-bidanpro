<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .invoice {
            max-width: 80mm;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #ccc;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #E91E8C;
        }

        .clinic-name {
            font-size: 14px;
            font-weight: 600;
            margin-top: 5px;
        }

        .clinic-info {
            font-size: 10px;
            color: #666;
            margin-top: 3px;
        }

        .invoice-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        .invoice-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .invoice-row .label {
            color: #666;
        }

        .invoice-row .value {
            font-weight: 500;
        }

        .patient-info {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        .patient-name {
            font-weight: 600;
        }

        .items {
            margin-bottom: 15px;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            font-weight: 600;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
            margin-bottom: 8px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .item-name {
            flex: 1;
        }

        .item-qty {
            width: 30px;
            text-align: center;
        }

        .item-price {
            width: 70px;
            text-align: right;
        }

        .totals {
            border-top: 1px dashed #ccc;
            padding-top: 10px;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .total-row.grand {
            font-size: 14px;
            font-weight: 700;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 2px solid #333;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #666;
            padding-top: 15px;
            border-top: 2px dashed #ccc;
        }

        .footer p {
            margin-bottom: 3px;
        }

        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status.paid {
            background: #DCFCE7;
            color: #166534;
        }

        .status.pending {
            background: #FEF3C7;
            color: #92400E;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 24px; background: #E91E8C; color: white; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
            🖨️ Cetak Invoice
        </button>
    </div>

    <div class="invoice">
        <div class="header">
            <div class="logo">BidanPRO</div>
            <div class="clinic-name">Bidan Praktik Mandiri</div>
            <div class="clinic-info">Jl. Contoh No. 123, Kota</div>
            <div class="clinic-info">Telp: 08123456789</div>
        </div>

        <div class="invoice-info">
            <div class="invoice-row">
                <span class="label">No. Invoice:</span>
                <span class="value">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="invoice-row">
                <span class="label">Tanggal:</span>
                <span class="value">{{ $invoice->invoice_date->format('d/m/Y') }}</span>
            </div>
            <div class="invoice-row">
                <span class="label">Kasir:</span>
                <span class="value">{{ $invoice->cashier?->name ?? 'System' }}</span>
            </div>
            <div class="invoice-row">
                <span class="label">Status:</span>
                <span
                    class="status {{ $invoice->status }}">{{ $invoice->status === 'paid' ? 'LUNAS' : 'PENDING' }}</span>
            </div>
        </div>

        <div class="patient-info">
            <div class="patient-name">{{ $invoice->patient->name }}</div>
            <div style="font-size: 10px; color: #666;">NIK: {{ $invoice->patient->nik }}</div>
        </div>

        <div class="items">
            <div class="items-header">
                <span class="item-name">Item</span>
                <span class="item-qty">Qty</span>
                <span class="item-price">Harga</span>
            </div>
            @foreach($invoice->items as $item)
                <div class="item">
                    <span class="item-name">{{ $item->item_name }}</span>
                    <span class="item-qty">{{ $item->quantity }}</span>
                    <span class="item-price">{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="totals">
            <div class="total-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($invoice->discount > 0)
                <div class="total-row">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($invoice->discount, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="total-row grand">
                <span>TOTAL</span>
                <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Pembayaran</span>
                <span>{{ ucfirst($invoice->payment_method) }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda</p>
            <p>Semoga lekas sehat!</p>
            <p style="margin-top: 10px;">{{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>

</html>