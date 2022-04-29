<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrainingSession;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class SessionsController extends Controller
{
    
    public function remaining_training_sessions()
    {
        return [
            'total_sessions' => Auth()->user()->total_sessions,
            'remain_session' => Auth()->user()->remain_session
        ];
    }

}


