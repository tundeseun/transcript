<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\PrevApp;
use App\Models\ZmainApp;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
{
    View::composer('*', function ($view) {
        if (request()->path() !== '/') {            
            $user = Auth::user();
            $matric = $user->matric;
            $prevApp = PrevApp::where('matric', $matric)->first();
            if (!$prevApp) {
                return response()->json(['error' => 'User not found in prev_app table.'], 404);
            }
            $user_id = $prevApp->user_id;

            $cartItems = Cart::where('matric', $matric)->get();
            $cartItemCount = $cartItems->count();
            $zmain = ZmainApp::where('user_id', $user_id)->get('user_id');

            $view->with([
                'cartItemCount' => $cartItemCount,
                'zmain' => $zmain,
            ]);
        }
    });
}

}
