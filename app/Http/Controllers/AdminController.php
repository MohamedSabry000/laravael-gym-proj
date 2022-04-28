<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Gym;
use DataTables;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    // public function index(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $data = User::select('*');
    //         return DataTables::of($data)
    //                 ->addIndexColumn()
    //                 ->addColumn('action', function($row){

    //                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

    //                         return $btn;
    //                 })
    //                 ->rawColumns(['action'])
    //                 ->make(true);
    //     }

    //     return view('users');
    // }

    public function showUsers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('allUsers.show');
    }
    public function showCites(Request $request)
    {
        if ($request->ajax()) {
            $data = City::select('*');
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('city.list');
    }
    public function showGyms(Request $request) {
     
        $data = Gym::with('city')->get();
        $data = Gym::all();

        if ($request->ajax()) {        
            // $data = Gym::all();
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
                        
                        // Crud operations
                        $btn =  "<a href='#' class='btn btn btn-primary'>View</a>";
                        $btn .= "<a href = '".$row->id."' class = 'btn btn-success'>Edit</a>";
                        $btn .= "<a href = '/admin/deletegym/".$row->id."' class = 'btn btn-danger'>Delete</a>";
                          return $btn;
                    })->addColumn('city_name', function($row){
            
                        // $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';
                        // return $row->city->name;
                    })->addColumn('avatar', function($row){
                        $avatar = "<img width='80' height='80' src='".$row->cover_image."' />";
                        return $avatar;
                        
                    })->rawColumns(['action','avatar'])->make(true);
        }

        return view('gym.list');
    }
}
