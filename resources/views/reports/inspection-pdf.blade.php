<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inspection Report - {{ $inspection->base->seriesno }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #000;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
        }
        .barcode-section {
            text-align: center;
            margin: 20px 0;
        }
        .barcode-section img {
            max-width: 300px;
            height: auto;
        }
        .qr-code {
            float: right;
            margin: 10px;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background-color: #f0f0f0;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 14px;
            border-left: 4px solid #333;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #f8f8f8;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 30%;
            font-weight: bold;
            padding: 5px 10px 5px 0;
        }
        .info-value {
            display: table-cell;
            padding: 5px 0;
        }
        .status-pass {
            color: #22c55e;
            font-weight: bold;
        }
        .status-fail {
            color: #ef4444;
            font-weight: bold;
        }
        .result-box {
            border: 3px solid;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            font-weight: bold;
        }
        .result-pass {
            border-color: #22c55e;
            background-color: #f0fdf4;
            color: #22c55e;
        }
        .result-fail {
            border-color: #ef4444;
            background-color: #fef2f2;
            color: #ef4444;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
        }
        .signature-section {
            margin-top: 40px;
        }
        .signature-box {
            display: inline-block;
            width: 45%;
            text-align: center;
            margin: 10px 2%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            padding-top: 5px;
        }
        @media print {
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>VEHICLE INSPECTION CERTIFICATE</h1>
        <p>Federal Republic of Nigeria</p>
        <p>{{ $inspection->base->department_name ?? 'Vehicle Inspection Service' }}</p>
        @if($inspection->base->department_address)
        <p>{{ $inspection->base->department_address }}</p>
        @endif
    </div>
    
    <!-- QR Code -->
    <div class="qr-code">
        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" style="width: 100px; height: 100px;">
    </div>
    
    <!-- Barcode -->
    <div class="barcode-section">
        <p><strong>Inspection Series Number</strong></p>
        <p style="font-size: 18px; letter-spacing: 3px; font-weight: bold;">{{ $inspection->base->seriesno }}</p>
        <p style="font-size: 10px; color: #666;">Scan QR code to verify authenticity</p>
    </div>
    
    <div style="clear: both;"></div>
    
    <!-- Vehicle Information -->
    <div class="section">
        <div class="section-title">VEHICLE INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Plate Number:</div>
                <div class="info-value">{{ $inspection->base->plateno }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Vehicle Type:</div>
                <div class="info-value">{{ $inspection->base->vehicletype }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Make/Model:</div>
                <div class="info-value">{{ $inspection->vehicle->makeofvehicle ?? 'N/A' }} / {{ $inspection->vehicle->model ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Engine Number:</div>
                <div class="info-value">{{ $inspection->vehicle->engineno ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Chassis Number:</div>
                <div class="info-value">{{ $inspection->vehicle->chassisno ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Owner:</div>
                <div class="info-value">{{ $inspection->base->owner }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fuel Type:</div>
                <div class="info-value">
                    @if($inspection->vehicle->fueltype == '1') Petrol
                    @elseif($inspection->vehicle->fueltype == '2') Diesel
                    @elseif($inspection->vehicle->fueltype == '3') Electric
                    @else N/A
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Inspection Details -->
    <div class="section">
        <div class="section-title">INSPECTION DETAILS</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Inspection Date:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($inspection->base->inspectdate)->format('F d, Y H:i') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Inspection Type:</div>
                <div class="info-value">
                    @if($inspection->base->inspecttype == '1') Initial Inspection
                    @elseif($inspection->base->inspecttype == '2') Periodic Inspection
                    @elseif($inspection->base->inspecttype == '3') Re-inspection
                    @else N/A
                    @endif
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Inspector:</div>
                <div class="info-value">{{ $inspection->base->inspector ?? 'N/A' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Inspection Times:</div>
                <div class="info-value">{{ $inspection->base->inspecttimes }}</div>
            </div>
        </div>
    </div>
    
    <!-- Test Results -->
    <div class="section page-break">
        <div class="section-title">TEST RESULTS</div>
        
        <!-- Brake Test -->
        @if($inspection->brake['summary'])
        <h4 style="margin: 15px 0 10px 0; font-size: 13px;">Brake System Test</h4>
        <table>
            <tr>
                <th>Parameter</th>
                <th>Front Axle</th>
                <th>Rear Axle</th>
                <th>Overall</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>Brake Efficiency (%)</td>
                <td>{{ $inspection->brake['front']->brakeeff ?? 'N/A' }}</td>
                <td>{{ $inspection->brake['rear']->brakeeff ?? 'N/A' }}</td>
                <td><strong>{{ $inspection->brake['summary']->tolbrakeeff }}</strong></td>
                <td class="{{ $inspection->brake['summary']->stsbrakeeff == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->brake['summary']->stsbrakeeff == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
            <tr>
                <td>Handbrake Efficiency (%)</td>
                <td>{{ $inspection->brake['front']->handbrakeeff ?? 'N/A' }}</td>
                <td>{{ $inspection->brake['rear']->handbrakeeff ?? 'N/A' }}</td>
                <td><strong>{{ $inspection->brake['summary']->tolhandbrakeeff }}</strong></td>
                <td class="{{ $inspection->brake['summary']->stshandbrakeeff == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->brake['summary']->stshandbrakeeff == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
        </table>
        @endif
        
        <!-- Emission Test -->
        @if($inspection->emission)
        <h4 style="margin: 15px 0 10px 0; font-size: 13px;">Emission Test</h4>
        <table>
            <tr>
                <th>Parameter</th>
                <th>Idle</th>
                <th>High Speed</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>HC (ppm)</td>
                <td>{{ $inspection->emission->idlhcaverage }}</td>
                <td>{{ $inspection->emission->hghhcaverage ?? 'N/A' }}</td>
                <td class="{{ $inspection->emission->stsidlhc == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->emission->stsidlhc == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
            <tr>
                <td>CO (%)</td>
                <td>{{ $inspection->emission->idlcoaverage }}</td>
                <td>{{ $inspection->emission->hghcoaverage ?? 'N/A' }}</td>
                <td class="{{ $inspection->emission->stsidlco == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->emission->stsidlco == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
        </table>
        @endif
        
        <!-- Headlamp Test -->
        @if($inspection->headlamp['left'] || $inspection->headlamp['right'])
        <h4 style="margin: 15px 0 10px 0; font-size: 13px;">Headlamp Test</h4>
        <table>
            <tr>
                <th>Side</th>
                <th>Light Intensity (cd)</th>
                <th>Horizontal Offset</th>
                <th>Vertical Offset</th>
                <th>Status</th>
            </tr>
            @if($inspection->headlamp['left'])
            <tr>
                <td>Left</td>
                <td>{{ $inspection->headlamp['left']->lightintensity }}</td>
                <td>{{ $inspection->headlamp['left']->offsetlrnear }}</td>
                <td>{{ $inspection->headlamp['left']->offsetudnear }}</td>
                <td class="{{ $inspection->headlamp['left']->stslightintensity == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->headlamp['left']->stslightintensity == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
            @endif
            @if($inspection->headlamp['right'])
            <tr>
                <td>Right</td>
                <td>{{ $inspection->headlamp['right']->lightintensity }}</td>
                <td>{{ $inspection->headlamp['right']->offsetlrnear }}</td>
                <td>{{ $inspection->headlamp['right']->offsetudnear }}</td>
                <td class="{{ $inspection->headlamp['right']->stslightintensity == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->headlamp['right']->stslightintensity == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
            @endif
        </table>
        @endif
        
        <!-- Suspension Test -->
        @if($inspection->suspension['front'] || $inspection->suspension['rear'])
        <h4 style="margin: 15px 0 10px 0; font-size: 13px;">Suspension Test</h4>
        <table>
            <tr>
                <th>Axle</th>
                <th>Efficiency (%)</th>
                <th>Differential (%)</th>
                <th>Status</th>
            </tr>
            @if($inspection->suspension['front'])
            <tr>
                <td>Front</td>
                <td>{{ $inspection->suspension['front']->suspensioneff }}</td>
                <td>{{ $inspection->suspension['front']->suspensiondiff }}</td>
                <td class="{{ $inspection->suspension['front']->stssuspensioneff == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->suspension['front']->stssuspensioneff == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
            @endif
            @if($inspection->suspension['rear'])
            <tr>
                <td>Rear</td>
                <td>{{ $inspection->suspension['rear']->suspensioneff }}</td>
                <td>{{ $inspection->suspension['rear']->suspensiondiff }}</td>
                <td class="{{ $inspection->suspension['rear']->stssuspensioneff == '1' ? 'status-pass' : 'status-fail' }}">
                    {{ $inspection->suspension['rear']->stssuspensioneff == '1' ? 'PASS' : 'FAIL' }}
                </td>
            </tr>
            @endif
        </table>
        @endif
    </div>
    
    <!-- Visual Inspection Defects -->
    @if($inspection->visual->count() > 0 || $inspection->pit->count() > 0)
    <div class="section">
        <div class="section-title">DEFECTS IDENTIFIED</div>
        
        @if($inspection->visual->count() > 0)
        <h4 style="margin: 15px 0 10px 0; font-size: 13px;">Visual Inspection</h4>
        <table>
            <tr>
                <th>Code</th>
                <th>Category</th>
                <th>Description</th>
            </tr>
            @foreach($inspection->visual as $defect)
            <tr>
                <td>{{ $defect->defectcode }}</td>
                <td>{{ $defect->category }}</td>
                <td>{{ $defect->description }}</td>
            </tr>
            @endforeach
        </table>
        @endif
        
        @if($inspection->pit->count() > 0)
        <h4 style="margin: 15px 0 10px 0; font-size: 13px;">Pit Inspection</h4>
        <table>
            <tr>
                <th>Code</th>
                <th>Category</th>
                <th>Description</th>
            </tr>
            @foreach($inspection->pit as $defect)
            <tr>
                <td>{{ $defect->defectcode }}</td>
                <td>{{ $defect->category }}</td>
                <td>{{ $defect->description }}</td>
            </tr>
            @endforeach
        </table>
        @endif
    </div>
    @endif
    
    <!-- Overall Result -->
    @php $passed = in_array($inspection->base->testresult, ['Y', '1']); @endphp
    <div class="result-box {{ $passed ? 'result-pass' : 'result-fail' }}">
        INSPECTION RESULT: {{ $passed ? 'PASSED' : 'FAILED' }}
        <br>
        <span style="font-size: 14px;">{{ $inspection->base->conclusion }}</span>
    </div>

    @if($passed)
    <div style="background-color: #f0fdf4; border: 1px solid #22c55e; padding: 15px; margin: 20px 0;">
        <p style="margin: 0; font-weight: bold;">Certificate Valid Until:</p>
        <p style="margin: 5px 0 0 0; font-size: 16px;">
            {{ \Carbon\Carbon::parse($inspection->base->inspectdate)->addYear()->format('F d, Y') }}
        </p>
    </div>
    @endif
    
    <!-- Signatures -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                Inspector's Signature
            </div>
            <p style="margin-top: 5px; font-size: 11px;">{{ $inspection->base->inspector ?? 'N/A' }}</p>
        </div>
        
        <div class="signature-box">
            <div class="signature-line">
                Supervisor's Signature
            </div>
            <p style="margin-top: 5px; font-size: 11px;">Date: {{ \Carbon\Carbon::parse($inspection->base->inspectdate)->format('d/m/Y') }}</p>
        </div>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p style="text-align: center; font-size: 10px; color: #666;">
            This is an official document issued by {{ $inspection->base->department_name ?? 'Vehicle Inspection Service' }}<br>
            For verification, visit our website or scan the QR code above<br>
            Report No: {{ $inspection->base->seriesno }} | Generated: {{ \Carbon\Carbon::now()->format('F d, Y H:i') }}
        </p>
    </div>
</body>
</html>
