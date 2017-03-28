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
        if ($this->configExists() && $this->domainAllowed()) {
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

        if ($this->configExists() && $this->domainAllowed()) {
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

    /**
     * Check if the domain is allowed.
     *
     * @return bool
     */
    protected function domainAllowed()
    {
        $allowedDomains = Config::get('sudosu.domains');

        // Split out the request url into the domain-name and port
        $requestData = parse_url(Request::url());
        $domain = $requestData['host'];
        $port = isset($requestData['port']) ? $requestData['port'] : null;

        // Create a regex based on the domains
        foreach($allowedDomains as $allowedDomain) {
            $regex = "/$allowedDomain$/";

            // Replace * with all chars
            $regex = str_replace('*', '(.*)', $regex);

            // Check the regex agains the domain.
            if ($port && preg_match($regex, "$domain:$port") || (!$port && preg_match($regex, $domain))) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function configExists()
    {
        return is_array(Config::get('sudosu.domains'));
    }
}
