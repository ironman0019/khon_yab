<?php

namespace App\Exports\Reports;

use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActiveDonorsReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getActiveDonorsReport($this->filters);
        $donors = $report['donors'];

        return $donors->map(function ($donor) {
            return [
                'ID' => $donor->id,
                'Donor Name' => $donor->user->full_name ?? 'N/A',
                'Email' => $donor->user->email ?? 'N/A',
                'Mobile' => $donor->mobile_number,
                'Blood Type' => $donor->blood_type,
                'RH Factor' => $donor->rh_factor,
                'Province' => $donor->province->name ?? 'N/A',
                'City' => $donor->city->name ?? 'N/A',
                'Last Donation Date' => $donor->last_donation_date ? $donor->last_donation_date->format('Y-m-d') : 'Never',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Donor Name',
            'Email',
            'Mobile',
            'Blood Type',
            'RH Factor',
            'Province',
            'City',
            'Last Donation Date',
        ];
    }
}

