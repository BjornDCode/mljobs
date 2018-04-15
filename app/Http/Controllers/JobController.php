<?php

namespace App\Http\Controllers;

use App\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    
    public function index(Request $request) 
    {
        $jobs = Job::latest();

        if ($filter = $request->query('filter')) {
            $filter = title_case(str_replace('-', ' ', $filter));
            $jobs->where('type', $filter);
        }

        return view('jobs.index', [
            'jobs' => $jobs->get()
        ]);
    }

    public function show($id) 
    {
        $job = Job::findOrFail($id);

        return view('jobs.show', [
            'job' => $job
        ]);
    }

}
