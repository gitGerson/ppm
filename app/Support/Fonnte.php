<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Client for the Fonnte WhatsApp gateway.
 *
 * Only the "send a text message to one number" slice of the API is covered,
 * which is all the santri notifications need.
 */
class Fonnte
{
    public static function isEnabled(): bool
    {
        return (bool) config('fonnte.enabled')
            && filled(config('fonnte.token'));
    }

    /**
     * Turn whatever the santri typed into the digits-only international form
     * Fonnte expects, or null when nothing dialable is left.
     */
    public static function normalizeTarget(?string $phone): ?string
    {
        $countryCode = (string) config('fonnte.country_code', '62');
        $digits = preg_replace('/\D/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '0')) {
            $digits = $countryCode.substr($digits, 1);
        } elseif ($digits !== '' && ! str_starts_with($digits, $countryCode)) {
            // Written without either prefix, e.g. "81234567890".
            $digits = $countryCode.$digits;
        }

        // Country code plus the shortest plausible mobile number.
        return strlen($digits) >= strlen($countryCode) + 9 ? $digits : null;
    }

    /**
     * @param  string  $target  digits-only number, as returned by normalizeTarget()
     */
    public function send(string $target, string $message): bool
    {
        if (! self::isEnabled()) {
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => (string) config('fonnte.token'),
            ])
                ->timeout((int) config('fonnte.timeout', 15))
                ->asForm()
                ->post((string) config('fonnte.endpoint'), [
                    'target' => $target,
                    'message' => $message,
                    'countryCode' => (string) config('fonnte.country_code', '62'),
                ]);
        } catch (ConnectionException $exception) {
            Log::warning('Fonnte tidak dapat dihubungi', [
                'target' => $target,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }

        // A rejected message still comes back as HTTP 200 carrying
        // {"status": false, "reason": "..."}, so the status code alone is not
        // enough to call this a success.
        if ($response->failed() || $response->json('status') !== true) {
            Log::warning('Pengiriman pesan Fonnte gagal', [
                'target' => $target,
                'status_code' => $response->status(),
                'body' => $response->json() ?? $response->body(),
            ]);

            return false;
        }

        return true;
    }
}
