<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * GET /api/admin/dashboard
     * Returns all stats, chart data, and recent bookings for the admin dashboard.
     */
    public function index(): JsonResponse
    {
        // ── Stat Cards ───────────────────────────────────────────────────────
        $totalUsers         = User::where('role', 'user')->count();
        $totalOperators     = User::where('role', 'operator')->count();
        $activeCompanies    = Company::where('status', 'active')->count();
        $totalBuses         = Bus::count();

        $totalBookings      = Booking::count();
        $confirmedBookings  = Booking::where('booking_status', 'confirmed')->count();
        $pendingBookings    = Booking::where('booking_status', 'pending')->count();
        $cancelledBookings  = Booking::where('booking_status', 'cancelled')->count();

        $totalRevenue       = Booking::where('booking_status', 'confirmed')
                                ->sum('total_amount');

        $todayBookings      = Booking::whereDate('created_at', today())->count();
        $todayRevenue       = Booking::where('booking_status', 'confirmed')
                                ->whereDate('created_at', today())
                                ->sum('total_amount');

        // ── Monthly Bookings Chart (last 12 months) ───────────────────────────
        $monthlyData = Booking::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('SUM(CASE WHEN booking_status = "confirmed" THEN 1 ELSE 0 END) as confirmed_bookings'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Build a full 12-month array (fill gaps with 0)
        $chartLabels  = [];
        $chartBookings = [];
        $chartRevenue  = [];

        for ($i = 11; $i >= 0; $i--) {
            $date   = now()->subMonths($i);
            $y      = (int) $date->format('Y');
            $m      = (int) $date->format('n');
            $label  = $date->format('M');

            $row = $monthlyData->first(fn($r) => (int)$r->year === $y && (int)$r->month === $m);

            $chartLabels[]   = $label;
            $chartBookings[] = $row ? (int) $row->total_bookings : 0;
            $chartRevenue[]  = $row ? (float) $row->revenue : 0;
        }

        // ── Booking Status Distribution ────────────────────────────────────────
        $statusDistribution = [
            'confirmed' => $confirmedBookings,
            'pending'   => $pendingBookings,
            'cancelled' => $cancelledBookings,
        ];

        // ── Recent Bookings (last 8) ───────────────────────────────────────────
        $recentBookings = Booking::with([
                'user',
                'scheduleBus.schedule.route',
                'scheduleBus.bus',
            ])
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn($b) => [
                'id'       => $b->id,
                'pnr'      => $b->booking_reference,
                'customer' => $b->user?->name ?? 'Unknown',
                'email'    => $b->user?->email ?? '',
                'route'    => ($b->scheduleBus?->schedule?->route?->source_city ?? '?')
                             . ' → '
                             . ($b->scheduleBus?->schedule?->route?->destination_city ?? '?'),
                'date'     => $b->scheduleBus?->schedule?->travel_date ?? null,
                'amount'   => $b->total_amount,
                'status'   => $b->booking_status,
                'bus'      => $b->scheduleBus?->bus?->bus_number ?? '—',
            ]);

        return response()->json([
            'stats' => [
                'total_users'        => $totalUsers,
                'total_operators'    => $totalOperators,
                'active_companies'   => $activeCompanies,
                'total_buses'        => $totalBuses,
                'total_bookings'     => $totalBookings,
                'confirmed_bookings' => $confirmedBookings,
                'pending_bookings'   => $pendingBookings,
                'cancelled_bookings' => $cancelledBookings,
                'total_revenue'      => $totalRevenue,
                'today_bookings'     => $todayBookings,
                'today_revenue'      => $todayRevenue,
            ],
            'chart' => [
                'labels'   => $chartLabels,
                'bookings' => $chartBookings,
                'revenue'  => $chartRevenue,
            ],
            'status_distribution' => $statusDistribution,
            'recent_bookings'     => $recentBookings,
        ]);
    }
}
