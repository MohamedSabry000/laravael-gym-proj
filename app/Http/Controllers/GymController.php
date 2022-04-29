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
        $data = Gym::with('city.manager')->get();
        $role = Auth::user()->hasRole('admin');
        
        if ($request->ajax()) {        
           
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function($row){    
            // Crud operations
            $btn =  "<a href='/admin/gym/".$row->id."' class='btn btn-sm btn-primary'>View</a>";
            $btn .= "<a href='/admin/addEditGym/".$row->id."' class = 'btn btn-sm btn-success'>Edit</a>";
            $btn .= "<a href='/admin/deletegym/".$row->id."' class = 'btn btn-sm btn-danger'>Delete</a>";
            return $btn;
            })->addColumn('city_name', function($row){
                    // Check if city name not null
                    return !empty($row->city->name) ? $row->city->name : 'no city found';
            })->addColumn('avatar', function($row){
                    $avatar = "<img width='50' height='50' src='".$row->cover_image."' />";
                    return $avatar;
                    
            })->addColumn('created_at', function($row){
                    $date = $row->created_at->format('Y-m-d');
                    return $date;               
            })->addColumn('managername1', function($row){
                    $roleAdmin = Auth::user()->hasRole('admin');
            
                    $managerName = '';    
                    if( $roleAdmin ) {
                        $managerName = !empty($row->city)  ? !empty($row->city->manager) ? $row->city->manager->name : 'no manager' : 'no manager';
                    }
                    return  $managerName ;
            })->rawColumns(['action','avatar'])->make(true);
        }

        return view('gym.list',['role' => $role ]);
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