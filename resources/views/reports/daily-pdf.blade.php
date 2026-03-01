<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Inspection Report — {{ \Carbon\Carbon::parse($date)->format('d M Y') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin-bottom: 2px; }
        .subtitle { font-size: 11px; color: #666; margin-bottom: 12px; }
        .stats { display: flex; gap: 16px; margin-bottom: 16px; }
        .stat-box { border: 1px solid #ddd; border-radius: 4px; padding: 8px 14px; text-align: center; min-width: 80px; }
        .stat-box .num { font-size: 20px; font-weight: 700; }
        .stat-box .lbl { font-size: 9px; color: #777; text-transform: uppercase; }
        .pass { color: #16a34a; } .fail { color: #dc2626; } .pend { color: #d97706; } .rate { color: #7c3aed; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #f1f5f9; font-size: 10px; text-transform: uppercase; padding: 5px 8px; text-align: left; border-bottom: 1px solid #cbd5e0; }
        td { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        tr:nth-child(even) td { background: #fafafa; }
        .badge { display: inline-block; padding: 1px 6px; border-radius: 9999px; font-size: 9px; font-weight: 700; }
        .badge-pass { background: #dcfce7; color: #166534; }
        .badge-fail { background: #fee2e2; color: #991b1b; }
        .badge-pend { background: #fef9c3; color: #854d0e; }
        .footer { margin-top: 16px; font-size: 9px; color: #999; text-align: right; }
        @page { margin: 15mm; }
    </style>
</head>
<body>
    <h1>Daily Inspection Report</h1>
    <p class="subtitle">{{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>

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

    @if(count($deptBreakdown) > 1)
    <h2 style="font-size:12px; margin-bottom:6px;">Department Summary</h2>
    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th style="text-align:right">Total</th>
                <th style="text-align:right">Passed</th>
                <th style="text-align:right">Failed</th>
                <th style="text-align:right">Pass Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deptBreakdown as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td style="text-align:right">{{ number_format($row['total']) }}</td>
                <td style="text-align:right; color:#166534; font-weight:700">{{ number_format($row['passed']) }}</td>
                <td style="text-align:right; color:#991b1b; font-weight:700">{{ number_format($row['failed']) }}</td>
                <td style="text-align:right">{{ $row['pass_rate'] }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h2 style="font-size:12px; margin: 12px 0 6px;">Inspection Records ({{ number_format(count($inspections)) }})</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Plate No</th>
                <th>Make / Model</th>
                <th>Owner</th>
                <th>Department</th>
                <th>Time</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inspections as $i => $ins)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td><strong>{{ $ins->plateno }}</strong></td>
                <td>{{ $ins->makeofvehicle ?? '—' }}{{ $ins->model ? ' / ' . $ins->model : '' }}</td>
                <td>{{ Str::limit($ins->owner ?? '—', 20) }}</td>
                <td>{{ $ins->department_name ?? '—' }}</td>
                <td>{{ $ins->inspectdate ? \Carbon\Carbon::parse($ins->inspectdate)->format('H:i') : '—' }}</td>
                <td>
                    @if(in_array($ins->testresult, ['1','Y']))
                        <span class="badge badge-pass">Passed</span>
                    @elseif(in_array($ins->testresult, ['0','N']))
                        <span class="badge badge-fail">Failed</span>
                    @else
                        <span class="badge badge-pend">Pending</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; color:#999; padding:12px;">No inspections for this date</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Generated {{ now()->format('d M Y H:i') }}</div>
</body>
</html>
