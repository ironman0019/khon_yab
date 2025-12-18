<?php

namespace App\Exports\Reports;

use App\Enums\BloodInventoryStatus;
use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BagExpirationReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getBagExpirationReport($this->filters);
        $inventory = $report['inventory'];

        $statusLabels = [
            BloodInventoryStatus::InStock->value => 'In Stock',
            BloodInventoryStatus::Used->value => 'Used',
            BloodInventoryStatus::Expired->value => 'Expired',
            BloodInventoryStatus::Discarded->value => 'Discarded',
        ];

        return $inventory->map(function ($item) use ($statusLabels) {
            $today = now()->startOfDay();
            $expirationStatus = 'Valid';
            if ($item->expiration_date) {
                if ($item->expiration_date->lt($today)) {
                    $expirationStatus = 'Expired';
                } elseif ($item->expiration_date->lte($today->copy()->addDays(7))) {
                    $expirationStatus = 'Expiring Soon';
                }
            }

            return [
                'Bag ID' => $item->bag_id,
                'Blood Type' => $item->blood_type,
                'RH Factor' => $item->rh_factor,
                'Donor Name' => $item->bloodDonationRecord->donor->user->full_name ?? 'N/A',
                'Province' => $item->province->name ?? 'N/A',
                'Entry Date' => $item->entry_date ? $item->entry_date->format('Y-m-d') : 'N/A',
                'Expiration Date' => $item->expiration_date ? $item->expiration_date->format('Y-m-d') : 'N/A',
                'Expiration Status' => $expirationStatus,
                'Inventory Status' => $statusLabels[$item->status] ?? 'Unknown',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Bag ID',
            'Blood Type',
            'RH Factor',
            'Donor Name',
            'Province',
            'Entry Date',
            'Expiration Date',
            'Expiration Status',
            'Inventory Status',
        ];
    }
}

