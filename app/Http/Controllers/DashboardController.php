<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index() 
    {
        return view('dashboard.index', [
            'jobs' => Job::latest()->get()
        ]);   
    }

}
