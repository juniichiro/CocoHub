<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\StorefrontSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function index()
    {
        
        $settings = StorefrontSetting::with([
            'productOne', 
            'productTwo', 
            'productThree', 
            'productFour'
        ])->first();

        return view('buyer.home', compact('settings'));
    }
}