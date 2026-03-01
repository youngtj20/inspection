<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monthly Inspection Report — {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</title>
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
        td { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; }
        tr:nth-child(even) td { background: #fafafa; }
        .footer { margin-top: 16px; font-size: 9px; color: #999; text-align: right; }
        @page { margin: 15mm; }
    </style>
</head>
<body>
    <h1>Monthly Inspection Report</h1>
    <p class="subtitle">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</p>

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

    {{-- Department Breakdown --}}
    @if(count($departmentStats) > 0)
    <h2>By Department</h2>
    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th style="text-align:right">Total</th>
                <th style="text-align:right">Passed</th>
                <th style="text-align:right">Failed</th>
                <th style="text-align:right">Pending</th>
                <th style="text-align:right">Pass Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departmentStats as $dept)
            @php $comp = $dept->passed + $dept->failed; $rate = $comp > 0 ? round($dept->passed / $comp * 100, 1) : 0; @endphp
            <tr>
                <td>{{ $dept->department_name ?? 'Unassigned' }}</td>
                <td style="text-align:right">{{ number_format($dept->total) }}</td>
                <td style="text-align:right; color:#166534; font-weight:700">{{ number_format($dept->passed) }}</td>
                <td style="text-align:right; color:#991b1b; font-weight:700">{{ number_format($dept->failed) }}</td>
                <td style="text-align:right; color:#92400e">{{ number_format($dept->pending) }}</td>
                <td style="text-align:right">{{ $rate }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Vehicle Type Breakdown --}}
    @if($vehicleTypeStats->count())
    <h2>By Vehicle Type</h2>
    <table>
        <thead>
            <tr>
                <th>Vehicle Type</th>
                <th style="text-align:right">Total</th>
                <th style="text-align:right">Passed</th>
                <th style="text-align:right">Failed</th>
                <th style="text-align:right">Pass Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicleTypeStats as $type => $vt)
            @php $vc = $vt['passed'] + $vt['failed']; $vr = $vc > 0 ? round($vt['passed'] / $vc * 100, 1) : 0; @endphp
            <tr>
                <td>{{ $type ?: 'Unspecified' }}</td>
                <td style="text-align:right">{{ number_format($vt['total']) }}</td>
                <td style="text-align:right; color:#166534; font-weight:700">{{ number_format($vt['passed']) }}</td>
                <td style="text-align:right; color:#991b1b; font-weight:700">{{ number_format($vt['failed']) }}</td>
                <td style="text-align:right">{{ $vr }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Daily Activity --}}
    @if(count($dailyStats))
    <h2>Daily Activity</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th style="text-align:right">Total</th>
                <th style="text-align:right">Passed</th>
                <th style="text-align:right">Failed</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailyStats as $day => $d)
            <tr>
                <td>{{ $day === 'Unknown' ? '—' : \Carbon\Carbon::parse($day)->format('d M Y (D)') }}</td>
                <td style="text-align:right">{{ number_format($d['total']) }}</td>
                <td style="text-align:right; color:#166534">{{ number_format($d['passed']) }}</td>
                <td style="text-align:right; color:#991b1b">{{ number_format($d['failed']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">Generated {{ now()->format('d M Y H:i') }}</div>
</body>
</html>
