<?php

namespace App\Http\Controllers;

use App\Models\GymManager;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Gym;
use DataTables;

class GymManagerController extends Controller
{
    #			                        list Function                                       #
    #=======================================================================================#
    public function showGymManagers(Request $request)
    {
        if ($request->ajax()) {
            $data = User::role('gymManager')->withoutBanned()->get();;

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('actions', function ($row) {
                        $btn = '<a href="/admin/gymManager/'.$row->id.'" class="edit btn btn-primary btn-sm">View</a> ';
                        $btn .= '<a href="/admin/addEditManager/'.$row->id.'" class="edit btn btn-warning btn-sm">Edit</a> ';
                        $btn .= '<a href="/admin/gymManagerDel/'.$row->id.'" class="edit btn btn-danger btn-sm">Delete</a>';
                        $btn .= '<a href="/admin/gymManagers" onclick="banUser('.$row->id.')" class="btn btn-dark "><i class="fa fa-user-lock"></i></a>';

                        return $btn;
                    })
                    ->addColumn('avatar', function ($row) {
                        $avatar = "<img width='80' height='80' src='".$row->profile_image."' />";

                        return $avatar;
                    })
                    ->rawColumns(['actions','avatar'])
                    ->make(true);
        }

        return view('gymManager.list');
    }

    #			                        create Function                                     #
    #=======================================================================================#
    public function create()
    {
        $users = User::all();
        $gyms = Gym::all();
        $cities = City::all();
        return view('gymManager.create', [
            'users' => $users,
            'cities' => $cities,
            'gyms' => $gyms,
        ]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'email' => ['required'],
            'profile_image' => ['nullable', 'mimes:jpg,jpeg'],
            'city_id' => ['required'],
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
        $user->city_id = $request->city_id;
        $user->profile_image = $imageName;
        $user->assignRole('gymManager');
        $user->save();
        return redirect(route('showGymManagers'));
    }
    

    public function show($id)
    {
        $singleManager = User::findorfail($id);
        return view("gymManager.show", ['singleManager' => $singleManager]);
    }

    public function edit($id)
    {
        $singleManager= User::findorfail($id);
        $cities = City::all(); 

        return view("gymManager.edit", ['singleManager' => $singleManager, 'cities' => $cities]);
    }

    public function editManager(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:2'],
            'email' => ['required'],
            'profile_image' => ['nullable', 'mimes:jpg,jpeg'],
            'city_id' => ['required'],
        ]);

        if ($request->hasFile('profile_image') == null) {
            $imageName =  $request->profile_image_saved;
        } else {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'http://localhost:8000/imgs/' . $name;
        }

        $manager = User::findorfail($id);

        $manager->name = $request->name;
        $manager->email = $request->email;
        $manager->city_id = $request->city_id;
        $manager->profile_image = $imageName;
        $manager->update();

        return redirect(route('showGymManagers'));
    }
    

    public function delete($id)
    {
        $singleManager = User::findorfail($id);
        $singleManager->delete();
        return redirect(route('showGymManagers'));
    }
}