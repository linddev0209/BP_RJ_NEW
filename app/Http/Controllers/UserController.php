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
                                                        'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(), 'users' => $all_users, 'userType' => $user_type, 'userId'=>$user_id]);
    }

    public function update( Request $request, $employee_id ): Response{
        $user_id = Auth::user()->id;
        User::where('id', $employee_id)->update(['authorization'=>true]);
        $all_users=User::get();
        $user_type = Auth::user()->user_type;
        
        return Inertia::render('UserManage/index', [  'vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                                        'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(), 'users' => $all_users, 'userType' => $user_type, 'userId'=>$user_id]);
    }

    public function updateInfo( Request $request, $employee_id ): Response{
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;
        User::where('id', $employee_id)->update(['name'=>$request->userName,'email'=>$request->userEmail]);
        $all_users=User::get();
        
        return Inertia::render('UserManage/index', [  'vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                                        'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(), 'users' => $all_users, 'userType' => $user_type, 'userId'=>$user_id]);
    }

    public function deleteUserInfo( Request $request, $employee_id ): Response{
        $user_id = Auth::user()->id;
        $all_users=User::get();
        $user_type = Auth::user()->user_type;
        User::where('id', $employee_id)->delete();
        
        return Inertia::render('UserManage/index', [  'vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                                        'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(), 'users' => $all_users, 'userType' => $user_type, 'userId'=>$user_id]);
    }
}
