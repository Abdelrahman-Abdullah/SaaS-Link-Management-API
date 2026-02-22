<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortLikeGenerateRequest;
use App\Http\Resources\ShortLinkResource;
use App\Models\Link;

class ShortLinkController extends Controller
{
    public function index()
    {
        $links = auth()->user()->links()
            ->select('id', 'original_url', 'short_code', 'custom_alias', 'title','clicks')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'message' => 'Links retrieved successfully',
            'status' => 'success',
            'data' => ShortLinkResource::collection($links)
        ]);
    }
    public function store(ShortLikeGenerateRequest $request)
    {
        $validated = $request->validated();
        $generatingResult = $this->generate();

        $link = auth()->user()->links()->create([
            'original_url' => $validated['original_url'],
            'short_code' => $generatingResult['short_code'],
            'custom_alias' => $validated['custom_alias'] ?? null,
            'title' => $validated['title'] ?? null,
        ]);

        return response()->json([
            'short_url' => $generatingResult['short_url'],
            'id' => $link->id
        ], 201);
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
