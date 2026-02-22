<?php

namespace App\Http\Controllers;

use App\Models\Click;
use App\Models\Link;
use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use Jenssegers\Agent\Agent;
use function Laravel\Prompts\error;

class RedirectController extends Controller
{
    use ApiResponseHelper;

    public function redirect($code)
    {
        $link = Link::where('short_code', $code)->firstOrFail();

        if (!$link) {
            return $this->apiResponse(message: 'Link not found', status: 'error', code: 404);
        }

        try {
            $this->recordClick($link);
            $link->increment('clicks_count');

            return $this->apiResponse(
                data: $link->original_url,
                message: 'Redirecting to original URL',
            );
        } catch (\Exception $e) {
            return $this->apiResponse(message: $e->getMessage(), status: 'error', code: 500);
        }
    }

    private function recordClick(Link $link): void
    {
        $agent = new Agent();
        $geo = geoip()->getLocation(request()->ip());

        Click::create([
            'link_id'     => $link->id,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'referrer'    => request()->headers->get('referer'),
            'device_type' => $agent->device(),
            'platform'    => $agent->platform(),
            'browser'     => $agent->browser(),
            'city'        => $geo->city,
            'region'      => $geo->state_name,
            'country'     => $geo->country,
        ]);
    }
}
