<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Gym;
use App\Models\User;
use Illuminate\Support\Facades\File;
use DataTables;

class CoachController extends Controller
{
    #			                        list Function                                       #
    #=======================================================================================#
    public function list()
    {
        $coachesFromDB = User::role('coach')->withoutBanned()->get();
        if (count($coachesFromDB) <= 0) {
            return view('empty');
        }
        return view("coach.list", ['coaches' => $coachesFromDB]);
    }
    
    public function showCoaches(Request $request)
    {
        if ($request->ajax()) {
            $data = User::role('coach');

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {    
                        $btn = '<a href="/admin/allcoaches/'.$row->id.'" class="edit btn btn-primary btn-sm">View</a> ';
                        $btn .= '<a href="/admin/addEditCoach/'.$row->id.'" class="edit btn btn-warning btn-sm">Edit</a> ';
                        $btn .= '<a href="/admin/delCoach/'.$row->id.'" class="edit btn btn-danger btn-sm">Delete</a>';
    
                        return $btn;
                    })->addColumn('avatar', function ($row) {
                        $avatar = "<img width='80' height='80' src='".$row->profile_image."' />";

                        return $avatar;
                    })->rawColumns(['action','avatar'])
                    ->make(true);
        }
        
        return view('coach.list');
    }

    #			                        create Function                                     #
    #=======================================================================================#
    public function create()
    {
        $coaches = User::role('coach');
        $cities = City::all();
        return view('coach.create', [
            'users' => $coaches,
            'cities' => $cities,
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
        $user->assignRole('coach');
        $user->save();
        return redirect(route('showCoaches'));
    }

    
    #			                        show Function                                       #
    #=======================================================================================#
    public function show($id)
    {
        $singleCoach = User::findorfail($id);
        $cityCoach = City::find($singleCoach->city_id);
        if (is_null($cityCoach)) {
            $cityCoach = 'not assigned';
        } else {
            $cityCoach = $cityCoach->name;
        }

        return view("coach.show", ['singleCoach' => $singleCoach, 'cityCoach' => $cityCoach]);
    }

    #			                        Edit Function                                       #
    #=======================================================================================#
    public function edit($id)
    {
        $singleCoach = User::findorfail($id);
        $cities = City::all(); 

        return view("coach.edit", ['singleCoach' => $singleCoach, 'cities' => $cities]);
    }

    public function editCoach(Request $request, $id)
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

        $coach = User::findorfail($id);

        $coach->name = $request->name;
        $coach->email = $request->email;
        $coach->city_id = $request->city_id;
        $coach->profile_image = $imageName;
        $coach->update();

        return redirect(route('showCoaches'));
    }
    
    #=======================================================================================#
    #			                        Delete Function                                     #
    #=======================================================================================#
    public function delete($id)
    {
        $singleCoach = User::findorfail($id);
        $singleCoach->delete();
        return response()->json(['success' => 'Record deleted successfully!']);
    }

}
