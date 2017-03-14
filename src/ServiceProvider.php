<?php

namespace VIACreative\SudoSu;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use VIACreative\SudoSu\Middleware\SudoSuMiddleware;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        if ($this->configExists() && $this->tldIsAllowed()) {
            $this->app->register(RouteServiceProvider::class);
        }
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../resources/assets/compiled' => public_path('sudo-su/'),
        ], 'public');

        $this->publishes([
            __DIR__.'/../config/sudosu.php' => config_path('sudosu.php')
        ], 'config');

        if ($this->configExists() && $this->tldIsAllowed()) {
            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sudosu');

            $kernel = $this->app['Illuminate\Contracts\Http\Kernel'];
            $kernel->pushMiddleware(SudoSuMiddleware::class);
        }
    }

    protected function tldIsAllowed()
    {
        $requestTld = $this->getRequestTld();
        $allowedTlds = Config::get('sudosu.allowed_tlds');

        return in_array($requestTld, $allowedTlds);
    }

    protected function getRequestTld()
    {
        $requestHost = parse_url(Request::url())['host'];
        $exploded = explode('.', $requestHost);
        $requestTld = end($exploded);

        return $requestTld;
    }

    protected function configExists()
    {
        return is_array(Config::get('sudosu.allowed_tlds'));
    }
}