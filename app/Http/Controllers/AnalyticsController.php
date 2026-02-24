<?php

namespace App\Http\Controllers;

use App\Http\Requests\Analytics\ClicksOverTimeRequest;
use App\Http\Requests\Analytics\RecentClicksRequest;
use App\Models\Click;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
class AnalyticsController extends Controller
{
    use ApiResponseHelper;
    public function overview(Request $request)
    {
        /**
         * GET /analytics/overview
         * Global summary of all user's links
         */
        $user = $request->user();
        $totalLinks = $user->links()->withCount('clicks')->get();
        if ($totalLinks->isEmpty()) {
            return $this->apiResponse(
                data: [
                    'total_links'          => 0,
                    'active_links'         => 0,
                    'inactive_links'       => 0,
                    'total_clicks'         => 0,
                    'unique_clicks'        => 0,
                    'best_performing_link' => null,
                    'top_five_links'       => [],
                    'peak_hours'           => [],
                    'top_referrers'        => [],
                ],
                message: 'No links found',
                code: 404
            );
        }
        $totalLinkIds = $totalLinks->pluck('id');
        $topLinks = $totalLinks->sortByDesc('clicks_count')
            ->take(5)
            ->map(function ($link) {
                return [
                    'id'           => $link->id,
                    'title'        => $link->title,
                    'short_code'   => $link->short_code,
                    'original_url' => $link->original_url,
                    'clicks' => $link->clicks_count,
                ];
             })->values();

            $peakHours = Click::whereIn('link_id', $totalLinkIds)
                ->selectRaw('HOUR(created_at) as hour, COUNT(*) as total')
                ->groupBy('hour')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            $topReferrers = Click::whereIn('link_id', $totalLinkIds)
                ->whereNotNull('referrer')
                ->selectRaw('referrer, COUNT(*) as total')
                ->groupBy('referrer')
                ->orderByDesc('total')
                ->limit(5)
            ->get();

        return $this->apiResponse(
            data: [
                'total_links' => $totalLinks->count(),
                'active_link' => $totalLinks->where('is_active', true)->count(),
                'inactive_link' => $totalLinks->where('is_active', false)->count(),
                'total_clicks' => $totalLinks->sum('clicks_count'),
                'best_performing_link' => $topLinks->first(),
                'top_five_links'      => $topLinks,
                'peak_hours'     => $peakHours,
                'top_referrers'  => $topReferrers,
            ],
            message: 'Analytics overview retrieved successfully'
        );

    }

    /**
     * GET /analytics/clicks-over-time?period=week|month|year
     * Clicks grouped by day across all user's links
     */
    public function clicksOverTime(ClicksOverTimeRequest $request)
    {
        $validatedData = $request->validated();
        if (isset($validatedData['from'] , $validatedData['to']))
        {
            $startDate = now()->parse($validatedData['from'])->startOfDay(); // Start of the 'from' date 2024-06-01 15:30:0 => 2024-06-01 00:00:00
            $endDate = now()->parse($validatedData['to'])->endOfDay(); // End of the 'to' date 2024-06-07 10:20:0 => 2024-06-07 23:59:59
            $periodLabel ='custom';
        } else{
            $period = $validatedData['period'] ?? 'week';
            $periodLabel = $period;
            $days = match($period) {
                'month' => 30,
                'year'  => 365,
                default => 7,   // week is the default
            };
            $startDate = now()->subDays($days)->startOfDay(); // Start of the period (e.g., 7 days ago)
            $endDate = now()->endOfDay(); // End of today
        }

        $userLinksId = auth()->user()->links()->pluck('id'); // Return a collection of link IDs
        if ($userLinksId->isEmpty()) {
            return $this->apiResponse(
                data: ['period' => $period, 'clicks_over_time' => []],
                message: 'No links found',code: 404
            );
        }

        // Current Period Clicks Data
        $clickData = Click::whereIn('link_id', $userLinksId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        /*
         * Current Period => 2024-06-07 00:00:00 to 2024-06-013 23:59:59
         * Duration in days => (2024-06-13 - 2024-06-07) => 7 days
         * Prev End Date => 2024-06-06 00:00:00
         * Prev Start Date => 2024-05-30 00:00:00
         * ------------------------------ EXAMPLE ------------------------------
         * ********* Current period:   Feb 17 ──────────── Feb 24   (7 days)
         *   Duration in days: 7 days
         *   Prev End Date:  Feb 16 (1 day before current period start) (I WILL END HERE IN THE PREVIOUS PERIOD)
         *   Prev Start Date: Prev End Date - Duration in days => Feb 16 - 7 days => Feb 9 (I WILL START HERE IN THE PREVIOUS PERIOD)
         * ********* Previous period:  Feb 10 ──────────── Feb 16   (7 days)
         * */
        $durationInDays = $startDate->diffInDays($endDate);
        $pevEndDate = $startDate->copy()->subDays()->startOfDay();
        $prevStartDate = $pevEndDate->copy()->subDays($durationInDays)->startOfDay();

        // Previous Period Clicks Data
        $prevClickData = Click::whereIn('link_id', $userLinksId)
            ->whereBetween('created_at', [$prevStartDate, $pevEndDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $currentTotalClicks = $clickData->sum('clicks');
        $prevTotalClicks = $prevClickData->sum('clicks');
        /*
         * Growth Rate Calculation:
         *      Growth Rate (%) = ((Current Period Clicks - Previous Period Clicks) / Previous Period Clicks) * 100
         * If Previous Period Clicks is 0 and Current Period Clicks is greater than 0, we can consider it as 100% growth.
         * If both periods have 0 clicks, growth rate is 0%.
         * If previous > 0 → normal formula
         *
         * */
        $growthRate = $prevTotalClicks > 0 ? round((($currentTotalClicks - $prevTotalClicks) / $prevTotalClicks) * 100) : ($currentTotalClicks > 0 ? 100 : 0);
        return $this->apiResponse(
            data: [
                'period' => $periodLabel,
                'clicks_over_time' => $clickData,
                'comparison' => [
                    'current_total' => $currentTotalClicks,
                    'previous_total' => $prevTotalClicks,
                    'growth_percentage' => "$growthRate%",
                ]
            ],
            message: 'Clicks over time retrieved successfully'
        );

    }

    /**
     * GET /analytics/links/{id}
     * Deep analytics for a single link
     */
    public function linkAnalytics($id)
    {
        $link = auth()->user()->links()->withCount('clicks')->find($id);
        if (!$link) {
            return $this->apiResponse(message: 'Link not found', code: 404);
        }
        if ($link->clicks_count === 0) {
            return $this->apiResponse(
                data: [
                    'link' => [
                        'id'            => $link->id,
                        'title'         => $link->title,
                        'short_code'    => $link->short_code,
                        'original_url'  => $link->original_url,
                        'is_active'     => $link->is_active,
                        'total_clicks'  => 0,
                        'unique_clicks' => 0,
                    ],
                    'analytics' => null,
                ],
                message: 'No clicks recorded for this link yet'
            );
        }
        $clicks = Click::where('link_id', $id); // Return a query builder for reuse in analytics
        return $this->apiResponse(
            data: [
                'link' => [
                    'id'           => $link->id,
                    'title'        => $link->title,
                    'short_code'   => $link->short_code,
                    'original_url' => $link->original_url,
                    'is_active'    => $link->is_active,
                    'total_clicks' => $link->clicks_count,
                ],
                'analytics' => [ // (clone $clicks) to reuse the base query for multiple aggregations without affecting each other
                    'top_countries' => (clone $clicks)->selectRaw('country, COUNT(*) as total')->groupBy('country')->orderByDesc('total')->limit(5)->get(),
                    'top_cities'    => (clone $clicks)->selectRaw('city, COUNT(*) as total')->groupBy('city')->orderByDesc('total')->limit(5)->get(),
                    'browsers'  => (clone $clicks)->selectRaw('browser, COUNT(*) as total')->groupBy('browser')->orderByDesc('total')->get(),
                    'platforms' => (clone $clicks)->selectRaw('platform, COUNT(*) as total')->groupBy('platform')->orderByDesc('total')->get(),
                    'peak_hours' => (clone $clicks)->selectRaw("HOUR(created_at) as hour, COUNT(*) AS total")->groupBy('hour')->orderByDesc('total')->limit(5)->get(),
                    'top_referrers' => (clone $clicks)->whereNotNull('referrer')->selectRaw('referrer, COUNT(*) as total')->groupBy('referrer')->orderByDesc('total')->limit(5)->get(),
                    'clicks_over_time' => (clone $clicks)->selectRaw('DATE(created_at) as date, COUNT(*) as total')->groupBy('date')->orderBy('date')->get()
                ]
            ],
            message: 'Link analytics retrieved successfully'
        );

    }

    /**
     * GET /analytics/recent-clicks?limit=10
     * Live feed of most recent clicks across all user's links
     */
    public function recentClicks(RecentClicksRequest $request)
    {
        $limit = $request->validated('limit', 10);
        $userLinksId = auth()->user()->links()->pluck('id');
        if ($userLinksId->isEmpty()) {
            return $this->apiResponse(
                message: 'No links found',
                code: 404
            );
        }
        $recentClicks = Click::whereIn('link_id', $userLinksId)
            ->with('link:id,title,short_code')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get(['id', 'link_id', 'country', 'city', 'device_type', 'browser', 'platform', 'created_at']);

        return $this->apiResponse(
            data: $recentClicks,
            message: 'Recent clicks retrieved successfully'
        );
    }
}
