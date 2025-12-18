<?php

namespace App\Exports\Reports;

use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserStatisticsReportExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getUserStatisticsReport();

        $data = collect([
            ['Metric' => 'Total Users', 'Value' => $report['total_users']],
            ['Metric' => 'Total Donors', 'Value' => $report['total_donors']],
            ['Metric' => 'Active Donors', 'Value' => $report['active_donors']],
            ['Metric' => 'Healthy Donors', 'Value' => $report['healthy_donors']],
        ]);

        // Add donors by blood type
        foreach ($report['donors_by_blood_type'] as $bloodType => $count) {
            $data->push(['Metric' => "Donors - Blood Type {$bloodType}", 'Value' => $count]);
        }

        // Add donors by province
        foreach ($report['donors_by_province'] as $province => $count) {
            $data->push(['Metric' => "Donors - Province {$province}", 'Value' => $count]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Metric',
            'Value',
        ];
    }
}

