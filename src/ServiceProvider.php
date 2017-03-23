<?php

namespace VIACreative\SudoSu;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
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
            $this->registerViews();
        }
    }
    
    protected function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sudosu');

        // Add an inline view composer for the user-selector
        View::composer('sudosu::user-selector', function ($view) {
            $sudosu = App::make(SudoSu::class);

            $view->with([
                'users' => $sudosu->getUsers(),
                'hasSudoed' => $sudosu->hasSudoed(),
                'originalUser' => $sudosu->getOriginalUser(),
                'currentUser' => Auth::user()
            ]);
        });   
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
