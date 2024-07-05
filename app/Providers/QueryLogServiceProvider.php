<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class QueryLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (env('DB_LOG_QUERIES', false)) {
            DB::listen(function ($query) {
                Log::info(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }
    }
}
