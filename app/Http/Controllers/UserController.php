<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = DB::table('final_combined_names')->get();
        return response()->jsonUnescaped($users);
    }
    public function updateUserValue(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'value' => 'required', // Ensuring 'value' is provided in the request
        ]);

        // Attempt to update the user's value in the database
        $affected = DB::table('final_combined_names')
            ->where('id', $id) // Matching the user's ID
            ->update(['value' => $validated['value']]); // Updating the 'value'

        // Check if any row was actually updated
        if ($affected === 0) {
            // No user was found with the provided ID, or no new data was provided
            return response()->json(['error' => 'User not found or no update required.'], 404);
        }

        // Successfully updated the user's value
        return response()->json(['message' => 'User updated successfully.'], 200);
    }
}
