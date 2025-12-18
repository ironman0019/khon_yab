<?php

namespace App\Exports\Reports;

use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyYearlyReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getMonthlyYearlyReport($this->filters);
        $data = $report['data'];

        $collection = collect();
        foreach ($data as $periodData) {
            $collection->push([
                'Period' => $periodData['period'],
                'Donations Count' => $periodData['donations']['count'],
                'Donations Total (ml)' => $periodData['donations']['total_amount_ml'],
                'Requests Count' => $periodData['requests']['count'],
                'Requests Approved' => $periodData['requests']['approved'],
                'Requests Total Bags' => $periodData['requests']['total_bags'],
                'Inventory Entries' => $periodData['inventory']['entries'],
                'Inventory In Stock' => $periodData['inventory']['in_stock'],
            ]);
        }

        return $collection;
    }

    public function headings(): array
    {
        return [
            'Period',
            'Donations Count',
            'Donations Total (ml)',
            'Requests Count',
            'Requests Approved',
            'Requests Total Bags',
            'Inventory Entries',
            'Inventory In Stock',
        ];
    }
}

