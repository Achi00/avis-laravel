<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserController extends Controller
{
    // fetch users
    public function index()
    {
        $users = DB::table('final_combined_names')->get();
        return response()->jsonUnescaped($users);
    }
    // update value in database
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
    // // get users with value in database
    // public function getUsersWithValue()
    // {
    //     $users = DB::table('final_combined_names')->whereNotNull('value')->get();
    //     return response()->json($users);
    // }
    // // count values
    // public function getValuesCount()
    // {
    //     $counts = DB::table('final_combined_names')
    //                 ->select('value', DB::raw('count(*) as total'))
    //                 ->whereIn('value', ['customer focus', 'ownership', 'innovation', 'integrity', 'passion'])
    //                 ->groupBy('value')
    //                 ->get();

    //     return response()->json($counts);
    // }
    // combined function
    public function getUsersWithValueAndCountValues()
    {
        // Fetch users with a non-null 'value'
        $usersWithValue = DB::table('final_combined_names')
                                ->whereNotNull('value')
                                ->get();

        // Count how many times each 'value' was selected
        $valueCounts = DB::table('final_combined_names')
                         ->select('value', DB::raw('count(*) as total'))
                         ->whereIn('value', ['customer focus', 'ownership', 'innovation', 'integrity', 'passion'])
                         ->groupBy('value')
                         ->get();

        // Combine both results into a single JSON response
        $response = [
            'usersWithValue' => $usersWithValue,
            'valueCounts' => $valueCounts,
        ];

        return response()->json($response);
    }

    public function getUsersWithInterestAndCycle()
{
    // Step 1: Attempt to find the first user with a new value (last_sent is NULL)
    $userWithNewValue = DB::table('final_combined_names')
                            ->whereNotNull('value')
                            ->whereNull('last_sent')
                            ->orderBy('id', 'asc') // or any other order preference
                            ->first();

    if ($userWithNewValue) {
        DB::table('final_combined_names')
            ->where('id', $userWithNewValue->id)
            ->update(['last_sent' => Carbon::now()]);
        return response()->json($userWithNewValue);
    }

    // Step 2: If all users have a last_sent value, find the one sent the longest time ago
    $oldestSentUser = DB::table('final_combined_names')
                        ->whereNotNull('value')
                        ->orderBy('last_sent', 'asc')
                        ->first();

    // If found, update and return
    if ($oldestSentUser) {
        DB::table('final_combined_names')
            ->where('id', $oldestSentUser->id)
            ->update(['last_sent' => Carbon::now()]);
        return response()->json($oldestSentUser);
    }

    // Step 3: If no users were found (all have been sent and there are no new values), reset
    DB::table('final_combined_names')
        ->update(['last_sent' => null]);

    // After resetting, fetch the first user again as the cycle starts over
    $firstUserAfterReset = DB::table('final_combined_names')
                                ->whereNotNull('value')
                                ->orderBy('id', 'asc')
                                ->first();

    // Update this user as being the first sent in the new cycle
    if ($firstUserAfterReset) {
        DB::table('final_combined_names')
            ->where('id', $firstUserAfterReset->id)
            ->update(['last_sent' => Carbon::now()]);
        return response()->json($firstUserAfterReset);
    }

    // If no users have a non-null value, return an appropriate response
    return response()->json(['message' => 'No users with selected values found'], 404);
}


}
