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
    public const HOME = '/dashboard';

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
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:rector'])
                ->prefix('rector')
                ->name('rector.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/rector.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:bursary'])
                ->prefix('bursary')
                ->name('bursary.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/bursary.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:director'])
                ->prefix('director')
                ->name('director.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/director.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:dr'])
                ->prefix('dr')
                ->name('dr.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/dr.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:admission'])
                ->prefix('admission')
                ->name('admission.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/admission.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:hod'])
                ->prefix('hod')
                ->name('hod.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/hod.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:lecturer'])
                ->prefix('lecturer')
                ->name('lecturer.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/lecturer.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:corrector'])
                ->prefix('corrector')
                ->name('corrector.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/corrector.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:cidm-user'])
                ->prefix('cidm-user')
                ->name('cidm-user.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/cidm-user.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:cec-admin'])
                ->prefix('cec-admin')
                ->name('cec-admin.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/cec-admin.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:dean-sa'])
                ->prefix('dean-sa')
                ->name('dean-sa.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/dean-sa.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:faculty-dean'])
                ->prefix('faculty-dean')
                ->name('faculty-dean.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/faculty-dean.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:dpp-admin'])
                ->prefix('dpp-admin')
                ->name('dpp-admin.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/dpp-admin.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:exams-and-records'])
                ->prefix('exams-and-records')
                ->name('exams-and-records.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/exams-and-records.php'));

            Route::middleware(['web', 'auth:sanctum', 'access', 'role:revalidation-admin'])
                ->prefix('revalidation-admin')
                ->name('revalidation-admin.')
                ->namespace($this->namespace)
                ->group(base_path('routes/custom/revalidation-admin.php'));
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
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
