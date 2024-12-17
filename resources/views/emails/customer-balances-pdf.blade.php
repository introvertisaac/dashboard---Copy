<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            size: landscape;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #000;
        }
        .header {
            margin-bottom: 20px;
        }
        .logo {
            width: 200px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background-color: #e76c21;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .number-column {
            width: 40px;
            text-align: center;
            background-color: #f5f5f5;
        }
        .timestamp {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('logo-main.png'))) }}" class="logo">
        <h2 style="color: #e76c21; margin: 10px 0;">Customer Balance Report</h2>
        <div class="timestamp">Generated on: {{ now()->format('Y-m-d H:i:s') }}</div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="number-column">#</th>
                <th>Customer Name</th>
                <th style="text-align: right;">Balance (KES)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
                <tr>
                    <td class="number-column">{{ $index + 1 }}</td>
                    <td>{{ $customer['name'] }}</td>
                    <td style="text-align: right;">{{ number_format($customer['balance']) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>