<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Mail\ResetPasswordMailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Helpers\ApiResponseHelper;

class ForgetPasswordController extends Controller
{
    use ApiResponseHelper;
    public function store(ForgetPasswordRequest $request)
    {
        $email = $request->validated('email');

        if ($this->hasRecentResetCode($email)) {
            return $this->apiResponse(
                message: 'A password reset code was already sent. Please check your inbox.',
                status: 200,
            );
        }

        try {
            $code = $this->generateResetCode();

            $this->saveResetCode($email, $code);

            Mail::to($email)->send(new ResetPasswordMailer($code));

            return $this->apiResponse(
                message: 'Password reset code sent to your email.',
                status: 200,
            );
        } catch (\Exception $e) {
            Log::error('Password reset failed', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return $this->apiResponse(
                message: 'Failed to send password reset code. Please try again later.',
                status: 500,
            );
        }
    }

// ─── Private helpers ──────────────────────────────────────────────────────────

    private function hasRecentResetCode(string $email): bool
    {
        $record = DB::table('password_reset_tokens')
            ->select('created_at')
            ->where('email', $email)
            ->first();

        return $record && now()->diffInMinutes($record->created_at) < 10;
    }

    private function generateResetCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function saveResetCode(string $email, string $code): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token'      => $code,
                'created_at' => now(),
            ]
        );
    }}
