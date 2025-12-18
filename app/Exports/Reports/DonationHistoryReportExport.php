<?php

namespace App\Exports\Reports;

use App\Enums\DonationRecordStatus;
use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DonationHistoryReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getDonationHistoryByDonor($this->filters);
        $donations = $report['donations'];

        $statusLabels = [
            DonationRecordStatus::TestPending->value => 'Test Pending',
            DonationRecordStatus::Safe->value => 'Safe',
            DonationRecordStatus::Unsafe->value => 'Unsafe',
            DonationRecordStatus::Discarded->value => 'Discarded',
        ];
        $donationTypeLabels = [
            0 => 'Whole Blood',
            1 => 'Plasma',
            2 => 'Platelets',
        ];

        return $donations->map(function ($donation) use ($statusLabels, $donationTypeLabels) {
            return [
                'ID' => $donation->id,
                'Donor Name' => $donation->donor->user->full_name ?? 'N/A',
                'Donor Mobile' => $donation->donor->mobile_number ?? 'N/A',
                'Blood Type' => $donation->donor->blood_type ?? 'N/A',
                'Donation Type' => $donationTypeLabels[$donation->donation_type] ?? 'N/A',
                'Amount (ml)' => $donation->amount_ml,
                'Donation Date' => $donation->donation_date ? $donation->donation_date->format('Y-m-d') : 'N/A',
                'Expiration Date' => $donation->expiration_date ? $donation->expiration_date->format('Y-m-d') : 'N/A',
                'Status' => $statusLabels[$donation->status] ?? 'Unknown',
                'Recorded By' => $donation->recordedByAdmin->full_name ?? 'N/A',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Donor Name',
            'Donor Mobile',
            'Blood Type',
            'Donation Type',
            'Amount (ml)',
            'Donation Date',
            'Expiration Date',
            'Status',
            'Recorded By',
        ];
    }
}

