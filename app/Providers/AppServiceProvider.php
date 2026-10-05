<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDriveService;
use Masbug\Flysystem\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        if (
            config('app.env') === 'production' || 
            str_starts_with((string) config('app.url'), 'https://') ||
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
            (request()->header('X-Forwarded-Proto') === 'https')
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        try {
            Storage::extend('google', function ($app, $config) {
                $client = new GoogleClient();

                if (!empty($config['client_id']) && !empty($config['client_secret']) && !empty($config['refresh_token'])) {
                    $client->setClientId($config['client_id']);
                    $client->setClientSecret($config['client_secret']);

                    $cacheKey = 'gdrive_access_token_' . md5($config['client_id'] . $config['refresh_token']);
                    $cachedToken = Cache::get($cacheKey);

                    if ($cachedToken && is_array($cachedToken) && !empty($cachedToken['access_token']) && !empty($cachedToken['created']) && !empty($cachedToken['expires_in']) && ($cachedToken['created'] + $cachedToken['expires_in'] - 120 > time())) {
                        $client->setAccessToken($cachedToken);
                    } else {
                        $token = $client->fetchAccessTokenWithRefreshToken($config['refresh_token']);
                        if (!isset($token['error'])) {
                            Cache::put($cacheKey, $token, now()->addSeconds(($token['expires_in'] ?? 3600) - 120));
                        }
                    }
                } elseif (!empty($config['credentials_path']) && file_exists($config['credentials_path'])) {
                    $client->setAuthConfig($config['credentials_path']);
                }

                $client->addScope(GoogleDriveService::DRIVE);

                $service = new GoogleDriveService($client);
                $options = [
                    'useHasDir' => true,
                ];

                if (!empty($config['folder_id'])) {
                    $options['sharedFolderId'] = $config['folder_id'];
                }

                $adapter = new GoogleDriveAdapter($service, null, $options);

                return new FilesystemAdapter(
                    new Filesystem($adapter, $config),
                    $adapter,
                    $config
                );
            });
        } catch (\Throwable $e) {
            // Silently handle if driver registration encounters error during early boot
        }
    }
}


