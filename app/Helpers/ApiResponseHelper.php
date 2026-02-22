<?php

namespace App\Helpers;

trait ApiResponseHelper
{
    protected function apiResponse($data = [], $message = null, $status = 'success', $code = 200)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data
        ], $code);
    }
}
