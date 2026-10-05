<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDriveService;
use Illuminate\Support\Facades\Storage;

class GoogleDriveAuthController extends Controller
{
    public function connect(Request $request)
    {
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');

        $client = new GoogleClient();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri(url('/google-drive/callback'));
        $client->addScope(GoogleDriveService::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        return redirect()->away($client->createAuthUrl());
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return response("Otorisasi dibatalkan atau error: " . $request->get('error_description', $request->get('error')), 400);
        }

        $code = $request->get('code');
        if (!$code) {
            return response("Kode otorisasi tidak ditemukan.", 400);
        }

        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');

        $client = new GoogleClient();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri(url('/google-drive/callback'));
        $client->addScope(GoogleDriveService::DRIVE);

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($accessToken['error'])) {
                return response("Gagal mendapatkan token: " . ($accessToken['error_description'] ?? $accessToken['error']), 400);
            }

            $refreshToken = $accessToken['refresh_token'] ?? null;

            if ($refreshToken) {
                $this->updateEnv('GOOGLE_DRIVE_REFRESH_TOKEN', $refreshToken);
            }

            return response("<h3>BERHASIL! Google Drive Berhasil Terhubung ke e-Kehadiran</h3><p>Refresh Token berhasil disimpan ke sistem. Anda dapat menutup tab ini.</p>", 200);
        } catch (\Throwable $e) {
            return response("Terjadi kesalahan: " . $e->getMessage(), 500);
        }
    }

    protected function updateEnv(string $key, string $value)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        if (preg_match("/^{$key}=/m", $envContent)) {
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
        } else {
            $envContent .= "\n{$key}={$value}";
        }

        file_put_contents($envPath, $envContent);
    }
}
