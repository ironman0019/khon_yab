<?php

namespace App\Exports\Reports;

use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApprovedRequestsReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getApprovedRequestsReport($this->filters);
        $requests = $report['requests'];

        return $requests->map(function ($request) {
            return [
                'ID' => $request->id,
                'Requested By' => $request->requestedBy->full_name ?? 'N/A',
                'Blood Type' => $request->blood_type,
                'RH Factor' => $request->rh_factor,
                'Number of Bags' => $request->number_of_bags,
                'Province' => $request->province->name ?? 'N/A',
                'City' => $request->city->name ?? 'N/A',
                'Hospital Name' => $request->hospital_name ?? 'N/A',
                'Approval Date' => $request->approval_date ? $request->approval_date->format('Y-m-d H:i:s') : 'N/A',
                'Approved By' => $request->approvedBy->full_name ?? 'N/A',
                'Notes' => $request->notes ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Requested By',
            'Blood Type',
            'RH Factor',
            'Number of Bags',
            'Province',
            'City',
            'Hospital Name',
            'Approval Date',
            'Approved By',
            'Notes',
        ];
    }
}

