![Demonstration](https://d78vgg4relhwk.cloudfront.net/sudo-su.gif)

---

![Licence: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)

A Laravel 5.4 utility package to enable developers to log in as other users during development.


## Installation
To install the package, simply follow the steps below.

Install the package using Composer:

```
$ composer require viacreative/sudo-su
```

Add the package's service provider to your app in your project's `AppServiceProvider`:

```php
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        if (env('APP_DEBUG')) {
            $this->app->register('VIACreative\SudoSu\ServiceProvider');
        }
    }
}
```

⚠️  *Warning:* You should not register the provider globally like usual in the `config/app.php` file. View the disclaimer [here](#disclaimer---danger) for more information.

Include the partial in your layout file.

```php
@if (env('APP_DEBUG'))
    @include('sudosu::user-selector')
@endif
```

Finally, publish the package's assets (the package won't work without this):

```
$ php artisan vendor:publish
```

## Config
After running `vendor:publish`, a config file called `sudosu.php` should appear in your project. Within here, there are two configuration values:

**sudosu.allowed_tlds `array`**

By default, the package will disable itself on any domains that don't have a TLD of `.dev` or `.local`. This is a security measure to reduce the risk of accidentally enabling the package in production. If you have a different TLD in development, you can edit the config option `sudosu.allowed_tlds`.

**sudosu.user_model `string`**

The path to the application User model. This will be used to retrieve the users displayed in the select dropdown. This must be an Eloquent Model instance. This is set to `App\User` by default.


## Disclaimer - DANGER!
This package can pose a serious security issue if used incorrectly, as anybody will be able to take control of any user's account. Please ensure that the service provider is only registered when the app is in a debug/local environment.

By default, the package will disable itself on any domains that don't have a TLD of `.dev` or `.local`. This is a security measure to reduce the risk of accidentally enabling the package in production. If you have a different TLD in development, you can edit the config option `sudosu.allowed_tlds`.

By using this package, you agree that VIA Creative and the contributors of this package cannot be held responsible for any damages caused by using this package.
