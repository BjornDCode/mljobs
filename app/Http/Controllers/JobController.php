<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    
    public function index() 
    {
        return view('jobs.index', [
            'jobs' => Job::latest()->get()
        ]);
    }

    public function show($id) 
    {
        $job = Job::findOrFail($id);

        return view('jobs.show', [
            'job' => $job->toArray()
        ]);
    }

}
