<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDriveService;

class GoogleDriveAuthCommand extends Command
{
    protected $signature = 'drive:auth {code? : Authorization code from Google}';
    protected $description = 'Authorize Google Drive OAuth 2.0 and generate Refresh Token';

    public function handle()
    {
        $clientId = env('GOOGLE_DRIVE_CLIENT_ID');
        $clientSecret = env('GOOGLE_DRIVE_CLIENT_SECRET');

        $client = new GoogleClient();
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        $client->addScope(GoogleDriveService::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $authCode = $this->argument('code');

        if (!$authCode) {
            $authUrl = $client->createAuthUrl();
            $this->info("==================================================================");
            $this->info("Silakan buka tautan berikut di browser Anda:");
            $this->line($authUrl);
            $this->info("==================================================================");
            $authCode = $this->ask("Tempelkan (paste) kode otorisasi dari Google di sini");
        }

        if (empty($authCode)) {
            $this->error("Kode otorisasi tidak boleh kosong.");
            return 1;
        }

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode(trim($authCode));

            if (isset($accessToken['error'])) {
                $this->error("Gagal mendapatkan token: " . ($accessToken['error_description'] ?? $accessToken['error']));
                return 1;
            }

            $refreshToken = $accessToken['refresh_token'] ?? null;

            if (!$refreshToken) {
                $this->warn("Token diterima tetapi refresh_token tidak ditemukan (mungkin sudah pernah diotorisasi sebelumnya).");
                $this->line(json_encode($accessToken));
            } else {
                $this->info("BERHASIL! Refresh Token berhasil didapatkan:");
                $this->line($refreshToken);

                $this->updateEnvFile([
                    'GOOGLE_DRIVE_CLIENT_ID' => $clientId,
                    'GOOGLE_DRIVE_CLIENT_SECRET' => $clientSecret,
                    'GOOGLE_DRIVE_REFRESH_TOKEN' => $refreshToken,
                ]);

                $this->info("Variabel GOOGLE_DRIVE_REFRESH_TOKEN telah otomatis disimpan ke file .env!");
            }

            return 0;
        } catch (\Throwable $e) {
            $this->error("Terjadi error: " . $e->getMessage());
            return 1;
        }
    }

    protected function updateEnvFile(array $data)
    {
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($data as $key => $value) {
            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
            } else {
                $envContent .= "\n{$key}={$value}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
