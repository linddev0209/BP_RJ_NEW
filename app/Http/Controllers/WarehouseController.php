<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Vacation;
use App\Models\Notification;
use App\Models\Products;
use App\Models\Instruments;
use App\Models\Vehicles;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\Requesters;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    //
    public function index( Request $request ): Response
    {
        $all_users = User::get();
        $user_type = Auth::user()->user_type;
        return Inertia::render('WarehouseManage/index', 
                                ['requesters' => Requesters::where('delflag', false)->latest()->get(), 'warehoses' => Warehouse::where('delflag', false)->latest()->get(),
                                 'products' => Products::select('*')->latest()->get(), 'instruments' => Instruments::select('*')->latest()->get(),
                                 'vehciles' => Vehicles::select('*')->latest()->get(), 'users' => $all_users, 'userType' => $user_type]);
    }

    public function edit( Request $request )
    {
        //
        $rawdata = $request->input('data.objdata');

        $isallowed = $rawdata['isAllowed'];
        $databaseid = $rawdata['databaseid'];
        $pivid = $rawdata['pivid'];
        $warehoseid = $rawdata['warehoseid'];
        $quantity = $rawdata['quantity'];
        $requesterid = $rawdata['requesterid'];
        $allowedNum = $rawdata['allowedNum'];
        $returnedNum = $rawdata['returnedNum'];

        if( $isallowed )
        {
            if( $databaseid == 1 )
            {
                Products::find($pivid)->increment('usedamount');
            }
            elseif( $databaseid == 2 )
            {
                Instruments::find($pivid)->increment('usedamount');
            }
            else
            {
                Vehicles::where('id', $pivid)->update(['used' => true]);
            }
            
            Warehouse::find($warehoseid)->increment('tempered');
            if( $quantity == $allowedNum && $databaseid == 1 )
            {
                Requesters::where('id', $requesterid)->update(['delflag' => true]);
                Warehouse::where('id', $warehoseid)->update(['delflag' => true]);

                if( $databaseid == 1 )
                {
                    Products::where('id', $pivid)->update(['usedamount' => 0]);
                }
            }
        }
        else
        {
            if( $databaseid == 1 )
            {
                Products::find($pivid)->increment('usedamount');
            }
            elseif( $databaseid == 2 )
            {
                Instruments::find($pivid)->increment('usedamount');
            }
            else
            {
                Vehicles::where('id', $pivid)->update(['used' => true]);
            }
            
            Warehouse::find($warehoseid)->increment('returned');

            if( $quantity == $returnedNum )
            {
                Requesters::where('id', $requesterid)->update(['delflag' => true]);
                Warehouse::where('id', $warehoseid)->update(['delflag' => true]);

                if( $databaseid == 1 )
                {
                    Products::where('id', $pivid)->update(['usedamount' => 0]);
                }
                elseif( $databaseid == 2 )
                {
                    Instruments::where('id', $pivid)->update(['usedamount' => 0]);
                }
                else
                {
                    Vehicles::where('id', $pivid)->update(['used' => false]);
                }
            }
        }

        return response()->json(['message' => 200], 200);
    }

    public function create( Request $request )
    {
        $rawdata = $request->input('data.objdata');

        $data = [];
        $dataRow = [];
        $dataRow['PIVid'] = $rawdata['PIVid'];
        $dataRow['PIVname'] = $rawdata['PIVname'];
        $dataRow['employee'] = $rawdata['employee'];
        $dataRow['PIVtype'] = $rawdata['PIVtype'];
        if( $dataRow['PIVtype'] != 3 )
            $dataRow['quantity'] = $rawdata['quantity'];

        $data1 = [];
        $dataRow1 = [];
        $dataRow1['username'] = $rawdata['employee'];
        $dataRow1['request_date'] = $rawdata['request_date'];
        $data1 = $dataRow1;

        try{
            Requesters::insert($data1);
            $dataRow['requesterid'] = Requesters::where('delflag', false)->latest()->get()->last()->id;
            $data = $dataRow;
            Warehouse::insert($data);

            // if( $rawdata['PIVtype'] == 1 )
            //     Products::where('id', $rawdata['PIVid'])->update(['usedamount' => $rawdata['quantity']]);
            // elseif( $rawdata['PIVtype'] == 2 )
            //     Instruments::where('id', $rawdata['PIVid'])->update(['usedamount' => $rawdata['quantity']]);
            // else
            //     Vehicles::where('id', $rawdata['PIVid'])->update(['used' => true]);
        
            // return response()->json(['state' => 111], 200);

        }catch (Exception $e) {
            dd($e);
            return response()->json(['error' => 404], 404);
        }
        //
    }

    public function update(Request $request, Vacation $vacation)
    {
        $rawdata = $request->input('data.objdata');
        $databaseid = $rawdata['databaseid'];
        $pivid = $rawdata['pivid'];

        $data = [];
        $dataRow = [];
        $dataRow['name'] = $rawdata['name'];
        if( $databaseid != 3 )
        {
            $dataRow['amount'] = $rawdata['amount'];
            $dataRow['barcode'] = $rawdata['barcode'];
        }
        else
        {
            $dataRow['targa'] = $rawdata['targa'];
            $dataRow['username'] = $rawdata['username'];
        }

        $dataRow['comments'] = $rawdata['comments'];
        $dataRow['mnm'] = $rawdata['mnm'];

        $curtime = date('Y-m-d H:i:s');
        $dataRow['dom'] = $curtime;
        $data = $dataRow;

        if( $databaseid == 1 )
            Products::where('id', $pivid)->update($data);
        elseif( $databaseid == 2 )
            Instruments::where('id', $pivid)->update($data);
        else
            Vehicles::where('id', $pivid)->update($data);
    }

    public function store(Request $request)
    {
        $rawdata = $request->input('data.objdata');
        $databaseid = $rawdata['databaseid'];

        $data = [];
        $dataRow = [];
        $dataRow['name'] = $rawdata['name'];
        if( $databaseid != 3 )
        {
            $dataRow['amount'] = $rawdata['amount'];
            $dataRow['barcode'] = $rawdata['barcode'];
        }
        else
        {
            $dataRow['targa'] = $rawdata['targa'];
            $dataRow['username'] = $rawdata['username'];
        }

        $dataRow['comments'] = $rawdata['comments'];
        $dataRow['mne'] = $rawdata['mne'];
        $dataRow['mnm'] = $rawdata['mnm'];

        $curtime = date('Y-m-d H:i:s');
        $dataRow['doi'] = $curtime;
        $dataRow['dom'] = $curtime;
        $data = $dataRow;

        if( $databaseid == 1 )
            Products::insert($data);
        elseif( $databaseid == 2 )
            Instruments::insert($data);
        else
            Vehicles::insert($data);

        return response()->json(['state' => 1], 200);
    }

    public function destroy(Request $request, $id)
    {
        // dd($id);
        $id_status = explode(":", $id);
        $databaseid = $id_status[0];
        $realid = $id_status[1];

        if( $databaseid == 1 )
        {
            Products::find($realid)->delete();
        }
        elseif( $databaseid == 2 )
        {
            Instruments::find($realid)->delete();
        }
        else
        {
            Vehicles::find($realid)->delete();
        }

        return response()->json(['message' => 'Success'], 200);
    }
}
