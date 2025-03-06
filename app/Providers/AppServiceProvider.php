<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api') // Gunakan middleware 'api' (throttle, bindings)
                ->prefix('api')      // Tambahkan awalan '/api' ke semua rute
                ->group(base_path('routes/api.php')); // Load file api.php

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
