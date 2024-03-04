<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $ModulesDir = __DIR__ . '/../Modules';
            $scanned_directory = array_diff(scandir($ModulesDir), array('..', '.'));
            foreach ($scanned_directory as $item) {
                $fullPath = $ModulesDir . '/' . $item . '/routes/api.php';
                if (file_exists($fullPath)) {
                    Route::middleware('api')
                        ->prefix('api')
                        ->group(base_path('app/Modules/' . $item . '/routes/api.php'));
                }
            }


            Route::middleware('web')
                ->prefix('api')
                ->group(base_path('app/Modules/Auth/routes/authWebRoutes.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('app/Modules/User/routes/web.php'));

            Route::middleware('web')
                ->prefix('api/v2/social')
                ->group(base_path('app/Modules/Social/routes/webSocialRoutes.php'));

//            Route::middleware('web')
//                ->namespace($this->namespace)
//                ->group(base_path('app/Modules/Voting/routes/web.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(200)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
