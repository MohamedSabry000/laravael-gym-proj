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

class CityController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    #=======================================================================================#
    #			                          list Function                                   	#
    #=======================================================================================#
    public function list()
    {
        $allCities = City::all();
        if (count($allCities) <= 0) { //for empty statement
            return view('empty');
        }
        return view("city.list", ['allCities' => $allCities]);
    }

    public function showCites(Request $request)
    {
        if ($request->ajax()) {
            $data = City::select('*');
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('ManagerName', function ($row) {
                        return User::find($row->manager_id)? User::find($row->manager_id)->name:"not assiend";
                    })
                    ->addColumn('action', function ($row) {
                        $btn =  '<a href="/admin/cities/'.$row->id.'" class="edit btn btn-primary btn-sm">View</a> ';
                        $btn .= '<a href="/admin/addEditCity/'.$row->id.'" class="edit btn btn-warning btn-sm">Edit</a> ';
                        $btn .= '<a href="/admin/delCities/'.$row->id.'" class="edit btn btn-danger btn-sm">Delete</a>';

                        return $btn;
                    })
                    ->rawColumns(['action','ManagerName'])
                    ->make(true);
        }
        return view('city.list');
    }

    #			                          store Function                                   #
    #=======================================================================================#
    public function create()
    {
        $cityManagers = $this->selectCityManagers();
        return view("city.create", ['cityManagers' => $cityManagers]);
    }
    
    public function store(Request $request)
    {
        $requestData = request()->all();
        if ($requestData['manager_id'] == 'optional') {
            City::create([
                'name' => $requestData['name'],
            ]);
        } else {
            City::create($requestData);
        }
        return redirect(route('showCites'));
    }

    #			                          show Function                                   	#
    #=======================================================================================#
    public function show($id)
    {
        $singleCity = User::findorfail($id);
        $cityManager = City::find($singleCity->manager_id);
        if (is_null($cityManager)) {
            $cityManager = 'not assigned';
        } else {
            $cityManager = $cityManager->name;
        }
        return view("city.show", ['singleCity' => $singleCity, 'cityManager' => $cityManager]);
    }
    
    #			                          destroy Function                                  #
    #=======================================================================================#
    public function delete($id)
    {
        $city = City::findorfail($id);
        $city->delete();
        return redirect(route('showCites'));
    }

    #			                          edit Function                                     #
    #=======================================================================================#
    public function edit($id)
    {
        $singleCity = City::findorfail($id);
        $cityManagers = $this->selectCityManagers();
        return view("city.edit", ['singleCity' => $singleCity, 'cityManagers' => $cityManagers]);
    }

    public function editCity(Request $request, $id)
    {
        $requestData = request()->all();
        $city = City::findorfail($id);
        $city->update($requestData);
        return redirect(route('showCites'));
    }

    #=======================================================================================#
    #			            private Function used in this controller                        #
    #=======================================================================================#
    private function selectCityManagers()
    {
        return User::select('users.*', 'cities.manager_id')
            ->role('cityManager')
            ->leftJoin('cities', 'users.id', '=', 'cities.manager_id')
            ->where('cities.manager_id', '=', null)
            ->get();
    }
    #=======================================================================================#
}
