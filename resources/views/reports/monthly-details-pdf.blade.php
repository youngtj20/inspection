<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Inspection Details - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</title>
    <style>
        /* Modern CSS Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #374151;
            background-color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        
        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding: 20px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
        }
        
        .header h1 {
            margin: 0 0 15px 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 12px;
            opacity: 0.9;
        }
        
        /* Statistics Summary */
        .stats-summary {
            margin-bottom: 25px;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #bae6fd;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .stats-summary table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }
        
        .stats-summary td {
            padding: 12px 15px;
            font-size: 12px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        /* Main Data Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        
        th {
            background: linear-gradient(135deg, #4b5563 0%, #6b7280 100%);
            color: white;
            padding: 15px 10px;
            text-align: left;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #374151;
        }
        
        td {
            padding: 12px 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9px;
            transition: background-color 0.2s ease;
        }
        
        tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tr:hover {
            background-color: #f3f4f6;
        }
        
        /* Status Indicators */
        .passed {
            color: #10b981;
            font-weight: 700;
            padding: 4px 10px;
            background: #ecfdf5;
            border-radius: 20px;
            display: inline-block;
            font-size: 9px;
            border: 1px solid #a7f3d0;
        }
        
        .failed {
            color: #ef4444;
            font-weight: 700;
            padding: 4px 10px;
            background: #fef2f2;
            border-radius: 20px;
            display: inline-block;
            font-size: 9px;
            border: 1px solid #fecaca;
        }
        
        .pending {
            color: #f59e0b;
            font-weight: 700;
            padding: 4px 10px;
            background: #fffbeb;
            border-radius: 20px;
            display: inline-block;
            font-size: 9px;
            border: 1px solid #fde68a;
        }
        
        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            border-top: 2px solid #e5e7eb;
            padding-top: 20px;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            padding: 20px;
            border-radius: 0 0 12px 12px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        /* Section Title */
        .section-title {
            background: linear-gradient(135deg, #9ca3af 0%, #d1d5db 100%);
            padding: 12px 15px;
            margin: 20px 0 15px 0;
            font-weight: 700;
            font-size: 12px;
            color: #1f2937;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Data Highlighting */
        .data-highlight {
            font-weight: 600;
            color: #111827;
        }
        
        /* Print-specific optimizations */
        @media print {
            body {
                padding: 10px;
                font-size: 9px;
            }
            
            .header {
                padding: 15px;
                margin-bottom: 20px;
            }
            
            th {
                padding: 10px 8px;
                font-size: 9px;
            }
            
            td {
                padding: 8px 8px;
                font-size: 8px;
            }
        }
        
        /* Column width adjustments for better readability */
        .col-id { width: 3%; text-align: center; }
        .col-plate { width: 8%; }
        .col-type { width: 5%; text-align: center; }
        .col-model { width: 10%; }
        .col-owner { width: 12%; }
        .col-phone { width: 8%; }
        .col-engine { width: 8%; }
        .col-chassis { width: 8%; }
        .col-date { width: 8%; text-align: center; }
        .col-result { width: 6%; text-align: center; }
        .col-inspector { width: 8%; }
        .col-department { width: 9%; }
        
        /* Page break optimization */
        .page-break {
            page-break-before: always;
        }
        
        /* Alternate row styling with better contrast */
        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Monthly Inspection Details Report</h1>
        <p><strong>📅 Period:</strong> {{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
        <p><strong>🏢 Department:</strong> {{ $departmentName }}</p>
        <p><strong>🕒 Generated:</strong> {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</p>
        <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.3);">
            <p style="font-size: 10px; opacity: 0.8;">Vehicle Inspection Management System - Detailed Analysis Report</p>
        </div>
    </div>

    <div class="stats-summary">
        <table>
            <tr>
                <td>
                    <div style="display: flex; align-items: center;">
                        <div style="background: #3b82f6; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold;">
                            T
                        </div>
                        <div>
                            <strong>Total Records:</strong><br>
                            <span style="font-size: 16px; font-weight: bold; color: #1e40af;">{{ number_format($stats['total']) }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center;">
                        <div style="background: #10b981; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold;">
                            ✓
                        </div>
                        <div>
                            <strong>Passed:</strong><br>
                            <span class="passed" style="font-size: 16px; font-weight: bold; padding: 0;">{{ number_format($stats['passed']) }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center;">
                        <div style="background: #ef4444; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold;">
                            ✗
                        </div>
                        <div>
                            <strong>Failed:</strong><br>
                            <span class="failed" style="font-size: 16px; font-weight: bold; padding: 0;">{{ number_format($stats['failed']) }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center;">
                        <div style="background: #f59e0b; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold;">
                            ⏳
                        </div>
                        <div>
                            <strong>Pending:</strong><br>
                            <span class="pending" style="font-size: 16px; font-weight: bold; padding: 0;">{{ number_format($stats['pending']) }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="display: flex; align-items: center;">
                        <div style="background: #8b5cf6; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 10px; font-weight: bold;">
                            %
                        </div>
                        <div>
                            <strong>Pass Rate:</strong><br>
                            <span style="font-size: 16px; font-weight: bold; color: #7c3aed;">{{ $stats['pass_rate'] }}%</span>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-id">ID</th>
                <th class="col-plate">Plate No</th>
                <th class="col-type">Type</th>
                <th class="col-model">Make/Model</th>
                <th class="col-owner">Owner</th>
                <th class="col-phone">Phone</th>
                <th class="col-engine">Engine No</th>
                <th class="col-chassis">Chassis No</th>
                <th class="col-date">Inspect Date</th>
                <th class="col-result">Result</th>
                <th class="col-inspector">Inspector</th>
                <th class="col-department">Department</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detailedRecords as $record)
                <tr>
                    <td class="data-highlight" style="text-align: center;">{{ $record->id }}</td>
                    <td class="data-highlight">{{ $record->plateno }}</td>
                    <td style="text-align: center;">{{ $record->vehicletype }}</td>
                    <td>{{ $record->makeofvehicle }} {{ $record->model }}</td>
                    <td class="data-highlight">{{ $record->owner }}</td>
                    <td>{{ $record->phone }}</td>
                    <td>{{ $record->engineno }}</td>
                    <td>{{ $record->chassisno }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($record->inspectdate)->format('Y-m-d') }}</td>
                    <td style="text-align: center;">
                        @switch($record->testresult)
                            @case('1')
                                <span class="passed">PASSED</span>
                                @break
                            @case('2')
                                <span class="failed">FAILED</span>
                                @break
                            @default
                                <span class="pending">PENDING</span>
                        @endswitch
                    </td>
                    <td>{{ $record->inspector }}</td>
                    <td>{{ $record->department }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" style="text-align: center; padding: 15px;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <div style="margin-bottom: 15px;">
            <h3 style="font-size: 12px; font-weight: 600; color: #4b5563; margin-bottom: 8px;">📋 Report Summary</h3>
            <p>This comprehensive report contains detailed vehicle and inspection information for <strong>{{ number_format($stats['total']) }}</strong> records.</p>
        </div>
        <div style="border-top: 1px solid #d1d5db; padding-top: 15px; margin-top: 15px;">
            <p style="font-size: 8px; color: #6b7280; line-height: 1.4;">
                📄 <strong>Document Information:</strong><br>
                • Report Type: Monthly Inspection Details<br>
                • Format: PDF Document<br>
                • Page generated on {{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}<br>
                • System: Vehicle Inspection Management System<br>
                • Confidential: Internal Use Only
            </p>
        </div>
    </div>
</body>
</html>
