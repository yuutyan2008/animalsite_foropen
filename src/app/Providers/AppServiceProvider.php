<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use App\View\Composers\ThemesSidebarComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     *ゲートの定義
     */

    public function boot(): void
    {
        // 「layouts.partials.themes_sidebar」が @include されるたびに
        // ThemesSidebarComposer::compose() を自動で呼ぶように登録する
        View::composer('layouts.partials.themes_sidebar', ThemesSidebarComposer::class);

        // 「システム管理者(role=1)」の定義
        Gate::define('admin', function (User $user) {
            return (int)$user->role === 1;
        });

    }
}
