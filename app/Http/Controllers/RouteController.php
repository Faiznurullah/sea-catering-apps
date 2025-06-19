<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RouteController extends Controller
{
     
    public function beranda()
    {
        return view('pages.beranda');
    }

    public function menu()
    {
        return view('pages.menu');
    }

    public function subscription()
    {
        return view('pages.subscription');
    }

    public function contact()
    {
        return view('pages.contact');
    }

  
}
