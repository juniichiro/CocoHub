<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the buyers with universal search.
     */
    public function index(Request $request)
    {
        $query = User::where('role_id', 2)->with('buyerDetail');

        // Universal Search Logic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Search in Users table
                $q->where('email', 'LIKE', "%$search%")
                  // Search in related BuyerDetail table
                  ->orWhereHas('buyerDetail', function($sq) use ($search) {
                      $sq->where('first_name', 'LIKE', "%$search%")
                        ->orWhere('last_name', 'LIKE', "%$search%")
                        ->orWhere('address', 'LIKE', "%$search%"); // Address/City search integrated here
                  });
            });
        }

        $clients = $query->get();

        // Removed the $cities mapping logic to keep the controller lean
        return view('seller.clients', compact('clients'));
    }

    /**
     * Remove the specified buyer from storage.
     */
    public function destroy(User $user)
    {
        if ($user->role_id == 2) {
            $user->delete();
            return back()->with('success', 'Client account deleted successfully.');
        }

        return back()->with('error', 'Unauthorized action.');
    }
}