<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #191c1e; margin: 0; padding: 40px; }

        /* Header */
        .header { display: table; width: 100%; margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid #e0e3e5; }
        .header-logo { display: table-cell; width: 80px; vertical-align: top; }
        .header-logo img { width: 70px; height: 70px; object-fit: contain; }
        .header-info { display: table-cell; vertical-align: top; padding-left: 15px; }
        .header-info h1 { margin: 0 0 5px 0; font-size: 24px; font-weight: 700; color: #004ac6; }
        .header-info p { margin: 2px 0; color: #434655; font-size: 12px; }

        /* Customer & Date Section */
        .meta-section { display: table; width: 100%; margin-bottom: 25px; }
        .bill-to { display: table-cell; width: 50%; vertical-align: top; }
        .bill-to-box { background: #f2f4f6; padding: 15px; border-radius: 8px; border: 1px solid #c3c6d7; }
        .bill-to-label { font-size: 10px; font-weight: 600; color: #004ac6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .bill-to-name { font-size: 18px; font-weight: 600; margin: 5px 0 3px 0; }
        .bill-to-mobile { color: #434655; font-size: 13px; }
        .date-col { display: table-cell; width: 50%; vertical-align: middle; text-align: right; }
        .date-label { font-size: 13px; color: #434655; margin-right: 10px; }
        .date-value { font-size: 13px; font-weight: 600; }

        /* Items Table */
        .items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items th { background: #004ac6; color: #ffffff; padding: 10px 15px; font-size: 12px; font-weight: 500; text-align: left; }
        .items th:first-child { border-radius: 8px 0 0 0; }
        .items th:last-child { border-radius: 0 8px 0 0; text-align: right; }
        .items th.center { text-align: center; }
        .items th.right { text-align: right; }
        .items td { padding: 12px 15px; border-bottom: 1px solid #e0e3e5; font-size: 13px; }
        .items td.center { text-align: center; color: #434655; }
        .items td.right { text-align: right; }
        .items td.amount { text-align: right; font-weight: 600; }
        .items tr:nth-child(even) { background: #fafbfc; }

        /* Total */
        .total-section { text-align: right; padding-top: 15px; margin-bottom: 30px; border-top: 2px solid #004ac6; }
        .total-label { font-size: 16px; font-weight: 600; }
        .total-value { font-size: 18px; font-weight: 700; color: #004ac6; margin-left: 15px; }

        /* Payment Footer */
        .payment { margin-top: 30px; padding-top: 25px; border-top: 1px solid #c3c6d7; }
        .payment-grid { display: table; width: 100%; }
        .payment-col { display: table-cell; width: 50%; vertical-align: top; }
        .payment-col-left { text-align: center; }
        .qris-box { display: inline-block; border: 1px solid #c3c6d7; border-radius: 8px; padding: 5px; }
        .qris-box img { width: 160px; }
        .bank-box { background: #eceef0; padding: 15px; border-radius: 8px; }
        .bank-label { font-size: 10px; font-weight: 600; color: #004ac6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .bank-row { display: table; width: 100%; padding: 5px 0; border-bottom: 1px solid #ffffff; }
        .bank-row:last-child { border-bottom: none; }
        .bank-row-label { display: table-cell; color: #434655; font-size: 12px; }
        .bank-row-value { display: table-cell; text-align: right; font-weight: 600; font-size: 12px; }
    </style>
</head>
<body>
    <!-- Header -->
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

    <!-- Customer & Date -->
    <div class="meta-section">
        <div class="bill-to">
            <div class="bill-to-box">
                <div class="bill-to-label">Bill To</div>
                <div class="bill-to-name">{{ $customerName }}</div>
                <div class="bill-to-mobile">{{ $customerMobile }}</div>
            </div>
        </div>
        <div class="date-col">
            <span class="date-label">Transaction Date</span>
            <span class="date-value">{{ $date }}</span>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="center">Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item['description'] }}</td>
                    <td class="center">{{ $item['quantity'] }}</td>
                    <td class="right">Rp {{ number_format($item['unit_price'], 0, ',', '.') }}</td>
                    <td class="amount">Rp {{ number_format($item['quantity'] * $item['unit_price'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total -->
    <div class="total-section">
        <span class="total-label">Total Amount:</span>
        <span class="total-value">Rp {{ number_format($total, 0, ',', '.') }}</span>
    </div>

    <!-- Payment Methods -->
    <div class="payment">
        <div class="payment-grid">
            <div class="payment-col payment-col-left">
                <div class="qris-box">
                    <img src="{{ public_path('qris.jpeg') }}" alt="QRIS">
                </div>
            </div>
            <div class="payment-col">
                <div class="bank-box">
                    <div class="bank-label">Bank Transfer</div>
                    <div class="bank-row">
                        <span class="bank-row-label">Bank Name:</span>
                        <span class="bank-row-value">BCA</span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-row-label">Account Number:</span>
                        <span class="bank-row-value">{{ $shop['bca_account_number'] }}</span>
                    </div>
                    <div class="bank-row">
                        <span class="bank-row-label">Beneficiary:</span>
                        <span class="bank-row-value">{{ $shop['bca_account_name'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
