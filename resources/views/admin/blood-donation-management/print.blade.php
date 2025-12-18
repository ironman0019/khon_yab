<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('Blood Donation Receipt') }} #{{ $donationRecord->id }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #dc2626;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #dc2626;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .receipt-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        .info-section h2 {
            margin: 0 0 15px 0;
            color: #dc2626;
            font-size: 18px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #10b981;
            color: white;
        }
        .badge-warning {
            background-color: #f59e0b;
            color: white;
        }
        .badge-danger {
            background-color: #ef4444;
            color: white;
        }
        .badge-info {
            background-color: #3b82f6;
            color: white;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #eee;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .notes-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9fafb;
            border-left: 4px solid #dc2626;
        }
        .notes-section h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .button-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #dc2626;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .button-print:hover {
            background-color: #b91c1c;
        }
    </style>
    <script>
        window.onload = function() {
            // Auto-print can be enabled by uncommenting the line below
            // window.print();
        };
    </script>
</head>
<body>
    <!-- Print Button -->
    <button onclick="window.print()" class="button-print no-print">{{ __('Print Receipt') }}</button>

    <!-- Receipt Header -->
    <div class="header">
        <h1>{{ __('Blood Donation Receipt') }}</h1>
        <p>{{ __('Donation Record') }} #{{ $donationRecord->id }}</p>
        <p>{{ __('Date') }}: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <!-- Receipt Information -->
    <div class="receipt-info">
        <!-- Donation Information -->
        <div class="info-section">
            <h2>{{ __('Donation Information') }}</h2>
            <div class="info-row">
                <span class="info-label">{{ __('Record ID') }}:</span>
                <span class="info-value">#{{ $donationRecord->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Donation Type') }}:</span>
                <span class="info-value">
                    @php
                        $donationTypes = [
                            0 => __('Whole Blood'),
                            1 => __('Plasma'),
                            2 => __('Platelets'),
                        ];
                    @endphp
                    {{ $donationTypes[$donationRecord->donation_type] ?? __('Unknown') }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Amount') }}:</span>
                <span class="info-value">{{ $donationRecord->amount_ml }} ml</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Donation Date') }}:</span>
                <span class="info-value">{{ $donationRecord->donation_date->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Expiration Date') }}:</span>
                <span class="info-value">{{ $donationRecord->expiration_date->format('Y-m-d') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Status') }}:</span>
                <span class="info-value">
                    @php
                        $statusLabels = [
                            0 => __('Test Pending'),
                            1 => __('Safe'),
                            2 => __('Unsafe'),
                            3 => __('Discarded'),
                        ];
                    @endphp
                    <span class="badge 
                        @if($donationRecord->status == 1) badge-success
                        @elseif($donationRecord->status == 0) badge-warning
                        @elseif($donationRecord->status == 2 || $donationRecord->status == 3) badge-danger
                        @else badge-info
                        @endif
                    ">
                        {{ $statusLabels[$donationRecord->status] ?? __('Unknown') }}
                    </span>
                </span>
            </div>
        </div>

        <!-- Donor Information -->
        <div class="info-section">
            <h2>{{ __('Donor Information') }}</h2>
            <div class="info-row">
                <span class="info-label">{{ __('Name') }}:</span>
                <span class="info-value">{{ $donationRecord->donor->user->full_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Email') }}:</span>
                <span class="info-value">{{ $donationRecord->donor->user->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">{{ __('Blood Type') }}:</span>
                <span class="info-value">{{ $donationRecord->donor->blood_type }}{{ $donationRecord->donor->rh_factor == 'positive' ? '+' : '-' }}</span>
            </div>
            @if($donationRecord->province || $donationRecord->city)
                <div class="info-row">
                    <span class="info-label">{{ __('Location') }}:</span>
                    <span class="info-value">
                        @if($donationRecord->city)
                            {{ $donationRecord->city->name }}, 
                        @endif
                        {{ $donationRecord->province->name ?? '' }}
                    </span>
                </div>
            @endif
            <div class="info-row">
                <span class="info-label">{{ __('Recorded By') }}:</span>
                <span class="info-value">{{ $donationRecord->recordedByAdmin->full_name ?? __('System') }}</span>
            </div>
        </div>
    </div>

    <!-- Inventory Information -->
    @if($donationRecord->bloodInventory->isNotEmpty())
        <div class="info-section" style="margin-top: 20px;">
            <h2>{{ __('Inventory Information') }}</h2>
            @foreach($donationRecord->bloodInventory as $inventory)
                <div class="info-row">
                    <span class="info-label">{{ __('Bag ID') }}:</span>
                    <span class="info-value">{{ $inventory->bag_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('Status') }}:</span>
                    <span class="info-value">
                        @php
                            $inventoryStatusLabels = [
                                0 => __('In Stock'),
                                1 => __('Used'),
                                2 => __('Expired'),
                                3 => __('Discarded'),
                            ];
                        @endphp
                        {{ $inventoryStatusLabels[$inventory->status] ?? __('Unknown') }}
                    </span>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Notes -->
    @if($donationRecord->notes)
        <div class="notes-section">
            <h3>{{ __('Notes') }}</h3>
            <p style="margin: 0; white-space: pre-wrap;">{{ $donationRecord->notes }}</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>{{ __('This is an official receipt for blood donation record') }} #{{ $donationRecord->id }}</p>
        <p>{{ __('Generated on') }} {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>

