<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\TrainingSession;
use App\Models\Gym;
use App\Models\City;

use DataTables;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }


    public function showAttendance(Request $request)
    {
        if ($request->ajax()) {
            $data = Attendance::select('*');
            return DataTables::of($data)
                    ->addIndexColumn()->addColumn('user_name',function ($row){

                        return User::find($row->user_id)->name??"no name";

                    })
                    ->addColumn('user_email',function ($row){
                        return User::find($row->user_id)->email??"no name";
                    
                    }
                        
                        )
                    ->addColumn('session_name',function ($row){
                    
                        return TrainingSession::find($row->training_session_id)->name??"no session";
                        
                    }
                            
                         )
                    ->addColumn('gym',function ($row){
                        $user= User::find($row->user_id);
                        // Gym::find($user->gym_id)->name??"no Gym";
                        return Gym::find($user->gym_id)->name??"no Gym";

                        // Gym::find(4)??"no Gym";
                            
                    }
                                
                    )
                    ->addColumn('city',function ($row){
                        $user= User::find($row->user_id);
                        return City::find($user->city_id)->name??"no City";                                
                        }
                           )
                   ->addColumn('attendance_date',function ($row){

                        return explode(' ',$row->attendance_at)[0];                                
                        }
                            
                            )
                     ->addColumn('attendance_time',function ($row){
                         return explode(' ', $row->attendance_at)[1];
                     } )
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                        return $btn;
                    })
                    ->rawColumns(['action','user_name','user_email','session_name','gym','city','attendance_date','attendance_time'])
                    ->make(true);
        }

        return view('attendance.list');
    }

}