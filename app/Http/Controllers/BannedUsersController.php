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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BannedUsersController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    public function banUser($userID)
    {
        User::find($userID)->ban([
            'comment' => 'Banned by admin',
            'expired_at' => '2020-12-12',
        ]);
        return response()->json(['success' => 'Record deleted successfully!']);
    }

    public function showbannedUsers(Request $request)
    {
        if ($request->ajax()) {
            $userRole = Auth::user()->getRoleNames();
            $allBannedUser = 0;

            // $data= DB::select("select * from users where banned_status = 1") ;
            switch ($userRole['0']) {
                case 'admin':
                    $allBannedUser = User::role(['cityManager', 'gymManager', 'coach', 'user'])->onlyBanned()->get();
                    break;
                case 'cityManager':
                    $allBannedUser = User::role(['gymManager', 'coach', 'user'])->onlyBanned()->get();
                    break;
                case 'gymManager':
                    $allBannedUser = User::role(['coach', 'user'])->onlyBanned()->get();
                    break;
            }

            // dd($allBannedUser);

            return DataTables::of($allBannedUser)
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


    public function UnBanUser($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->banned_status = 0;
            $user->save();
        }
        return redirect(route('showbannedUsers'));
    }
}
