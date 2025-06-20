<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has language preference
        if (Auth::check() && Auth::user()->language) {
            $locale = Auth::user()->language;
            App::setLocale($locale);
            Session::put('locale', $locale);
        } 
        // Check for session locale (for guests or temporary changes)
        elseif (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        }
        // Check for URL parameter to change language
        elseif ($request->has('lang')) {
            $locale = $request->get('lang');
            if (in_array($locale, ['en', 'id'])) {
                App::setLocale($locale);
                Session::put('locale', $locale);
                
                // If user is authenticated, save to database
                if (Auth::check()) {
                    User::where('id', Auth::id())->update(['language' => $locale]);
                }
            }
        }
        // Default to English
        else {
            App::setLocale('en');
        }

        return $next($request);
    }
}
