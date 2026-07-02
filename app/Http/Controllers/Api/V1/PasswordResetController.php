<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function forgotPassword(Request $request): JsonResponse
    {

        $request->validate([
            'email' => ['required', 'email']
        ]);

        PasswordBroker::sendResetLink($request->only('email'));

        return response()->json(
            [
                'success' => true,
                'message' => 'If the email exists, a reset link has been sent.',

            ]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'email' => ['required', 'email'],
                'token' => ['required', 'string'],
                'password' => [
                    'required',
                    'string',
                    'confirmed',
                    PasswordRule::min(8)->letters()->numbers()->symbols(),
                ],
            ],
        );

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ]);

                if (method_exists($user, 'getRememberTokenName') && Schema::hasColumn($user->getTable(), $user->getRememberTokenName())) {
                    $user->setRememberToken(Str::random(60));
                }

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'Password Reset Successful.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid or expired reset link.',
        ], 400);
    }
}
