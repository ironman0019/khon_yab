<?php

namespace App\Exports\Reports;

use App\Models\Province;
use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsByProvinceExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected int $provinceId,
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getReportsByProvince($this->provinceId, $this->filters);
        $province = $report['province'];

        if (!$province) {
            return collect([]);
        }

        $data = collect([
            ['Section' => 'Province Information', 'Field' => 'Province Name', 'Value' => $province->name],
            ['Section' => 'Donors', 'Field' => 'Total Donors', 'Value' => $report['donors']['total']],
            ['Section' => 'Donors', 'Field' => 'Active Donors', 'Value' => $report['donors']['active']],
            ['Section' => 'Donors', 'Field' => 'Healthy Donors', 'Value' => $report['donors']['healthy']],
        ]);

        foreach ($report['donors']['by_blood_type'] as $bloodType => $count) {
            $data->push(['Section' => 'Donors', 'Field' => "Blood Type {$bloodType}", 'Value' => $count]);
        }

        $data->push(['Section' => 'Requests', 'Field' => 'Total Requests', 'Value' => $report['requests']['total']]);
        $data->push(['Section' => 'Requests', 'Field' => 'Total Bags Requested', 'Value' => $report['requests']['total_bags']]);

        foreach ($report['requests']['by_status'] as $status => $count) {
            $data->push(['Section' => 'Requests', 'Field' => "Status {$status}", 'Value' => $count]);
        }

        $data->push(['Section' => 'Inventory', 'Field' => 'Total Inventory', 'Value' => $report['inventory']['total']]);
        $data->push(['Section' => 'Inventory', 'Field' => 'In Stock', 'Value' => $report['inventory']['in_stock']]);

        foreach ($report['inventory']['by_blood_type'] as $bloodType => $count) {
            $data->push(['Section' => 'Inventory', 'Field' => "Blood Type {$bloodType}", 'Value' => $count]);
        }

        $data->push(['Section' => 'Donations', 'Field' => 'Total Donations', 'Value' => $report['donations']['total']]);
        $data->push(['Section' => 'Donations', 'Field' => 'Total Amount (ml)', 'Value' => $report['donations']['total_amount_ml']]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'Section',
            'Field',
            'Value',
        ];
    }
}

