<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StorefrontSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache; // Add this

class StorefrontController extends Controller
{
    public function index()
    {
        $settings = Cache::remember('storefront_settings', 86400, function () {
            return StorefrontSetting::first() ?? new StorefrontSetting();
        });

        $products = Product::orderBy('name', 'asc')->get();

        return view('seller.storefront', compact('settings', 'products'));
    }

    public function update(Request $request)
    {
        $allowedBadges = 'Featured,Best Seller,New Arrival,Limited,Sale,Eco-Friendly';

        $request->validate([
            'banner_title'      => 'required|string|max:255',
            'short_description' => 'required|string',
            'banner_badge'      => 'nullable|string|max:50',
            'main_image'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'featured_badge_1'  => "nullable|in:$allowedBadges",
            'featured_badge_2'  => "nullable|in:$allowedBadges",
            'featured_badge_3'  => "nullable|in:$allowedBadges",
            'featured_badge_4'  => "nullable|in:$allowedBadges",
        ]);

        $settings = StorefrontSetting::first();

        if (!$settings) {
            $settings = new StorefrontSetting();
            $settings->id = 1; 
        } 

        $settings->banner_title = $request->banner_title;
        $settings->short_description = $request->short_description;
        $settings->banner_badge = $request->banner_badge ?? 'Our Collection';

        for ($i = 1; $i <= 4; $i++) {
            $productKey = "featured_$i";
            $badgeKey = "featured_badge_$i";
            $settings->$productKey = $request->input($productKey);
            $settings->$badgeKey = $request->input($badgeKey) ?? 'Featured';
        }

        if ($request->hasFile('main_image')) {
            if ($settings->main_image && File::exists(public_path('images/storefront/' . $settings->main_image))) {
                File::delete(public_path('images/storefront/' . $settings->main_image));
            }
            $imageName = 'banner_' . time() . '.' . $request->main_image->extension();
            $request->main_image->move(public_path('images/storefront'), $imageName);
            $settings->main_image = $imageName;
        }

        $settings->save();

        Cache::forget('storefront_settings');

        return back()->with('status', 'Storefront settings successfully saved!');
    }
}