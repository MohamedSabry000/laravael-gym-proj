<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Gym;
use App\Models\Revenue;
use App\Models\GymManager;

use DataTables;
use Illuminate\Support\Facades\DB;
class BannedUsersController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }


    public function showbannedUsers(Request $request)
    {

        if ($request->ajax()) {
            $data= DB::select("select * from users where banned_status = 1") ;
            return DataTables::of($data)
                    ->addIndexColumn()
        
                    ->addColumn('action', function ($row) {
                        $btn =  '<a href="/admin/bannedUsers/'.$row->id.'" class="edit btn btn-primary btn-sm">UnBan</a> ';
    
                        return $btn;
                    })->addColumn('avatar', function ($row) {
                        $avatar = "<img width='80' height='80' src='".$row->profile_image."' />";

                        return $avatar;
                    })
                    ->rawColumns(['action','avatar'])
                    ->make(true);
        }
        return view('bannedUsers.list');
    }
    public function UnBanUser($id){
        $user = User::find($id);
        if($user) {
            $user->banned_status = 0;
            $user->save();
        }
        return redirect(route('showbannedUsers'));

    }
}
