<?php

namespace App\Services;

use App\Models\User;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorAuthenticationService
{
    protected Google2FA $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Generate a new secret key for the user
     */
    public function generateSecretKey(): string
    {
        return $this->google2fa->generateSecretKey();
    }

    /**
     * Generate QR code SVG for the user
     */
    public function generateQrCode(User $user, string $secret): string
    {
        $qrCodeUrl = $this->google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);

        return $writer->writeString($qrCodeUrl);
    }

    /**
     * Verify the provided code against the user's secret
     */
    public function verifyCode(User $user, string $code): bool
    {
        if (!$user->two_factor_secret) {
            return false;
        }

        $secret = Crypt::decryptString($user->two_factor_secret);

        return $this->google2fa->verifyKey($secret, $code);
    }

    /**
     * Generate recovery codes for the user
     */
    public function generateRecoveryCodes(): Collection
    {
        return collect(range(1, 8))->map(function () {
            return strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        });
    }

    /**
     * Enable two-factor authentication for the user
     */
    public function enableTwoFactor(User $user, string $secret): void
    {
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_secret' => Crypt::encryptString($secret),
            'two_factor_recovery_codes' => Crypt::encryptString($recoveryCodes->toJson()),
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
        ]);
    }

    /**
     * Disable two-factor authentication for the user
     */
    public function disableTwoFactor(User $user): void
    {
        $user->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_enabled' => false,
            'two_factor_confirmed_at' => null,
        ]);
    }

    /**
     * Verify a recovery code
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        if (!$user->two_factor_recovery_codes) {
            return false;
        }

        $recoveryCodes = collect(json_decode(
            Crypt::decryptString($user->two_factor_recovery_codes),
            true
        ));

        if (!$recoveryCodes->contains($code)) {
            return false;
        }

        // Remove the used recovery code
        $remainingCodes = $recoveryCodes->reject(fn($c) => $c === $code);

        $user->update([
            'two_factor_recovery_codes' => Crypt::encryptString($remainingCodes->toJson()),
        ]);

        return true;
    }

    /**
     * Get recovery codes for the user
     */
    public function getRecoveryCodes(User $user): Collection
    {
        if (!$user->two_factor_recovery_codes) {
            return collect();
        }

        return collect(json_decode(
            Crypt::decryptString($user->two_factor_recovery_codes),
            true
        ));
    }

    /**
     * Regenerate recovery codes
     */
    public function regenerateRecoveryCodes(User $user): Collection
    {
        $recoveryCodes = $this->generateRecoveryCodes();

        $user->update([
            'two_factor_recovery_codes' => Crypt::encryptString($recoveryCodes->toJson()),
        ]);

        return $recoveryCodes;
    }
}
