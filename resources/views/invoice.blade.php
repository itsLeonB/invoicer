<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $transaction->id }}</title>

    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #f3f4f6;
            --text-color: #374151;
            --border-color: #e5e7eb;
            --heading-bg: #f9fafb;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: #f9fafb;
            padding: 20px;
        }

        .invoice-box {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px;
            background-color: white;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .logo {
            max-width: 200px;
            height: auto;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .invoice-details {
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        table th {
            background-color: var(--heading-bg);
            color: var(--text-color);
            font-weight: bold;
            padding: 12px;
            border-bottom: 2px solid var(--border-color);
        }

        table th.text-right {
            text-align: right;
        }

        table th:first-child {
            text-align: left;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .text-right {
            text-align: right;
        }

        .item-name {
            width: 40%;
        }

        .qty {
            width: 20%;
        }

        .price {
            width: 20%;
        }

        .amount {
            width: 20%;
        }

        .total-row td {
            border-top: 2px solid var(--border-color);
            border-bottom: none;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        .thank-you {
            margin-bottom: 10px;
            font-weight: bold;
            color: var(--primary-color);
        }

        @media only screen and (max-width: 768px) {
            .invoice-header {
                flex-direction: column;
            }

            .invoice-info {
                text-align: left;
                margin-top: 20px;
            }

            table thead {
                display: none;
            }

            table tr {
                display: block;
                margin-bottom: 20px;
                border: 1px solid var(--border-color);
                border-radius: 4px;
                padding: 12px;
            }

            table td {
                display: flex;
                justify-content: space-between;
                text-align: right;
                padding: 8px 0;
                border-bottom: 1px dashed var(--border-color);
            }

            table td:last-child {
                border-bottom: none;
            }

            table td:before {
                content: attr(data-label);
                font-weight: bold;
                text-align: left;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="invoice-header">
            <div>
                <img class="logo" src="{{ asset('images/logo.png') }}" alt="Company Logo">
            </div>
            <div class="invoice-info">
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-details">
                    <strong>Invoice #:</strong> {{ $transaction->id }}<br>
                    <strong>Date:</strong> {{ $transaction->created_at->format(config('app.datetime.format')) }}
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="item-name">Item</th>
                    <th class="qty text-right">Quantity</th>
                    <th class="price text-right">Price</th>
                    <th class="amount text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->products as $product)
                    <tr>
                        <td data-label="Item">{{ $product->product_name }}</td>
                        <td data-label="Quantity" class="text-right">{{ $product->quantity }}</td>
                        <td data-label="Price" class="text-right">Rp{{ number_format($product->price) }}</td>
                        <td data-label="Amount" class="text-right">Rp{{ number_format($product->total) }}</td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="3" class="text-right">Total:</td>
                    <td class="text-right">Rp{{ number_format($transaction->amount) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div class="thank-you">Thank you for your business!</div>
            <div>
                <p>If you have any questions about this invoice, please contact</p>
                <p>{{ config('app.company.email', 'support@yourcompany.com') }} |
                    {{ config('app.company.phone', '+123456789') }}</p>
            </div>
        </div>
    </div>
</body>

</html>
