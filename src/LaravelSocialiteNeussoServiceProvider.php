<?php
namespace Wuwx\LaravelSocialiteNeusso;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use phpCAS;

class LaravelSocialiteNeussoServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Socialite::extend('neusso', function() {
            return new class {
                public function __construct()
                {
                    phpCAS::client(CAS_VERSION_2_0, "sso.neu.cn", 443, "cas");
                    phpCAS::setNoCasServerValidation();
                }
                public function redirect()
                {
                    phpCAS::setFixedServiceURL(phpCAS::getServiceURL() . "/callback");
                    return redirect(phpCAS::getServerLoginURL());
                }
                public function user()
                {
                    phpCAS::forceAuthentication();
                    return (new User)->map([
                        'nickname' => phpCAS::getUser(),
                    ]);
                }
            };
        });
    }

    public function register()
    {
        //
    }
}
