<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Post;
use App\Observers\PostObserver;

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
        Paginator::useBootstrap();

        //1. @can
        //2. Gate::allows()
        Gate::define('admin', function($user){
            return $user->role_id === User::ADMIN_ROLE_ID;
        });

        
        {
            Post::observe(PostObserver::class);
        }


    }
}
