<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorChallengeController extends Controller
{
    protected TwoFactorAuthenticationService $twoFactorService;

    public function __construct(TwoFactorAuthenticationService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show the 2FA challenge page
     */
    public function show(Request $request)
    {
        if (!$request->session()->has('two_factor_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor.challenge');
    }

    /**
     * Verify the 2FA code
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'recovery' => 'boolean',
        ]);

        $userId = $request->session()->get('two_factor_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if (!$user || !$user->two_factor_enabled) {
            return redirect()->route('login');
        }

        $isValid = false;

        if ($request->boolean('recovery')) {
            // Verify recovery code
            $isValid = $this->twoFactorService->verifyRecoveryCode($user, $request->code);
        } else {
            // Verify TOTP code
            $isValid = $this->twoFactorService->verifyCode($user, $request->code);
        }

        if (!$isValid) {
            return back()->withErrors([
                'code' => 'The provided code is invalid.',
            ])->withInput();
        }

        // Clear the 2FA session data
        $request->session()->forget('two_factor_user_id');

        // Log the user in
        Auth::login($user, $request->session()->get('two_factor_remember', false));
        $request->session()->forget('two_factor_remember');

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }
}
