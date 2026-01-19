<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TwoFactorAuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    protected TwoFactorAuthenticationService $twoFactorService;

    public function __construct(TwoFactorAuthenticationService $twoFactorService)
    {
        $this->twoFactorService = $twoFactorService;
    }

    /**
     * Show the 2FA setup page
     */
    public function enable(Request $request)
    {
        $user = $request->user();

        if ($user->two_factor_enabled) {
            return redirect()->route('profile.edit')->with('error', 'Two-factor authentication is already enabled.');
        }

        // Generate secret and store in session temporarily
        $secret = $this->twoFactorService->generateSecretKey();
        $request->session()->put('two_factor_secret', $secret);

        // Generate QR code
        $qrCode = $this->twoFactorService->generateQrCode($user, $secret);

        return view('auth.two-factor.enable', [
            'secret' => $secret,
            'qrCode' => $qrCode,
        ]);
    }

    /**
     * Confirm and enable 2FA
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = $request->user();
        $secret = $request->session()->get('two_factor_secret');

        if (!$secret) {
            return back()->withErrors(['code' => 'Session expired. Please try again.']);
        }

        // Temporarily set the secret to verify the code
        $user->two_factor_secret = encrypt($secret);

        if (!$this->twoFactorService->verifyCode($user, $request->code)) {
            return back()->withErrors(['code' => 'The provided code is invalid.']);
        }

        // Enable 2FA
        $this->twoFactorService->enableTwoFactor($user, $secret);

        // Clear session
        $request->session()->forget('two_factor_secret');

        // Get recovery codes to display
        $recoveryCodes = $this->twoFactorService->getRecoveryCodes($user);

        return view('auth.two-factor.recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return back()->with('error', 'Two-factor authentication is not enabled.');
        }

        $this->twoFactorService->disableTwoFactor($user);

        return back()->with('success', 'Two-factor authentication has been disabled.');
    }

    /**
     * Show recovery codes
     */
    public function recoveryCodes(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return redirect()->route('profile.edit')->with('error', 'Two-factor authentication is not enabled.');
        }

        $recoveryCodes = $this->twoFactorService->getRecoveryCodes($user);

        return view('auth.two-factor.recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
        ]);
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        $user = $request->user();

        if (!$user->two_factor_enabled) {
            return back()->with('error', 'Two-factor authentication is not enabled.');
        }

        $recoveryCodes = $this->twoFactorService->regenerateRecoveryCodes($user);

        return view('auth.two-factor.recovery-codes', [
            'recoveryCodes' => $recoveryCodes,
            'regenerated' => true,
        ]);
    }
}
