<?php

namespace App\Http\Controllers;

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
                ],
                message: 'No links found',
                code: 404
            );
        }
        $userBestLink = $totalLinks->sortByDesc('clicks_count')->first();
        return $this->apiResponse(
            data: [
                'total_links' => $totalLinks->count(),
                'active_link' => $totalLinks->where('is_active', true)->count(),
                'inactive_link' => $totalLinks->where('is_active', false)->count(),
                'total_clicks' => $totalLinks->sum('clicks_count'),
                'best_performing_link' => $userBestLink ? [
                    'id'           => $userBestLink->id,
                    'title'        => $userBestLink->title,
                    'short_code'   => $userBestLink->short_code,
                    'original_url' => $userBestLink->original_url,
                    'clicks'       => $userBestLink->clicks_count,
                ] : null
            ],
            message: 'Analytics overview retrieved successfully'
        );

    }

    /**
     * GET /analytics/clicks-over-time?period=week|month|year
     * Clicks grouped by day across all user's links
     */
    public function clicksOverTime(Request $request)
    {
        $period = $request->query('period', 'week');
        $startDate = match ($period) {
            'month' => now()->subDays(30),
            'year' => now()->subDays(365),
            default => now()->subDays(7),
        };
        $userLinksId = auth()->user()->links()->pluck('id'); // Return a collection of link IDs
        if ($userLinksId->isEmpty()) {
            return $this->apiResponse(
                data: ['period' => $period, 'clicks_over_time' => []],
                message: 'No links found',code: 404
            );
        }
        $clickData = Click::whereIn('link_id', $userLinksId)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as clicks')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return $this->apiResponse(
            data: [
                'period' => $period,
                'clicks_over_time' => $clickData
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
    public function recentClicks(Request $request)
    {
        $limit = $request->query('limit', 10);
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
