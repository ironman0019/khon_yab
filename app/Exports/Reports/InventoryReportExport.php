<?php

namespace App\Exports\Reports;

use App\Enums\BloodInventoryStatus;
use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getInventoryReport($this->filters);
        $inventory = $report['inventory'];

        return $inventory->map(function ($item) {
            $statusLabels = [
                BloodInventoryStatus::InStock->value => 'In Stock',
                BloodInventoryStatus::Used->value => 'Used',
                BloodInventoryStatus::Expired->value => 'Expired',
                BloodInventoryStatus::Discarded->value => 'Discarded',
            ];

            return [
                'Bag ID' => $item->bag_id,
                'Blood Type' => $item->blood_type,
                'RH Factor' => $item->rh_factor,
                'Donor Name' => $item->bloodDonationRecord->donor->user->full_name ?? 'N/A',
                'Province' => $item->province->name ?? 'N/A',
                'Entry Date' => $item->entry_date ? $item->entry_date->format('Y-m-d') : 'N/A',
                'Expiration Date' => $item->expiration_date ? $item->expiration_date->format('Y-m-d') : 'N/A',
                'Status' => $statusLabels[$item->status] ?? 'Unknown',
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
            'Status',
        ];
    }
}

