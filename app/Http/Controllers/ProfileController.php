<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $view = $request->user()->role_id == 1 ? 'seller.profile' : 'buyer.profile';
        
        return view($view, [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->fill($request->validated());
        if ($user->isDirty('email')) { 
            $user->email_verified_at = null; 
        }
        $user->save();

        $detailsData = $request->only([
            'first_name', 
            'middle_name', 
            'last_name', 
            'phone_number', 
            'age', 
            'address'
        ]);

        $relation = $user->role_id == 1 ? 'sellerDetail' : 'buyerDetail';

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            
            $firstNameOnly = explode(' ', trim($request->first_name))[0];
            $cleanName = strtolower($firstNameOnly);
            $filename = $cleanName . '.' . $file->getClientOriginalExtension();

            $targetDir = public_path('images/profile');

            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            $oldPhoto = $user->$relation->profile_picture;
            if ($oldPhoto && File::exists($targetDir . '/' . $oldPhoto)) {
                File::delete($targetDir . '/' . $oldPhoto);
            }

            $file->move($targetDir, $filename);
            $detailsData['profile_picture'] = $filename;
        }

        $user->$relation()->update($detailsData);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $details = $user->role_id == 1 ? $user->sellerDetail : $user->buyerDetail;
        if ($details && $details->profile_picture) {
            $imagePath = public_path('images/profile/' . $details->profile_picture);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}