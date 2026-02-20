<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortLikeGenerateRequest;
use Illuminate\Http\Request;

class ShortLinkGeneratorController extends Controller
{
    public function generate(ShortLikeGenerateRequest $request)
    {
        $randomString = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6);
        $shortLink = url('/s/' . $randomString);
        auth()->user()->links()->create([
            'original_url' => $request->input('original_url'),
            'short_code' => $randomString,
            'custom_alias' => $request->input('custom_alias'),
            'title' => $request->input('title'),
        ]);
        return response()->json(['short_url' => $shortLink], 201);
    }
}
