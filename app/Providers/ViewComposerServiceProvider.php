<?php

namespace App\Providers;

use App\Http\ViewComposers\LayoutComposer;
use Illuminate\Support\ServiceProvider;
use View;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composers([
            LayoutComposer::class => 'layouts.default',
        ]);
    }
}
