<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\TrainingSession;
use App\Models\Gym;
use App\Models\City;

use DataTables;
use Illuminate\Support\Facades\Auth;

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
                    ->addIndexColumn()->addColumn('user_name', function ($row) {
                        return User::find($row->user_id)? User::find($row->user_id)->name:"no name";
                    })
                    ->addColumn(
                        'user_email',
                        function ($row) {
                            return User::find($row->user_id)? User::find($row->user_id)->email:"no name";
                        }
                    )
                    ->addColumn(
                        'session_name',
                        function ($row) {
                            return TrainingSession::find($row->training_session_id)? TrainingSession::find($row->training_session_id)->name:"no session";
                        }
                    )
                    ->addColumn(
                        'gym',
                        function ($row) {
                            $adminRole = Auth::user()->hasRole('admin');
                            $cityManagerRole = Auth::user()->hasRole('cityManager');
                    
                            if ($adminRole || $cityManagerRole) {
                                $user= User::find($row->user_id);
                                return $user && Gym::find($user->gym_id)? Gym::find($user->gym_id)->name:"no Gym";
                            } else {
                                return "---";
                            }
                        }
                    )
                    ->addColumn(
                        'city',
                        function ($row) {
                            $adminRole = Auth::user()->hasRole('admin');
                            if ($adminRole) {
                                $user= User::find($row->user_id);
                                return $user && City::find($user->city_id)? City::find($user->city_id)->name:"no City";
                            } else {
                                return "---";
                            }
                        }
                    )
                   ->addColumn(
                       'attendance_date',
                       function ($row) {
                           return explode(' ', $row->attendance_at)[0];
                       }
                   )
                     ->addColumn('attendance_time', function ($row) {
                         return explode(' ', $row->attendance_at)[1];
                     })
                    ->rawColumns(['action','user_name','user_email','session_name','gym','city','attendance_date','attendance_time'])
                    ->make(true);
        }

        return view('attendance.list');
    }
}
