<?php

namespace App\Http\Controllers;

use App\Models\Vacation;
use Illuminate\Http\Request;

class VacationAjaxController extends Controller
{
    //
    public function index(Request $request)
    {
        // Validate and process the request data here
        $value = $request->input('status'); // Retrieve the value from the POST request

        // Query the database to retrieve vacation data based on the value
        $vacationData = Vacation::with('user:id,name')->where('status', $value)->get(); // Adjust the column name as needed
    
        return response()->json(['vacationD' => $vacationData]);
    }
}
