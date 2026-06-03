<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; margin: 0; padding: 40px; }
        .header { display: table; width: 100%; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header-logo { display: table-cell; width: 60px; vertical-align: middle; }
        .header-logo img { width: 50px; height: 50px; }
        .header-info { display: table-cell; vertical-align: middle; padding-left: 15px; }
        .header-info h1 { margin: 0; font-size: 20px; }
        .header-info p { margin: 2px 0; color: #666; }
        .meta { margin-bottom: 25px; }
        .meta table { width: 100%; }
        .meta td { padding: 3px 0; }
        .meta .label { font-weight: bold; width: 120px; }
        .items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items th { background: #f5f5f5; border: 1px solid #ddd; padding: 8px; text-align: left; font-weight: bold; }
        .items td { border: 1px solid #ddd; padding: 8px; }
        .items .right { text-align: right; }
        .total { text-align: right; font-size: 16px; font-weight: bold; margin-bottom: 30px; padding: 10px; background: #f5f5f5; }
        .payment { margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px; }
        .payment h3 { margin-bottom: 10px; }
        .payment p { margin: 3px 0; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-logo">
            <img src="{{ public_path('shop-logo.png') }}" alt="Logo">
        </div>
        <div class="header-info">
            <h1>{{ $shop['name'] }}</h1>
            <p>{{ $shop['address'] }}</p>
            <p>{{ $shop['phone'] }}</p>
        </div>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td class="label">Date:</td>
                <td>{{ $date }}</td>
            </tr>
            <tr>
                <td class="label">Customer:</td>
                <td>{{ $customerName }}</td>
            </tr>
            <tr>
                <td class="label">Mobile:</td>
                <td>{{ $customerMobile }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td class="right">{{ $item['quantity'] }}</td>
                    <td class="right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                    <td class="right">Rp {{ number_format($item['quantity'] * $item['unit_price'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: Rp {{ number_format($total, 0, ',', '.') }}
    </div>

    <div class="payment">
        <h3>Payment Methods</h3>
        <p><strong>BCA Transfer</strong></p>
        <p><strong>QRIS</strong></p>
    </div>
</body>
</html>
