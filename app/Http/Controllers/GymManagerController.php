<?php

namespace App\Http\Controllers;

use App\Models\GymManager;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use DataTables;


class GymManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showGymManager(Request $request)
    {
        if ($request->ajax()) {
            $data = User::role('gymManager');

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm">View</a>';

                            return $btn;
                    })
                    ->addColumn('avatar', function($row){

                    $avatar = "<img width='80' height='80' src='".$row->profile_image."' />";

                    return $avatar;
                })
                    ->rawColumns(['action','avatar'])
                    ->make(true);
        }

        return view('gymManager.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GymManager  $gymManager
     * @return \Illuminate\Http\Response
     */
    public function show(GymManager $gymManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GymManager  $gymManager
     * @return \Illuminate\Http\Response
     */
    public function edit(GymManager $gymManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GymManager  $gymManager
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GymManager $gymManager)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GymManager  $gymManager
     * @return \Illuminate\Http\Response
     */
    public function destroy(GymManager $gymManager)
    {
        //
    }
}
