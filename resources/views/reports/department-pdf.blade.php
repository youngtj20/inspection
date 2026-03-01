<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Department Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        h2 { font-size: 12px; margin: 14px 0 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
        .subtitle { font-size: 11px; color: #666; margin-bottom: 12px; }
        .stats { display: flex; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
        .stat-box { border: 1px solid #ddd; border-radius: 4px; padding: 8px 14px; text-align: center; min-width: 80px; }
        .stat-box .num { font-size: 20px; font-weight: 700; }
        .stat-box .lbl { font-size: 9px; color: #777; text-transform: uppercase; }
        .pass { color: #16a34a; } .fail { color: #dc2626; } .pend { color: #d97706; } .rate { color: #7c3aed; }
        table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        th { background: #f1f5f9; font-size: 10px; text-transform: uppercase; padding: 5px 8px; text-align: left; border-bottom: 1px solid #cbd5e0; }
        td { padding: 5px 8px; border-bottom: 1px solid #f1f5f9; }
        tr:nth-child(even) td { background: #fafafa; }
        .dept-card { border: 1px solid #e2e8f0; border-radius: 6px; padding: 10px 14px; margin-bottom: 12px; page-break-inside: avoid; }
        .dept-title { font-size: 13px; font-weight: 700; color: #1e40af; margin-bottom: 6px; }
        .footer { margin-top: 16px; font-size: 9px; color: #999; text-align: right; }
        @page { margin: 15mm; }
    </style>
</head>
<body>
    <h1>Department Report</h1>
    <p class="subtitle">Period: {{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }} — {{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>

    {{-- Overall Stats --}}
    <div class="stats">
        <div class="stat-box">
            <div class="num">{{ number_format($stats['total']) }}</div>
            <div class="lbl">Total</div>
        </div>
        <div class="stat-box">
            <div class="num pass">{{ number_format($stats['passed']) }}</div>
            <div class="lbl">Passed</div>
        </div>
        <div class="stat-box">
            <div class="num fail">{{ number_format($stats['failed']) }}</div>
            <div class="lbl">Failed</div>
        </div>
        <div class="stat-box">
            <div class="num pend">{{ number_format($stats['pending']) }}</div>
            <div class="lbl">Pending</div>
        </div>
        <div class="stat-box">
            <div class="num rate">{{ $stats['pass_rate'] }}%</div>
            <div class="lbl">Pass Rate</div>
        </div>
    </div>

    {{-- Department comparison table --}}
    <h2>Department Comparison</h2>
    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th style="text-align:right">Total</th>
                <th style="text-align:right">Passed</th>
                <th style="text-align:right">Failed</th>
                <th style="text-align:right">Pending</th>
                <th style="text-align:right">Pass Rate</th>
                <th style="text-align:right">Equipment</th>
                <th style="text-align:right">Personnel</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deptBreakdown as $row)
            <tr>
                <td><strong>{{ $row['title'] }}</strong></td>
                <td style="text-align:right">{{ number_format($row['total']) }}</td>
                <td style="text-align:right; color:#166534; font-weight:700">{{ number_format($row['passed']) }}</td>
                <td style="text-align:right; color:#991b1b; font-weight:700">{{ number_format($row['failed']) }}</td>
                <td style="text-align:right; color:#92400e">{{ number_format($row['pending']) }}</td>
                <td style="text-align:right">{{ $row['pass_rate'] }}%</td>
                <td style="text-align:right">{{ number_format($row['equipment']) }}</td>
                <td style="text-align:right">{{ number_format($row['personnel']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Generated {{ now()->format('d M Y H:i') }}</div>
</body>
</html>
