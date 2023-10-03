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

class VacationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request ): Response
    {
        $user_id = Auth::user()->id;
        $all_users=User::get();
        $user_type = Auth::user()->user_type;
        return Inertia::render('Vacation/Index', [  'vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                                        'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get(), 'users' => $all_users, 'userType'=>$user_type, 'userId'=>$user_id]);
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
    public function store(Request $request): RedirectResponse
    {
        //
        $validated = $request->validate([
            'request_type' => 'required|string|max:255',
            'reason' => 'required|string|max:255',
            'start_date'=> 'required',
            'end_date'=>'required',
            'start_time'=>'required',
            'end_time'=>'required'
        ]);

        $request->user()->vacations()->create($validated);

        return redirect(route('ferie.index'));
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
    public function edit(Vacation $vacation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vacation $vacation)
    {
        //
        $status = $request->input('data.status');

        Notification::where('notitype', $status)->delete();
        $example = "example";
        if( $status == 'Deleted')
            return response()->json(['vacations' => Vacation::with('user:id,name')->where('delflag', true)->latest()->get()]);
        else if( $status == 'All' )
            return response()->json(['vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', '!=' , 'Pending')->latest()->get()]);
        else
            return response()->json(['vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', $status)->latest()->get()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id) : Response
    {
        //
        $id_status = explode(":", $id);
        $real_id = $id_status[0];
        $status = $id_status[1];
        $user_id = $id_status[2];
        $auth_name = $id_status[3];
        $time_stamp = date('Y-m-d H:i:s');
        $userIdentification = Auth::user()->id;
        $notification = new Notification();
        $vacation = Vacation::find($real_id);
        $example = "example";

        if (!$vacation) {
            return response()->json(['message' => 'Vacation not found'], 404);
        }

        // Update the 'delflag' field instead of deleting
        if( $status == 'Deleted' )
            $vacation->update(['delflag' => true]);
        elseif( $status == 'DDeleted') 
            $vacation->delete();
        else
        {
            $notification->userid = $user_id;
            $notification->notititle = $auth_name.' '.$status.' your request.';
            $notification->notitype = $status;
            $notification->notified_at = $time_stamp;
            $notification->save();
            $vacation->update(['status' => $status]);
        }
        return Inertia::render('Vacation/Index', ['vacations' => Vacation::with('user:id,name')->where('delflag', false)->where('status', 'Pending')->latest()->get(),
                                                  'notifications' => Notification::select('*')->where('userid', $user_id)->latest()->get()]);
    }
}
