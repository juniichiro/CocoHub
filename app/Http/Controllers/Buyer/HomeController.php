<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\StorefrontSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the Buyer Homepage with dynamic storefront settings.
     */
    public function index()
    {
        // We eager load the product relationships (productOne, productTwo, etc.)
        // This ensures the featured section loads efficiently.
        $settings = StorefrontSetting::with([
            'productOne', 
            'productTwo', 
            'productThree', 
            'productFour'
        ])->first();

        // If no settings exist yet, we pass an empty object so the 
        // null-coalesce operators (??) in your Blade view handle the defaults.
        return view('buyer.home', compact('settings'));
    }
}