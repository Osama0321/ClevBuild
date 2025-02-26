<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{Country,Category,User,City};
use View;
use Illuminate\Support\Facades\Schema;

class SiteProvider extends ServiceProvider
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
        if (Schema::hasTable('migrations')) {
            $countries  = Country::where('is_active', 1)->get();
            $categories = Category::where('is_active', 1)->get();
            $companies = User::active()->where('user_type', 6)->get();
            $cities = City::select('city_id','city_name')->active()->get();
            View::share('categories', $categories);
            View::share('countries', $countries);
            View::share('companies', $companies);
            View::share('cities', $cities);
        }
    }
}
