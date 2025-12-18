<?php

namespace App\Exports\Reports;

use App\Enums\BloodRequestStatus;
use App\Services\Admin\ReportsService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BloodRequestsReportExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected array $filters
    ) {}

    public function collection(): Collection
    {
        $reportsService = app(ReportsService::class);
        $report = $reportsService->getBloodRequestReport($this->filters);
        $requests = $report['requests'];

        return $requests->map(function ($request) {
            $statusLabels = [
                BloodRequestStatus::Pending->value => 'Pending',
                BloodRequestStatus::Approved->value => 'Approved',
                BloodRequestStatus::Rejected->value => 'Rejected',
                BloodRequestStatus::Completed->value => 'Completed',
            ];

            return [
                'ID' => $request->id,
                'Requested By' => $request->requestedBy->full_name ?? 'N/A',
                'Blood Type' => $request->blood_type,
                'RH Factor' => $request->rh_factor,
                'Number of Bags' => $request->number_of_bags,
                'Province' => $request->province->name ?? 'N/A',
                'City' => $request->city->name ?? 'N/A',
                'Hospital Name' => $request->hospital_name ?? 'N/A',
                'Status' => $statusLabels[$request->status] ?? 'Unknown',
                'Request Date' => $request->created_at ? $request->created_at->format('Y-m-d H:i:s') : 'N/A',
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
            'Status',
            'Request Date',
            'Approval Date',
            'Approved By',
            'Notes',
        ];
    }
}

