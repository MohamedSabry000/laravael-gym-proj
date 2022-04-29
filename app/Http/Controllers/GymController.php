<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GymRequest;
use App\Http\Requests\UpdateGymRequest;
use App\Models\Gym;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\File;
use DataTables; 
use Illuminate\Support\Facades\Auth;


class GymController extends Controller
{   

    #=======================================================================================# 
    #			                           Store Function                                 	#
    #=======================================================================================#
    public function store(Request $request) 
    {
        $request->validate([
            'name'        => ['required', 'string', 'min:2'],
            'cover_image' => ['nullable', 'mimes:jpg,jpeg'],
            'city_id'     => ['required'],
        ]);

        $data = $request->all();
       
        if ($request->hasFile('cover_image') == null) {
                $imageName = 'http://localhost:8000/defaultImg.jpg';
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


      #=======================================================================================#
    #			                            List Function                             	    #
    #=======================================================================================#
    public function showGyms(Request $request)
    {
        
        if ($request->ajax()) {        
            // $data = Gym::all();
            return DataTables::of($data)->addIndexColumn() ->addColumn('action', function($row){    
            // Crud operations
            $btn =  "<a href='/admin/gym/".$row->id."' class='btn btn btn-primary'>View</a>";
            $btn .= "<a href='/admin/editgym/".$row->id."' class = 'btn btn-success'>Edit</a>";
            $btn .= "<a href='/admin/deletegym/".$row->id."' class = 'btn btn-danger'>Delete</a>";
            return $btn;
            })->addColumn('city_name', function($row){
            
                return !empty($row->city->name) ? $row->city->name : 'no city found';
            })->addColumn('avatar', function($row){
                $avatar = "<img width='80' height='80' src='".$row->cover_image."' />";
                return $avatar;
                
            })->addColumn('created_at', function($row){
                // $avatar = "<img width='80' height='80' src='".$row->cover_image."' />";
                return $date = $row->created_at->format('Y.m.d');
                
            })->rawColumns(['action','avatar'])->make(true);
        }

        return view('gym.list');
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
        $users = User::role('gymManager')->withoutBanned()->get();
        $cities = City::all();
        $singleGym = Gym::find($id);
        return view("gym.edit", ['gym' => $singleGym, 'users' => $users, 'cities' => $cities,]);
    }


    //Update Function
    public function update(Request $request, $id)
    {
        $gym = Gym::find($id);
        $validated = $request->validate([
            'name'         => 'required|max:20',
            'city_id'      => 'required',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg',
        ]);

        $gym->name = $request->name;

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'imgs/' . $name;
            if (isset($gym->cover_image))
                File::delete(public_path('imgs/' . $gym->cover_image));
            $gym->cover_image = $imageName;
        }
        $gym->save();
        
        return redirect()->route('gym.list');
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
