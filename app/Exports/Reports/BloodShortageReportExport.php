<?php

namespace App\Exports\Reports;

use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BloodShortageReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getBloodShortageReport($this->filters);
        $shortages = $report['shortages'];

        $data = collect();
        foreach ($shortages as $key => $shortage) {
            $data->push([
                'Blood Type' => $shortage['blood_type'],
                'RH Factor' => $shortage['rh_factor'],
                'Current Count' => $shortage['current_count'],
                'Threshold' => $shortage['threshold'],
                'Shortage' => $shortage['shortage'],
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Blood Type',
            'RH Factor',
            'Current Count',
            'Threshold',
            'Shortage',
        ];
    }
}

