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
                        return User::find($row->manager_id)->name??"not assiend";
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="/admin/cities/'.$row->id.'" class="edit btn btn-primary btn-sm">View</a>';
    
                        return $btn;
                    })
                    ->rawColumns(['action','ManagerName'])
                    ->make(true);
        }
        
        return view('city.list');
    }

    #=======================================================================================#
    #			                          store Function                                   #
    #=======================================================================================#
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

    #=======================================================================================#
    #			                          show Function                                   	#
    #=======================================================================================#
    public function show($id)
    {
        $singleCity = City::findorfail($id);
        $cityManager = User::findorfail($singleCity->manager_id);
        return view("city.show", ['singleCity' => $singleCity, 'cityManager' => $cityManager->name]);
    }
    // public function show($cityID)
    // {
    //     $totalRevenue = 0;
    //     $gymsManagers = 0;
    //     $coaches = 0;
    //     $users = 0;

    //     $cityData = City::find($cityID);
    //     $userOfCity = $cityData->users;

    //     $citiesManagers = User::find($cityData->manager_id);

    //     foreach ($userOfCity as $usersID) {
    //         $totalRevenue += (Revenue::where('user_id', '=', $usersID['id'])->sum('price')) / 100;
    //     }
    //     $revenueInDollars = number_format($totalRevenue, 2, ',', '.');

    //     $gyms = count(Gym::where('city_id', '=', $cityID)->get());

    //     //get users by type in cityManager city
    //     foreach ($userOfCity as $singleUser) {
    //         if ($singleUser->hasRole('gymManager')) {
    //             $gymsManagers++;
    //         } elseif ($singleUser->hasRole('coach')) {
    //             $coaches++;
    //         } elseif ($singleUser->hasRole('user')) {
    //             $users++;
    //         }
    //     }
    //     return view("city.show", [
    //         'citiesManagers' => $citiesManagers,
    //         'gyms' => $gyms,
    //         'gymsManagers' => $gymsManagers,
    //         'coaches' => $coaches,
    //         'users' => $users,
    //         'revenueInDollars' => $revenueInDollars,
    //     ]);
    // }
    #=======================================================================================#
    #			                          create Function                                   #
    #=======================================================================================#
    public function create()
    {
        $cityManagers = $this->selectCityManagers();
        return view("city.create", ['cityManagers' => $cityManagers]);
    }
    
    #=======================================================================================#
    #			                          edit Function                                     #
    #=======================================================================================#
    public function edit($cityID)
    {
        $cityData = City::find($cityID);
        $cityManagers = $this->selectCityManagers();
        return view('city.edit', ['cityData' => $cityData, 'cityManagers' => $cityManagers]);
    }

    #=======================================================================================#
    #			                          destroy Function                                  #
    #=======================================================================================#
    public function destroy($cityID)
    {
        $city = City::find($cityID);
        $city->delete($cityID);
        return $this->list();
    }
    #=======================================================================================#
    #			                 restored deleted Cities Function                           #
    #=======================================================================================#
    public function showDeleted()
    {
        $deletedCity = City::onlyTrashed()->get();
        if (count($deletedCity) <= 0) { //for empty statement
            return view('empty');
        }
        return view('city.showDeleted', ['deletedCity' => $deletedCity]);
    }
    #=======================================================================================#
    #			                 restore deleted Cities Function                            #
    #=======================================================================================#
    public function restore($cityID)
    {
        City::withTrashed()->find($cityID)->restore();
        return $this->showDeleted();
    }

    #=======================================================================================#
    #			            private Function used in this controller                        #
    #=======================================================================================#
    private function selectCityManagers()
    {
        return User::select('users.*', 'cities.manager_id')
            ->role('cityManager')
            ->leftJoin('cities', 'users.id', '=', 'cities.manager_id')

            ->get();
    }
    #=======================================================================================#
    #                                      store
}
