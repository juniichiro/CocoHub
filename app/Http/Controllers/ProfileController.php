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
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $view = $request->user()->role_id == 1 ? 'seller.profile' : 'buyer.profile';
        
        return view($view, [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // 1. Update User Table (Email)
        $user->fill($request->validated());
        if ($user->isDirty('email')) { 
            $user->email_verified_at = null; 
        }
        $user->save();

        // 2. Prepare Details Data
        $detailsData = $request->only([
            'first_name', 
            'middle_name', 
            'last_name', 
            'phone_number', 
            'age', 
            'address'
        ]);

        $relation = $user->role_id == 1 ? 'sellerDetail' : 'buyerDetail';

        // 3. Handle Profile Photo with New Path: images/profile
        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            
            // STRICT FIRST NAME logic
            $firstNameOnly = explode(' ', trim($request->first_name))[0];
            $cleanName = strtolower($firstNameOnly);
            $filename = $cleanName . '.' . $file->getClientOriginalExtension();

            // Define the specific profile directory
            $targetDir = public_path('images/profile');

            // Ensure the directory exists (prevents move errors)
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            // ORPHAN PREVENTION: Delete the old photo from images/profile
            $oldPhoto = $user->$relation->profile_picture;
            if ($oldPhoto && File::exists($targetDir . '/' . $oldPhoto)) {
                File::delete($targetDir . '/' . $oldPhoto);
            }

            // Move to the new sub-directory
            $file->move($targetDir, $filename);
            $detailsData['profile_picture'] = $filename;
        }

        // 4. Dynamic Relationship Update
        $user->$relation()->update($detailsData);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
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

    /**
     * Delete the logged-in user's account (Self-Deletion).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Cleanup Profile Image from images/profile
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