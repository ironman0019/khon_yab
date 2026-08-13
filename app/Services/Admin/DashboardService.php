<?php

namespace App\Services\Admin;

use App\Enums\BloodInventoryStatus;
use App\Enums\BloodRequestStatus;
use App\Models\BloodDonationRecord;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\ContactMessage;
use App\Models\Donor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get dashboard statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total_donors' => $this->getTotalDonorsCount(),
            'total_blood_requests' => $this->getTotalBloodRequestsCount(),
            'total_blood_inventory' => $this->getTotalBloodInventory(),
            'donations_today' => $this->getDonationsTodayCount(),
            'alerts' => $this->getAlerts(),
            'graphical_statistics' => $this->getGraphicalStatistics(),
            'user_activity' => $this->getUserActivity(),
        ];
    }

    /**
     * Get total donors count.
     */
    protected function getTotalDonorsCount(): int
    {
        return Donor::count();
    }

    /**
     * Get total blood requests count.
     */
    protected function getTotalBloodRequestsCount(): int
    {
        return BloodRequest::count();
    }

    /**
     * Get total blood inventory (available bags).
     */
    protected function getTotalBloodInventory(): int
    {
        return BloodInventory::where('status', BloodInventoryStatus::InStock->value)
            ->where('expiration_date', '>=', Carbon::today())
            ->count();
    }

    /**
     * Get donations today count.
     */
    protected function getDonationsTodayCount(): int
    {
        return BloodDonationRecord::whereDate('donation_date', Carbon::today())
            ->count();
    }

    /**
     * Get alerts (low stock, expired blood, pending requests, unread contacts).
     */
    protected function getAlerts(): array
    {
        return [
            'low_stock' => $this->getLowStockAlerts(),
            'expired_blood' => $this->getExpiredBloodAlerts(),
            'pending_requests' => $this->getPendingRequestsCount(),
            'unread_contact_messages' => $this->getUnreadContactMessagesCount(),
        ];
    }

    /**
     * Get low stock alerts (blood types with less than 10 bags).
     */
    protected function getLowStockAlerts(): array
    {
        $lowStock = BloodInventory::where('status', BloodInventoryStatus::InStock->value)
            ->where('expiration_date', '>=', Carbon::today())
            ->select('blood_type', 'rh_factor', DB::raw('count(*) as count'))
            ->groupBy('blood_type', 'rh_factor')
            ->having('count', '<', 10)
            ->get();

        return $lowStock->map(function ($item) {
            return [
                'blood_type' => $item->blood_type,
                'rh_factor' => $item->rh_factor,
                'count' => $item->count,
            ];
        })->toArray();
    }

    /**
     * Get expired blood alerts.
     */
    protected function getExpiredBloodAlerts(): int
    {
        return BloodInventory::where('status', BloodInventoryStatus::InStock->value)
            ->where('expiration_date', '<', Carbon::today())
            ->count();
    }

    /**
     * Get pending requests count.
     */
    protected function getPendingRequestsCount(): int
    {
        return BloodRequest::where('status', BloodRequestStatus::Pending->value)
            ->count();
    }

    /**
     * Get unread contact messages count.
     */
    protected function getUnreadContactMessagesCount(): int
    {
        return ContactMessage::query()->unread()->count();
    }

    /**
     * Get graphical statistics data.
     */
    protected function getGraphicalStatistics(): array
    {
        return [
            'monthly_donations' => $this->getMonthlyDonations(),
            'requests_by_status' => $this->getRequestsByStatus(),
            'inventory_by_blood_type' => $this->getInventoryByBloodType(),
        ];
    }

    /**
     * Get monthly donations for the last 12 months.
     */
    protected function getMonthlyDonations(): array
    {
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $donations = BloodDonationRecord::whereBetween('donation_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE_FORMAT(donation_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return $donations->pluck('count', 'month')->toArray();
    }

    /**
     * Get requests grouped by status.
     */
    protected function getRequestsByStatus(): array
    {
        $requests = BloodRequest::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return $requests->mapWithKeys(function ($item) {
            $statusName = BloodRequestStatus::from($item->status)->name;

            return [$statusName => $item->count];
        })->toArray();
    }

    /**
     * Get inventory grouped by blood type.
     */
    protected function getInventoryByBloodType(): array
    {
        $inventory = BloodInventory::where('status', BloodInventoryStatus::InStock->value)
            ->where('expiration_date', '>=', Carbon::today())
            ->select('blood_type', 'rh_factor', DB::raw('COUNT(*) as count'))
            ->groupBy('blood_type', 'rh_factor')
            ->get();

        return $inventory->map(function ($item) {
            return [
                'blood_type' => $item->blood_type.$item->rh_factor,
                'count' => $item->count,
            ];
        })->toArray();
    }

    /**
     * Get recent user activity.
     */
    protected function getUserActivity(): array
    {
        // Recent blood requests
        $recentRequests = BloodRequest::with(['requestedBy:id,full_name,email'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($request) {
                return [
                    'type' => 'blood_request',
                    'description' => "New blood request from {$request->requestedBy->full_name}",
                    'created_at' => $request->created_at->toDateTimeString(),
                ];
            });

        // Recent donations
        $recentDonations = BloodDonationRecord::with(['donor.user:id,full_name'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($donation) {
                return [
                    'type' => 'donation',
                    'description' => "Blood donation recorded from {$donation->donor->user->full_name}",
                    'created_at' => $donation->created_at->toDateTimeString(),
                ];
            });

        // Combine and sort by created_at
        $activities = $recentRequests->concat($recentDonations)
            ->sortByDesc('created_at')
            ->take(10)
            ->values()
            ->toArray();

        return $activities;
    }
}
