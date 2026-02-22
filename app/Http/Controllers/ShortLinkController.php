<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortLikeGenerateRequest;
use App\Http\Resources\ShortLinkResource;
use App\Models\Link;
use App\Helpers\ApiResponseHelper;

class ShortLinkController extends Controller
{
    use ApiResponseHelper;
    public function index()
    {
        $links = auth()->user()->links()
            ->select('id', 'original_url', 'short_code', 'custom_alias', 'title','clicks_count')
            ->orderByDesc('created_at')
            ->get();

        if ($links->isEmpty()) {
           return $this->apiResponse(
               message: 'No links found for the user',
               code: 404);
        }
        return $this->apiResponse(ShortLinkResource::collection($links), 'Links retrieved successfully');
    }
    public function store(ShortLikeGenerateRequest $request)
    {
        try {
            $validated = $request->validated();
            $generatingResult = $this->generate();

            $link = auth()->user()->links()->create([
                'original_url' => $validated['original_url'],
                'short_code' => $generatingResult['short_code'],
                'custom_alias' => $validated['custom_alias'] ?? null,
                'title' => $validated['title'] ?? null,
            ]);

          return $this->apiResponse(
              data: new ShortLinkResource($link),
              message: 'Short link created successfully',
              code: 201
          );

        }catch (\Exception $e)
        {
            return $this->apiResponse( message: 'An error occurred while creating the short link',
                status: 'error',
                code: $e->getCode() ?: 500);
        }

    }

    public function destroy($id)
    {
        $link = auth()->user()->links()->findOrFail($id);
        $link->delete();
        return response()->json(['message' => 'Link deleted successfully']);
    }

    private function generate()
    {
        do {
            $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
        } while (Link::where('short_code', $randomString)->exists());

        return [
            'short_code' => $randomString,
            'short_url' => url('/' . $randomString)
        ];
    }
}
