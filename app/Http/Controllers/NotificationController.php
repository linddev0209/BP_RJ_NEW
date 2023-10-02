<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Notification;
use App\Models\Vacation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request ): Response
    {
        if( Auth::user() != null )
        {
        $all_users=User::get();
        $user_id = Auth::user()->id;
        $user_type = Auth::user()->user_type;
        return Inertia::render('Dashboard', ['vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                             'acceptedVacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Accepted')->latest()->get(),
                                             'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(), 'users' => $all_users, 'userId' => $user_id, 'userType' => $user_type]);
        }
        else{
            $all_users=User::get();
            return Inertia::render('Dashboard', ['vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                             'acceptedVacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Accepted')->latest()->get(),
                                             'notifications' => Notification::select('*')->latest()->get(), 'users' => $all_users, 'userId' => $user_id, 'userType' => $user_type]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $vacation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $tnum)
    {
        dd("fdsfsd");
        $tnum = $request->input('tnum');
        $user_id = Auth::user()->id;
        return Inertia::visit('Vacation/Index', ['vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                             'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(),
                                             'tnum' => $tnum]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {

    }
}
