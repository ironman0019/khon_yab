<?php

namespace App\Exports\Reports;

use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SummaryReportExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getSummaryReport();

        return collect([
            ['Category' => 'Donations', 'Metric' => 'Today', 'Value' => $report['donations']['today']],
            ['Category' => 'Donations', 'Metric' => 'This Month', 'Value' => $report['donations']['this_month']],
            ['Category' => 'Donations', 'Metric' => 'Last Month', 'Value' => $report['donations']['last_month']],
            ['Category' => 'Requests', 'Metric' => 'Pending', 'Value' => $report['requests']['pending']],
            ['Category' => 'Requests', 'Metric' => 'Approved', 'Value' => $report['requests']['approved']],
            ['Category' => 'Requests', 'Metric' => 'Completed', 'Value' => $report['requests']['completed']],
            ['Category' => 'Inventory', 'Metric' => 'In Stock', 'Value' => $report['inventory']['in_stock']],
            ['Category' => 'Inventory', 'Metric' => 'Expired', 'Value' => $report['inventory']['expired']],
            ['Category' => 'Inventory', 'Metric' => 'Used', 'Value' => $report['inventory']['used']],
            ['Category' => 'Users', 'Metric' => 'Total', 'Value' => $report['users']['total']],
            ['Category' => 'Users', 'Metric' => 'Donors', 'Value' => $report['users']['donors']],
        ]);
    }

    public function headings(): array
    {
        return [
            'Category',
            'Metric',
            'Value',
        ];
    }
}

