<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CsrfTokenController extends Controller
{
    public function generateToken()
    {
        // Generate a new CSRF token
        $newToken = csrf_token();

        // Store the new token in the session (optional)
        Session::put('_token', $newToken);

        // Return the new token as a JSON response
        return response()->json(['csrfToken' => $newToken]);
    }
}