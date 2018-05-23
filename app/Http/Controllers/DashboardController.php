<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index() 
    {
        return view('dashboard.index', [
            'jobs' => Job::where('published', 0)->latest()->get()
        ]);   
    }

    public function show($id) 
    {
        $job = Job::findOrFail($id);
        
        return view('dashboard.show', [
            'job' => $job
        ]);
    }

}
