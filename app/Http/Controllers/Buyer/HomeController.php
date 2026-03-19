<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\StorefrontSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // Add this

class HomeController extends Controller
{
    public function index()
    {
        $settings = Cache::remember('storefront_settings', 86400, function () {
            return StorefrontSetting::with([
                'productOne', 
                'productTwo', 
                'productThree', 
                'productFour'
            ])->first();
        });

        return view('buyer.home', compact('settings'));
    }
}