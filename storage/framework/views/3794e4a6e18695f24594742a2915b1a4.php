<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspection Certificate — <?php echo e($inspection->plateno); ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            background: #e5e7eb;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 30px 16px;
        }

        .page {
            background: #fff;
            width: 760px;
            border: 1px solid #ccc;
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }

        /* ribbons */
        .ribbon-top, .ribbon-bottom { background: #1f3c88; height: 10px; }

        /* header */
        .cert-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 24px 40px 20px;
            border-bottom: 3px solid #1f3c88;
            gap: 12px;
        }
        .logo-circle {
            width: 60px; height: 60px; border-radius: 50%;
            background: #1f3c88;
            display: flex; align-items: center; justify-content: center;
            font-size: 26px; color: #fff; flex-shrink: 0;
        }
        .logo-area { display: flex; align-items: center; gap: 12px; }
        .org-name h2 { font-size: 14px; color: #1f3c88; font-weight: 700; text-transform: uppercase; }
        .org-name p  { font-size: 11px; color: #555; margin-top: 2px; }
        .cert-title  { text-align: center; }
        .cert-title h1 { font-size: 19px; color: #1f3c88; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; line-height: 1.25; }
        .cert-title p  { font-size: 11px; color: #666; margin-top: 4px; }
        .cert-no { text-align: right; font-size: 11px; color: #555; }
        .cert-no strong { display: block; font-size: 13px; color: #1f3c88; }

        /* result banner */
        .result-banner {
            padding: 18px 40px;
            display: flex; align-items: center; justify-content: center; gap: 14px;
            font-size: 28px; font-weight: 800; letter-spacing: 2px;
            border-bottom: 3px solid;
        }
        .result-banner.passed { background: #f0fdf4; color: #15803d; border-color: #16a34a; }
        .result-banner.failed { background: #fff1f2; color: #b91c1c; border-color: #dc2626; }
        .result-banner .icon { font-size: 34px; }

        /* body */
        .cert-body { padding: 28px 40px; }

        .section { margin-bottom: 22px; }
        .section-heading {
            background: #1f3c88; color: #fff;
            padding: 6px 14px; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .6px; margin-bottom: 10px;
        }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px 24px; }
        .span-2 { grid-column: span 2; }
        .span-3 { grid-column: span 3; }

        .field .label {
            font-size: 10px; color: #1f3c88; font-weight: 700;
            text-transform: uppercase; letter-spacing: .4px; margin-bottom: 2px;
        }
        .field .value {
            font-size: 13px; color: #111;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 3px; min-height: 20px;
        }

        /* validity / remedial */
        .validity-box {
            background: #f0fdf4; border: 2px solid #16a34a; border-radius: 6px;
            padding: 12px 20px; text-align: center; margin-bottom: 22px;
        }
        .validity-box .val-label { font-size: 11px; color: #15803d; font-weight: 700; text-transform: uppercase; }
        .validity-box .val-date  { font-size: 22px; font-weight: 800; color: #15803d; margin-top: 4px; }
        .validity-box p { font-size: 11px; color: #166534; margin-top: 4px; }

        .remedial-box {
            background: #fff1f2; border: 2px solid #dc2626; border-radius: 6px;
            padding: 14px 20px; margin-bottom: 22px;
        }
        .remedial-box .rem-title { font-size: 12px; color: #b91c1c; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .remedial-box p { font-size: 12px; color: #7f1d1d; line-height: 1.5; }

        /* conclusion */
        .conclusion-box {
            border: 1px solid #d1d5db; border-radius: 4px;
            padding: 10px 14px; font-size: 13px; color: #374151;
            min-height: 40px; background: #f9fafb;
        }

        /* signatures */
        .sig-row { display: flex; justify-content: space-between; gap: 20px; margin-top: 34px; }
        .sig-box { flex: 1; text-align: center; }
        .sig-box .sig-line {
            border-top: 1px solid #374151; margin-top: 50px;
            padding-top: 6px; font-size: 11px; color: #555;
        }
        .sig-box .sig-name { font-size: 12px; font-weight: 700; color: #1f3c88; margin-top: 2px; }

        /* footer */
        .cert-footer {
            border-top: 1px solid #e5e7eb; padding: 12px 40px;
            display: flex; justify-content: space-between; align-items: center;
            background: #f9fafb; font-size: 10px; color: #6b7280;
        }

        /* print bar */
        .print-bar {
            width: 760px; text-align: center;
            padding: 16px 0 24px; display: flex; justify-content: center; gap: 10px;
        }
        .print-bar button {
            padding: 9px 24px; border: none; border-radius: 5px;
            font-size: 14px; cursor: pointer; color: #fff;
        }
        .btn-print  { background: #1f3c88; }
        .btn-back   { background: #6b7280; }

        @media print {
            body { background: #fff; padding: 0; }
            .page { box-shadow: none; border: none; width: 100%; }
            .print-bar { display: none; }
        }
    </style>
</head>
<body>

<div class="print-bar">
    <button class="btn-print" onclick="window.print()">&#128438; Print Certificate</button>
    <button class="btn-back"  onclick="window.history.back()">&#8592; Back</button>
</div>

<div class="page">
    <div class="ribbon-top"></div>

    
    <div class="cert-header">
        <div class="logo-area">
            <div class="logo-circle">&#128663;</div>
            <div class="org-name">
                <h2>Vehicle Inspection Service</h2>
                <p>Federal Republic of Nigeria</p>
                <?php if($inspection->department_name): ?>
                <p><?php echo e($inspection->department_name); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <div class="cert-title">
            <h1>Vehicle Inspection<br>Certificate</h1>
            <p>Official Document &mdash; Not Transferable</p>
        </div>

        <div class="cert-no">
            <strong><?php echo e(str_pad($inspection->seriesno ?? $inspection->id, 10, '0', STR_PAD_LEFT)); ?></strong>
            Issued: <?php echo e(now()->format('d M Y')); ?>

        </div>
    </div>

    
    <div class="result-banner <?php echo e($passed ? 'passed' : 'failed'); ?>">
        <span class="icon"><?php echo e($passed ? '&#10004;' : '&#10008;'); ?></span>
        INSPECTION &nbsp; <?php echo e($passed ? 'PASSED' : 'FAILED'); ?>

    </div>

    <div class="cert-body">

        
        <div class="section">
            <div class="section-heading">Vehicle Information</div>
            <div class="grid-3">
                <div class="field">
                    <div class="label">Plate Number</div>
                    <div class="value"><?php echo e(strtoupper(trim($inspection->plateno ?? '—'))); ?></div>
                </div>
                <div class="field">
                    <div class="label">Vehicle Type</div>
                    <div class="value"><?php echo e($inspection->vehicletype ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Licence Type</div>
                    <div class="value"><?php echo e($inspection->licencetype ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Make of Vehicle</div>
                    <div class="value"><?php echo e($inspection->makeofvehicle ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Model</div>
                    <div class="value"><?php echo e($inspection->model ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Fuel Type</div>
                    <div class="value">
                        <?php
                            $fuels = ['1'=>'Petrol','2'=>'Diesel','3'=>'Electric','P'=>'Petrol','D'=>'Diesel','G'=>'Gas','E'=>'Electric'];
                        ?>
                        <?php echo e($fuels[$inspection->fueltype ?? ''] ?? ($inspection->fueltype ?? '—')); ?>

                    </div>
                </div>
                <div class="field">
                    <div class="label">Engine Number</div>
                    <div class="value"><?php echo e($inspection->engineno ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Chassis Number</div>
                    <div class="value"><?php echo e($inspection->chassisno ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Net Weight (kg)</div>
                    <div class="value"><?php echo e($inspection->netweight ?? '—'); ?></div>
                </div>
            </div>
        </div>

        
        <div class="section">
            <div class="section-heading">Owner Information</div>
            <div class="grid-2">
                <div class="field">
                    <div class="label">Owner Name</div>
                    <div class="value"><?php echo e($inspection->owner ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Phone Number</div>
                    <div class="value"><?php echo e($inspection->phoneno ?? '—'); ?></div>
                </div>
                <div class="field span-2">
                    <div class="label">Address</div>
                    <div class="value"><?php echo e($inspection->address ?? '—'); ?></div>
                </div>
            </div>
        </div>

        
        <div class="section">
            <div class="section-heading">Inspection Details</div>
            <div class="grid-3">
                <div class="field">
                    <div class="label">Inspection Date</div>
                    <div class="value">
                        <?php
                            try { echo \Carbon\Carbon::parse($inspection->inspectdate)->format('d M Y'); }
                            catch(\Exception $e) { echo $inspection->inspectdate ?? '—'; }
                        ?>
                    </div>
                </div>
                <div class="field">
                    <div class="label">Inspection Times</div>
                    <div class="value"><?php echo e($inspection->inspecttimes ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Series No</div>
                    <div class="value"><?php echo e($inspection->seriesno ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Inspector</div>
                    <div class="value"><?php echo e($inspection->inspector ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Station / Department</div>
                    <div class="value"><?php echo e($inspection->department_name ?? '—'); ?></div>
                </div>
                <div class="field">
                    <div class="label">Result Code</div>
                    <div class="value" style="font-weight:700;color:<?php echo e($passed ? '#15803d' : '#b91c1c'); ?>">
                        <?php echo e($inspection->testresult); ?> &mdash; <?php echo e($passed ? 'PASSED' : 'FAILED'); ?>

                    </div>
                </div>
            </div>
        </div>

        <?php if($inspection->conclusion): ?>
        <div class="section">
            <div class="section-heading">Conclusion / Remarks</div>
            <div class="conclusion-box"><?php echo e($inspection->conclusion); ?></div>
        </div>
        <?php endif; ?>

        
        <?php if($passed): ?>
        <div class="validity-box">
            <div class="val-label">Certificate Valid Until</div>
            <div class="val-date">
                <?php
                    try { echo \Carbon\Carbon::parse($inspection->inspectdate)->addYear()->format('d M Y'); }
                    catch(\Exception $e) { echo '12 months from inspection date'; }
                ?>
            </div>
            <p>This vehicle has met all statutory safety inspection requirements.</p>
        </div>
        <?php else: ?>
        <div class="remedial-box">
            <div class="rem-title">&#9888; Remedial Action Required</div>
            <p>This vehicle has <strong>failed</strong> the statutory safety inspection. The owner is required to
            address all identified defects and present the vehicle for re-inspection. The vehicle must
            <strong>not</strong> be operated on public roads until it has passed a re-inspection.</p>
        </div>
        <?php endif; ?>

        
        <div class="sig-row">
            <div class="sig-box">
                <div class="sig-line">Inspector's Signature</div>
                <div class="sig-name"><?php echo e($inspection->inspector ?? ''); ?></div>
            </div>
            <div class="sig-box">
                <div class="sig-line">Officer-in-Charge</div>
            </div>
            <div class="sig-box">
                <div class="sig-line">Official Stamp</div>
            </div>
        </div>

    </div>

    <div class="cert-footer">
        <span>Cert&nbsp;No:&nbsp;<?php echo e(str_pad($inspection->seriesno ?? $inspection->id, 10, '0', STR_PAD_LEFT)); ?></span>
        <span>Vehicle Inspection Service &mdash; Federal Republic of Nigeria</span>
        <span>Generated:&nbsp;<?php echo e(now()->format('d M Y H:i')); ?></span>
    </div>
    <div class="ribbon-bottom"></div>
</div>

</body>
</html>
<?php /**PATH C:\Users\talk2\OneDrive\Desktop\inspection\resources\views/inspections/certificate.blade.php ENDPATH**/ ?>