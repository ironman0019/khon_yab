@php
    $isRtl = in_array(app()->getLocale(), ['fa', 'ps']);
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('laboratory.Blood Request Receipt') }} #{{ $bloodRequest->id }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            padding: 40px;
            background: #f9fafb;
        }
        .receipt {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 10px;
        }
        .header p {
            color: #6b7280;
            font-size: 14px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .info-item {
            margin-bottom: 15px;
        }
        .info-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #111827;
            font-weight: 500;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }
        .status-completed {
            background: #e9d5ff;
            color: #6b21a8;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .receipt {
                box-shadow: none;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>{{ __('laboratory.Blood Request Receipt') }}</h1>
            <p>{{ __('laboratory.Request Confirmation') }}</p>
        </div>

        <div class="section">
            <div class="section-title">{{ __('laboratory.Request Information') }}</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Request ID') }}</div>
                    <div class="info-value">#{{ $bloodRequest->id }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Requested On') }}</div>
                    <div class="info-value">{{ $bloodRequest->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Status') }}</div>
                    <div class="info-value">
                        @if($bloodRequest->status == 1)
                            <span class="status-badge status-approved">{{ __('laboratory.Approved') }}</span>
                        @elseif($bloodRequest->status == 3)
                            <span class="status-badge status-completed">{{ __('laboratory.Completed') }}</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Print Date') }}</div>
                    <div class="info-value">{{ now()->format('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">{{ __('laboratory.Patient Information') }}</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Patient Name') }}</div>
                    <div class="info-value">{{ $bloodRequest->patient_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Patient Age') }}</div>
                    <div class="info-value">{{ $bloodRequest->patient_age }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Contact Number') }}</div>
                    <div class="info-value">{{ $bloodRequest->contact_number }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">{{ __('laboratory.Blood Information') }}</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Blood Type') }}</div>
                    <div class="info-value">{{ $bloodRequest->blood_type }}{{ $bloodRequest->rh_factor === 'positive' ? '+' : '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Number of Bags') }}</div>
                    <div class="info-value">{{ $bloodRequest->number_of_bags }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">{{ __('laboratory.Location Information') }}</div>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Province') }}</div>
                    <div class="info-value">{{ $bloodRequest->province->name ?? __('laboratory.Not Available') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.City') }}</div>
                    <div class="info-value">{{ $bloodRequest->city->name ?? __('laboratory.Not Available') }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">{{ __('laboratory.Medical Center') }}</div>
                    <div class="info-value">{{ $bloodRequest->medical_center }}</div>
                </div>
            </div>
        </div>

        @if($bloodRequest->approvedBy)
            <div class="section">
                <div class="section-title">{{ __('laboratory.Approval Information') }}</div>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">{{ __('laboratory.Approved By') }}</div>
                        <div class="info-value">{{ $bloodRequest->approvedBy->full_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">{{ __('laboratory.Approval Date') }}</div>
                        <div class="info-value">{{ $bloodRequest->approval_date ? $bloodRequest->approval_date->format('Y-m-d H:i') : __('laboratory.Not Available') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="footer">
            <p>{{ __('laboratory.This is an official receipt for the blood request.') }}</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px;">
            {{ __('laboratory.Print') }}
        </button>
    </div>
</body>
</html>

