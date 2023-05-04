<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Docs\Entities\DocsDoc;
use Modules\Docs\Observers\DocsDocObserver;
use Modules\Users\Entities\User;
use Modules\Users\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 加载观察者
        $this->bootObservers();
    }

    // 加载观察者
    protected function bootObservers()
    {
        User::observe(UserObserver::class);
        DocsDoc::observe(DocsDocObserver::class);
    }
}
