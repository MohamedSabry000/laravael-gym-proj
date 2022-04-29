<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GymRequest;
use App\Http\Requests\UpdateGymRequest;
use App\Models\Gym;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\File;

class GymController extends Controller
{   

    public function store(Request $request) 
    {
        $request->validate([
            'name'        => ['required', 'string', 'min:2'],
            
            'cover_image' => ['nullable', 'mimes:jpg,jpeg'],
            'city_id'     => ['required'],
        ]);

        $data = $request->all();
       
        if ($request->hasFile('cover_image') == null) {
                $imageName = 'imgs/defaultImg.jpg';
            } else {
                $image = $request->file('cover_image');
                $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/imgs');
                $image->move($destinationPath, $name);
                $imageName = 'http://localhost:8000/imgs/' . $name;
            }        //store the request data in the db
        Gym::create([
            'name'        => $data['name'],
            'cover_image' => $imageName,
            'city_id'     => $data['city_id'],
        ]);
        return redirect(route('showGyms'));
    }   

    public function list()
    {
        $gymsFromDB = Gym::all();
        if (count($gymsFromDB) <= 0) { //for gym empty statement
            return view('empty');
        }
        return view("gym.list", ['gyms' => $gymsFromDB]);
    }
    #=======================================================================================#
    #			                            Show Function                                 	#
    #=======================================================================================#

    public function show($id)
    {
        
        $singleGym = Gym::with('city')->find($id);
     
        return view("gym.show", ['singleGym' => $singleGym]);
    }
    #=======================================================================================#
    #			                           Create Function                              	#
    #=======================================================================================#
        public function create()
        {

            $city = City::all();
            $user = User::role('gymManager')->get();
        
            return view('gym.create', [
                'cityData' => $city,
                'users'    => $user
            ]);
            
        }
    #=======================================================================================#
    #			                           Store Function                                 	#
    #=======================================================================================#
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'min:2'],
    //         'cover_image' => ['nullable', 'mimes:jpg,jpeg'],
    //         'city_id' => ['required'],
    //     ]);
    //     if ($request->hasFile('cover_image') == null) {
    //         $imageName = 'imgs/defaultImg.jpg';
    //     } else {
    //         $image = $request->file('cover_image');
    //         $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
    //         $destinationPath = public_path('/imgs');
    //         $image->move($destinationPath, $name);
    //         $imageName = 'imgs/' . $name;
    //     }


    //     $requestData = request()->all();
    //     Gym::create([
    //         'name' => $requestData['name'],
    //         'city_id' => $requestData['city_id'],
    //         'cover_image' => $imageName,
    //     ]);
    //     return redirect()->route('gym.list');
    // }


    #=======================================================================================#
    #			                            Edit Function                             	    #
    #=======================================================================================#
    public function edit($id)

    {
        $users = User::with('gym');
        $cities = City::all();
        $singleGym = Gym::find($id);
        return view("gym.edit", ['gym' => $singleGym, 'users' => $users, 'cities' => $cities,]);
    }


    //Update Function
    public function editGym(Request $request, $id)
    {
        $gym = Gym::find($id);
        $request->validate([
            'name'         => 'required|max:20',
            'city_id'      => 'required',

            'cover_image'  => 'nullable|image|mimes:jpg,jpeg',
        ]);

        $gym->name = $request->name;
        if ($request->hasFile('cover_image') == null) {
            $imageName =  $request->cover_image_saved;
        } else {
            $image = $request->file('cover_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'http://localhost:8000/imgs/' . $name;
        }
        $gym = Gym::findorfail($id);

        $gym->name = $request->name;
        $gym->city_id = $request->city_id;
        $gym->cover_image = $imageName;
        $gym->update();
        return redirect(route('showGyms'));
    }

    //Delete Function
    public function delete($id)
    {

        $singleGym = Gym::find($id);
        $singleGym->delete();
        return redirect( route('showGyms') );
        // return response()->json(['success' => 'Record deleted successfully!']);
    }
}