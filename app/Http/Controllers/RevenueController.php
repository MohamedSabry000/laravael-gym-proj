<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $adminRole = Auth::user()->hasRole('admin');
        $gymManagerRole = Auth::user()->hasRole('gymManager');
        $cityManagerRole = Auth::user()->hasRole('cityManager');

        if ($gymManagerRole) {
            if ($request->ajax()) {
                $gym_id = Auth::user()->gym_id;
    
                $allCollection = DB::select('SELECT u.name, u.email, t.name p_name, r.amount FROM `users` u  
                JOIN `gyms_training_packages` g on u.gym_id = g.gym_id
                join `revenues` r on g.training_package_id=r.training_package_id
                join `training_packages` t on t.id = r.training_package_id
                WHERE u.gym_id=' . $gym_id);
               
                return DataTables::of($allCollection)
                    ->addIndexColumn()
                    ->addColumn('user_name', function ($row) {
                        return $row->name;
                    })
                    ->addColumn('user_email', function ($row) {
                        return $row->email;
                    })
                    ->addColumn('package_name', function ($row) {
                        return $row->p_name;
                    })
                    ->addColumn('amount', function ($row) {
                        return $row->amount;
                    })
                    ->make(true);
            }
            $gym_id = Auth::user()->gym_id;
            
            $amount = DB::select('SELECT sum(`revenues`.`amount`) allAmount FROM `users`  
            JOIN `gyms_training_packages` on `users`.`gym_id` = `gyms_training_packages`.`gym_id` 
            join `revenues` on `gyms_training_packages`.`training_package_id`=`revenues`.`training_package_id`
            WHERE `users`.`gym_id`=' . $gym_id);

            if ($amount[0]->allAmount == null) {
                $amount = 0;
            } else {
                $amount = $amount[0]->allAmount;
            }
            return view('revenue.index', ['amount' => $amount, 'role' => 'gymManager']);
        } elseif ($cityManagerRole) {
            if ($request->ajax()) {
                $city_id = Auth::user()->city_id;
                $allCollection = DB::select('SELECT u.name, u.email, t.name p_name, r.amount, g.name gymName FROM `users` u  
                JOIN `gyms_training_packages` gtp on u.gym_id = gtp.gym_id
                join `revenues` r on gtp.training_package_id=r.training_package_id
                join `training_packages` t on t.id = r.training_package_id
                JOIN `gyms` g on g.id = gtp.gym_id
                WHERE u.city_id=' . $city_id);

                return DataTables::of($allCollection)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->name;
                })
                ->addColumn('user_email', function ($row) {
                    return $row->email;
                })
                ->addColumn('package_name', function ($row) {
                    return $row->p_name;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('gymName', function ($row) {
                    return $row->gymName;
                })
                ->make(true);
            }

            $city_id = Auth::user()->city_id;
            $amount = DB::select('SELECT sum(`revenues`.`amount`) allAmount FROM `users`  
            JOIN `gyms_training_packages` on `users`.`gym_id` = `gyms_training_packages`.`gym_id` 
            join `revenues` on `gyms_training_packages`.`training_package_id`=`revenues`.`training_package_id`
            WHERE `users`.`city_id`=' . $city_id);

            if ($amount[0]->allAmount == null) {
                $amount = 0;
            } else {
                $amount = $amount[0]->allAmount;
            }
            return view('revenue.index', ['amount' => $amount, 'role' => 'cityManager']);
        } elseif ($adminRole) {
            if ($request->ajax()) {
                $allCollection = DB::select('SELECT u.name, u.email, t.name p_name, r.amount, g.name gymName, c.name cityName FROM `users` u  
                JOIN `gyms_training_packages` gtp on u.gym_id = gtp.gym_id
                join `revenues` r on gtp.training_package_id=r.training_package_id
                join `training_packages` t on t.id = r.training_package_id
                JOIN `gyms` g on g.id = gtp.gym_id
                JOIN `cities` c on c.id = g.city_id');

                return DataTables::of($allCollection)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->name;
                })
                ->addColumn('user_email', function ($row) {
                    return $row->email;
                })
                ->addColumn('package_name', function ($row) {
                    return $row->p_name;
                })
                ->addColumn('amount', function ($row) {
                    return $row->amount;
                })
                ->addColumn('gymName', function ($row) {
                    return $row->gymName;
                })
                ->addColumn('cityName', function ($row) {
                    return $row->cityName;
                })
                ->make(true);
            }
            
            $amount = DB::select('SELECT sum(`revenues`.`amount`) allAmount FROM `users`  
            JOIN `gyms_training_packages` on `users`.`gym_id` = `gyms_training_packages`.`gym_id` 
            join `revenues` on `gyms_training_packages`.`training_package_id`=`revenues`.`training_package_id`;');

            if ($amount[0]->allAmount == null) {
                $amount = 0;
            } else {
                $amount = $amount[0]->allAmount;
            }
            return view('revenue.index', ['amount' => $amount, 'role' => 'admin']);
        }
    }
}
