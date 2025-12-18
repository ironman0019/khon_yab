<?php

namespace App\Services\Admin;

use App\Enums\BloodRequestStatus;
use App\Models\BloodDonationRecord;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\Province;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsService
{
    /**
     * Generate donation report.
     */
    public function getDonationReport(array $filters = []): array
    {
        $query = BloodDonationRecord::with(['donor.user', 'recordedByAdmin']);

        // Date range filter
        if (isset($filters['date_from'])) {
            $query->whereDate('donation_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('donation_date', '<=', $filters['date_to']);
        }

        // Blood type filter
        if (isset($filters['blood_type'])) {
            $query->whereHas('donor', function ($q) use ($filters) {
                $q->where('blood_type', $filters['blood_type']);
            });
        }

        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $donations = $query->latest('donation_date')->get();

        return [
            'donations' => $donations,
            'total_count' => $donations->count(),
            'total_amount_ml' => $donations->sum('amount_ml'),
            'filters' => $filters,
        ];
    }

    /**
     * Generate blood request report.
     */
    public function getBloodRequestReport(array $filters = []): array
    {
        $query = BloodRequest::with(['requestedBy', 'approvedBy', 'province', 'city']);

        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Date range filter
        if (isset($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // Blood type filter
        if (isset($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        $requests = $query->latest()->get();

        return [
            'requests' => $requests,
            'total_count' => $requests->count(),
            'total_bags_requested' => $requests->sum('number_of_bags'),
            'by_status' => $requests->groupBy('status')->map->count(),
            'filters' => $filters,
        ];
    }

    /**
     * Generate inventory report.
     */
    public function getInventoryReport(array $filters = []): array
    {
        $query = BloodInventory::with(['bloodDonationRecord.donor.user', 'province']);

        // Status filter
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Blood type filter
        if (isset($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        // Expiration filter
        if (isset($filters['expiration_filter'])) {
            $today = Carbon::today();
            match ($filters['expiration_filter']) {
                'expired' => $query->where('expiration_date', '<', $today),
                'expiring_soon' => $query->whereBetween('expiration_date', [$today, $today->copy()->addDays(7)]),
                'valid' => $query->where('expiration_date', '>', $today->copy()->addDays(7)),
                default => null,
            };
        }

        $inventory = $query->get();

        return [
            'inventory' => $inventory,
            'total_count' => $inventory->count(),
            'by_status' => $inventory->groupBy('status')->map->count(),
            'by_blood_type' => $inventory->groupBy('blood_type')->map->count(),
            'filters' => $filters,
        ];
    }

    /**
     * Generate user/donor statistics report.
     */
    public function getUserStatisticsReport(): array
    {
        $totalUsers = DB::table('users')->count();
        $totalDonors = DB::table('donors')->count();
        $activeDonors = DB::table('donors')->where('ability_to_donate', 1)->count();
        $healthyDonors = DB::table('donors')->where('health_status', 1)->count();

        $donorsByBloodType = DB::table('donors')
            ->select('blood_type', DB::raw('count(*) as count'))
            ->groupBy('blood_type')
            ->get()
            ->pluck('count', 'blood_type')
            ->toArray();

        $donorsByProvince = DB::table('donors')
            ->join('provinces', 'donors.province_id', '=', 'provinces.id')
            ->select('provinces.name', DB::raw('count(*) as count'))
            ->groupBy('provinces.name')
            ->get()
            ->pluck('count', 'name')
            ->toArray();

        return [
            'total_users' => $totalUsers,
            'total_donors' => $totalDonors,
            'active_donors' => $activeDonors,
            'healthy_donors' => $healthyDonors,
            'donors_by_blood_type' => $donorsByBloodType,
            'donors_by_province' => $donorsByProvince,
        ];
    }

    /**
     * Generate comprehensive summary report.
     */
    public function getSummaryReport(): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        return [
            'donations' => [
                'today' => BloodDonationRecord::whereDate('donation_date', $today)->count(),
                'this_month' => BloodDonationRecord::whereDate('donation_date', '>=', $thisMonth)->count(),
                'last_month' => BloodDonationRecord::whereDate('donation_date', '>=', $lastMonth)
                    ->whereDate('donation_date', '<', $thisMonth)->count(),
            ],
            'requests' => [
                'pending' => BloodRequest::where('status', BloodRequestStatus::Pending->value)->count(),
                'approved' => BloodRequest::where('status', BloodRequestStatus::Approved->value)->count(),
                'completed' => BloodRequest::where('status', BloodRequestStatus::Completed->value)->count(),
            ],
            'inventory' => [
                'in_stock' => BloodInventory::where('status', 0)->where('expiration_date', '>=', $today)->count(),
                'expired' => BloodInventory::where('expiration_date', '<', $today)->count(),
                'used' => BloodInventory::where('status', 1)->count(),
            ],
            'users' => [
                'total' => DB::table('users')->count(),
                'donors' => DB::table('donors')->count(),
            ],
        ];
    }

    /**
     * Generate active donors report.
     */
    public function getActiveDonorsReport(array $filters = []): array
    {
        $query = Donor::with(['user', 'province', 'city'])
            ->where('ability_to_donate', 1)
            ->where('health_status', 1);

        // Province filter
        if (isset($filters['province_id'])) {
            $query->where('province_id', $filters['province_id']);
        }

        // Blood type filter
        if (isset($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        // Date range filter (by last donation date)
        if (isset($filters['date_from'])) {
            $query->whereDate('last_donation_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('last_donation_date', '<=', $filters['date_to']);
        }

        $donors = $query->latest()->get();

        return [
            'donors' => $donors,
            'total_count' => $donors->count(),
            'by_blood_type' => $donors->groupBy('blood_type')->map->count(),
            'by_province' => $donors->groupBy('province.name')->map->count(),
            'filters' => $filters,
        ];
    }

    /**
     * Generate blood shortage report.
     */
    public function getBloodShortageReport(array $filters = []): array
    {
        $threshold = $filters['threshold'] ?? 5;
        $today = Carbon::today();

        $query = BloodInventory::with(['province', 'bloodDonationRecord.donor.user'])
            ->where('status', 0) // In stock only
            ->where('expiration_date', '>=', $today); // Not expired

        // Blood type filter
        if (isset($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        $inventory = $query->get();

        // Group by blood type and RH factor, count items
        $grouped = $inventory->groupBy(function ($item) {
            return $item->blood_type.$item->rh_factor;
        });

        // Find shortages (count < threshold)
        $shortages = [];
        foreach ($grouped as $key => $items) {
            $count = $items->count();
            if ($count < $threshold) {
                $shortages[$key] = [
                    'blood_type' => $items->first()->blood_type,
                    'rh_factor' => $items->first()->rh_factor,
                    'current_count' => $count,
                    'threshold' => $threshold,
                    'shortage' => $threshold - $count,
                    'items' => $items,
                ];
            }
        }

        return [
            'shortages' => $shortages,
            'total_shortages' => count($shortages),
            'by_blood_type' => $inventory->groupBy('blood_type')->map->count(),
            'filters' => $filters,
        ];
    }

    /**
     * Generate approved requests report.
     */
    public function getApprovedRequestsReport(array $filters = []): array
    {
        $query = BloodRequest::with(['requestedBy', 'approvedBy', 'province', 'city'])
            ->where('status', BloodRequestStatus::Approved->value);

        // Date range filter
        if (isset($filters['date_from'])) {
            $query->whereDate('approval_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('approval_date', '<=', $filters['date_to']);
        }

        // Province filter
        if (isset($filters['province_id'])) {
            $query->where('province_id', $filters['province_id']);
        }

        // Blood type filter
        if (isset($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        $requests = $query->latest('approval_date')->get();

        return [
            'requests' => $requests,
            'total_count' => $requests->count(),
            'total_bags_requested' => $requests->sum('number_of_bags'),
            'by_date' => $requests->groupBy(function ($item) {
                return $item->approval_date ? $item->approval_date->format('Y-m-d') : 'Unknown';
            })->map->count(),
            'by_province' => $requests->groupBy('province.name')->map->count(),
            'by_blood_type' => $requests->groupBy('blood_type')->map->count(),
            'filters' => $filters,
        ];
    }

    /**
     * Generate donation history by donor.
     */
    public function getDonationHistoryByDonor(array $filters = []): array
    {
        $query = BloodDonationRecord::with(['donor.user', 'recordedByAdmin']);

        // Donor filter
        if (isset($filters['donor_id'])) {
            $query->where('donor_id', $filters['donor_id']);
        }

        // Date range filter
        if (isset($filters['date_from'])) {
            $query->whereDate('donation_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->whereDate('donation_date', '<=', $filters['date_to']);
        }

        $donations = $query->latest('donation_date')->get();

        // Group by donor for statistics
        $byDonor = $donations->groupBy('donor_id')->map(function ($items, $donorId) {
            $donor = $items->first()->donor;

            return [
                'donor' => $donor,
                'donations_count' => $items->count(),
                'total_amount_ml' => $items->sum('amount_ml'),
                'donations' => $items,
            ];
        });

        return [
            'donations' => $donations,
            'by_donor' => $byDonor,
            'total_count' => $donations->count(),
            'total_amount_ml' => $donations->sum('amount_ml'),
            'unique_donors' => $byDonor->count(),
            'filters' => $filters,
        ];
    }

    /**
     * Generate reports by province.
     */
    public function getReportsByProvince(int $provinceId, array $filters = []): array
    {
        $province = Province::with('cities')->findOrFail($provinceId);

        // Donors in province
        $donorsQuery = Donor::with(['user', 'city'])
            ->where('province_id', $provinceId);
        if (isset($filters['blood_type'])) {
            $donorsQuery->where('blood_type', $filters['blood_type']);
        }
        $donors = $donorsQuery->get();

        // Blood requests in province
        $requestsQuery = BloodRequest::with(['requestedBy', 'approvedBy', 'city'])
            ->where('province_id', $provinceId);
        if (isset($filters['status'])) {
            $requestsQuery->where('status', $filters['status']);
        }
        if (isset($filters['date_from'])) {
            $requestsQuery->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $requestsQuery->whereDate('created_at', '<=', $filters['date_to']);
        }
        $requests = $requestsQuery->get();

        // Inventory in province
        $inventoryQuery = BloodInventory::with(['bloodDonationRecord.donor.user'])
            ->where('province_id', $provinceId);
        if (isset($filters['status'])) {
            $inventoryQuery->where('status', $filters['status']);
        }
        $inventory = $inventoryQuery->get();

        // Donations from donors in province
        $donationsQuery = BloodDonationRecord::with(['donor.user', 'recordedByAdmin'])
            ->whereHas('donor', function ($q) use ($provinceId) {
                $q->where('province_id', $provinceId);
            });
        if (isset($filters['date_from'])) {
            $donationsQuery->whereDate('donation_date', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to'])) {
            $donationsQuery->whereDate('donation_date', '<=', $filters['date_to']);
        }
        $donations = $donationsQuery->get();

        return [
            'province' => $province,
            'donors' => [
                'total' => $donors->count(),
                'active' => $donors->where('ability_to_donate', 1)->count(),
                'healthy' => $donors->where('health_status', 1)->count(),
                'by_blood_type' => $donors->groupBy('blood_type')->map->count(),
            ],
            'requests' => [
                'total' => $requests->count(),
                'by_status' => $requests->groupBy('status')->map->count(),
                'total_bags' => $requests->sum('number_of_bags'),
            ],
            'inventory' => [
                'total' => $inventory->count(),
                'in_stock' => $inventory->where('status', 0)->count(),
                'by_blood_type' => $inventory->groupBy('blood_type')->map->count(),
            ],
            'donations' => [
                'total' => $donations->count(),
                'total_amount_ml' => $donations->sum('amount_ml'),
            ],
            'filters' => $filters,
        ];
    }

    /**
     * Generate monthly/yearly report.
     */
    public function getMonthlyYearlyReport(array $filters = []): array
    {
        $period = $filters['period'] ?? 'month'; // 'month' or 'year'
        $startDate = isset($filters['start_date']) ? Carbon::parse($filters['start_date']) : Carbon::now()->startOfMonth();
        $endDate = isset($filters['end_date']) ? Carbon::parse($filters['end_date']) : Carbon::now()->endOfMonth();

        $data = [];

        // Generate periods based on type
        $current = $startDate->copy();
        while ($current->lte($endDate)) {
            $periodKey = $period === 'year'
                ? $current->format('Y')
                : $current->format('Y-m');

            $periodStart = $period === 'year'
                ? $current->copy()->startOfYear()
                : $current->copy()->startOfMonth();
            $periodEnd = $period === 'year'
                ? $current->copy()->endOfYear()
                : $current->copy()->endOfMonth();

            // Donations in this period
            $donations = BloodDonationRecord::whereBetween('donation_date', [$periodStart, $periodEnd])->get();

            // Requests in this period
            $requests = BloodRequest::whereBetween('created_at', [$periodStart, $periodEnd])->get();

            // Inventory entries in this period
            $inventory = BloodInventory::whereBetween('entry_date', [$periodStart, $periodEnd])->get();

            $data[$periodKey] = [
                'period' => $periodKey,
                'donations' => [
                    'count' => $donations->count(),
                    'total_amount_ml' => $donations->sum('amount_ml'),
                ],
                'requests' => [
                    'count' => $requests->count(),
                    'approved' => $requests->where('status', BloodRequestStatus::Approved->value)->count(),
                    'total_bags' => $requests->sum('number_of_bags'),
                ],
                'inventory' => [
                    'entries' => $inventory->count(),
                    'in_stock' => $inventory->where('status', 0)->count(),
                ],
            ];

            $current = $period === 'year'
                ? $current->addYear()
                : $current->addMonth();
        }

        return [
            'data' => $data,
            'period' => $period,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'filters' => $filters,
        ];
    }

    /**
     * Generate bag expiration report.
     */
    public function getBagExpirationReport(array $filters = []): array
    {
        $today = Carbon::today();
        $query = BloodInventory::with(['bloodDonationRecord.donor.user', 'province'])
            ->where('status', 0); // In stock only

        // Expiration date range filter
        if (isset($filters['expiration_date_from'])) {
            $query->whereDate('expiration_date', '>=', $filters['expiration_date_from']);
        } else {
            // Default: show expiring soon (next 30 days) and expired
            $query->whereDate('expiration_date', '<=', $today->copy()->addDays(30));
        }

        if (isset($filters['expiration_date_to'])) {
            $query->whereDate('expiration_date', '<=', $filters['expiration_date_to']);
        }

        // Filter type: expired, expiring_soon, or all
        if (isset($filters['expiration_type'])) {
            match ($filters['expiration_type']) {
                'expired' => $query->where('expiration_date', '<', $today),
                'expiring_soon' => $query->whereBetween('expiration_date', [$today, $today->copy()->addDays(7)]),
                default => null,
            };
        }

        // Blood type filter
        if (isset($filters['blood_type'])) {
            $query->where('blood_type', $filters['blood_type']);
        }

        $inventory = $query->orderBy('expiration_date')->get();

        // Group by expiration date
        $byExpirationDate = $inventory->groupBy(function ($item) {
            return $item->expiration_date ? $item->expiration_date->format('Y-m-d') : 'Unknown';
        });

        // Separate expired and expiring soon
        $expired = $inventory->filter(function ($item) use ($today) {
            return $item->expiration_date && $item->expiration_date->lt($today);
        });
        $expiringSoon = $inventory->filter(function ($item) use ($today) {
            return $item->expiration_date && $item->expiration_date->gte($today) && $item->expiration_date->lte($today->copy()->addDays(7));
        });

        return [
            'inventory' => $inventory,
            'expired' => $expired,
            'expiring_soon' => $expiringSoon,
            'expired_count' => $expired->count(),
            'expiring_soon_count' => $expiringSoon->count(),
            'by_expiration_date' => $byExpirationDate,
            'by_blood_type' => $inventory->groupBy('blood_type')->map->count(),
            'filters' => $filters,
        ];
    }
}
