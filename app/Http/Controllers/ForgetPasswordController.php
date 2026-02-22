<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\VerifyResetCodeRequest;
use App\Mail\ResetPasswordMailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Str;

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
    public function verify(VerifyResetCodeRequest $request)
    {
        $email = $request->validated('email');
        $code = $request->validated('code');

        $record = $this->getResetCode($email);
        if (!$record) {
            return $this->apiResponse(
                message: 'No password reset request found for this email.',
                status: 404,
            );
        }
        if ($this->isExpired($record->created_at)) {
            $this->deleteResetCode($email);
            return $this->apiResponse(
                message: 'This code has expired. Please request a new one.',
                status: 422,
            );
        }
        if (!hash_equals((string)$record->token, (string)$code)) {
            return $this->apiResponse(
                message: 'Invalid reset code. Please check the code and try again.',
                status: 422,
            );
        }
        $verifyToken = $this->markCodeAsVerified($email);
        return $this->apiResponse(
            data: [
                'verify_token' => $verifyToken,
            ],
            message: 'Code verified successfully. You can now reset your password.',
            status: 200
        );

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
    }
    private function getResetCode(string $email): ?string
    {
        return DB::table('password_reset_tokens')
            ->select('token', 'created_at')
            ->where('email', $email)
            ->first() ;
    }
    private function deleteResetCode(string $email): void
    {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }

    private function isExpired($createdAt, $minutes = 10 ): bool
    {
        return now()->diffInMinutes($createdAt) >= $minutes ;
    }

    public function markCodeAsVerified(string $email)
    {
        $verifyToken = hash('sha256', Str::random(60));
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->update([
                'token' => $verifyToken,
                'created_at' => now(),
            ]);
        return $verifyToken;
    }
}

