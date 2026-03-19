<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\StorefrontSetting;
use Illuminate\Support\Facades\Cache;

class AboutController extends Controller
{
    public function index()
    {
        $settings = Cache::remember('storefront_settings', 86400, function () {
            return StorefrontSetting::first() ?? new StorefrontSetting();
        });

        return view('buyer.about', compact('settings'));
    }
}