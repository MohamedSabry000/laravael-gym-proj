<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use DataTables;

class CityManagerController extends Controller
{

    #=======================================================================================#
    #			                           Create Function                              	#
    #=======================================================================================#
    public function create()
    {
        return view('cityManager.create', [
            'users' => User::all(),
        ]);
    }
    #=======================================================================================#
    #			                           Store Function                                	#
    #=======================================================================================#
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
            'password' => 'required |min:6',
            'email' => 'required|string|unique:users,email,',
            'national_id' => 'digits_between:10,17|required|numeric|unique:users',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg',
        ]);

 
        if ($request->hasFile('profile_image') == null) {
            $imageName = 'imgs/defaultImg.jpg';
        } else {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'http://localhost:8000/imgs/' . $name;
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->profile_image = $imageName;
        $user->national_id = $request->national_id;
        $user->assignRole('cityManager');
        $user->save();

        return redirect(route('showCityManager'));
    }


    #=======================================================================================#
    #			                           List Function                                	#
    #=======================================================================================#
    public function showCityManager(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('CityManagerName', function ($row) {
                        return User::find($row->manager_id)->name??"not assiend";
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="/admin/allCityManagers/'.$row->id.'" class="edit btn btn-primary btn-sm">View</a> ';
                        $btn .= '<a href="/admin/addEditCityManager/'.$row->id.'" class="edit btn btn-warning btn-sm">Edit</a> ';
                        $btn .= '<a href="/admin/delCityManagers/'.$row->id.'" class="edit btn btn-danger btn-sm">Delete</a>';
                        // $btn .= '<a href="/admin/allCityManagers" onclick="banUser('.$row->id.')" class="btn btn-dark "><i class="fa fa-user-lock"></i></a>';

                        return $btn;
                    })->addColumn('avatar', function ($row) {
                        $avatar = "<img width='80' height='80' src='".$row->profile_image."' />";

                        return $avatar;
                    }) ->rawColumns(['action','CityManagerName','avatar'])
                    ->make(true);
        }
        // return view(route('showCityManager'));


        return view('cityManager.list');
    }
    public function list()
    {
        $usersFromDB =  User::role('cityManager')->withoutBanned()->get();
        // $usersFromDB = User::all();
        // $usersFromDB =  User::role('cityManager')->get();
        if (count($usersFromDB) <= 0) { //for empty statement
            return view('empty');
        }
        return view("cityManager.list", ['users' => $usersFromDB]);
    }
    #=======================================================================================#
    #			                           Show Function                                	#
    #=======================================================================================#
    public function show($id)
    {
        $singleUser = User::findorfail($id);
        return view("cityManager.show", ['singleUser' => $singleUser]);
    }
    #=======================================================================================#
    #			                           Edit Function                                	#
    #=======================================================================================#
    public function edit($id)
    {
        $singleUser = User::find($id);
        return view("cityManager.edit", ['singleUser' => $singleUser]);
    }



    public function editCityManager(Request $request, $id)
    {
        $user = User::find($id);
        $request->validate([
            'name' => 'required|max:20',
            'password' => 'required |min:6',
            'email' => 'required|string|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg',
            'national_id' => 'digits_between:10,17|numeric|unique:users,national_id,' . $user->id,
        ]);


        if ($request->hasFile('profile_image') == null) {
            $imageName = 'imgs/defaultImg.jpg';
        } else {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'http://localhost:8000/imgs/' . $name;
        }
        $user->name = $request->name;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->profile_image = $imageName;
        $user->national_id = $request->national_id;
        $user->assignRole('cityManager');
        $user->update();
        return redirect(route('showCityManager'));
    }

    #=======================================================================================#
    #			                           Delete Function                                	#
    #=======================================================================================#
    public function deleteCityManager($id)
    {
        $singleUser = User::findorfail($id);
        $singleUser->delete();
        return redirect(route('showCityManager'));
    }
}
