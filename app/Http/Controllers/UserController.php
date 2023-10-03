<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Vacation;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function index( Request $request ): Response
    {
        $user_id = Auth::user()->id;
        $all_users=User::get();
        $user_type = Auth::user()->user_type;
        return Inertia::render('UserManage/index', [  'vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                                        'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(), 'users' => $all_users, 'userType' => $user_type]);
    }
}
