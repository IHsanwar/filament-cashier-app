<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $transaction->id }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE</h2>
        <p>No Transaksi #{{ $transaction->id }}</p>
    </div>

    <div class="info">
        <div><strong>Date:</strong> {{ $transaction->date }}</div>
        <div><strong>Status:</strong> Terbayar</div>
    </div>

    <div class="section-title">Items</div>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th class="text-center">Qty</th>
                <th class="text-right">Harga Satuan</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp

            @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">Rp{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-right">
                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                        @php $total += $item->subtotal; @endphp
                    </td>
                </tr>
            @endforeach
        </tbody>        </tbody>
    </table>

    <p class="total text-right" style="text-align: right; font-weight: bold; margin-top: 10px;">
        Total: Rp{{ number_format($total, 0, ',', '.') }}
    </p>

    <p style="margin-top: 30px;">Terimakasih atas kunjungan anda!</p>

</body>
</html>
