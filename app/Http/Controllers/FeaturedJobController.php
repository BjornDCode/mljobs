<?php

namespace App\Http\Controllers;

use App\StripeGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeaturedJobController extends Controller
{
    
    public function store(Request $request, StripeGateway $gateway) 
    {
        $data = $request->validate([
            'token' => 'required',
            'job.title' => 'required',
            'job.description' => 'required',
            'job.company' => 'nullable',
            'job.company_logo' => 'nullable|url',
            'job.location' => 'nullable',
            'job.salary' => 'nullable',
            'job.type' => 'nullable',
            'job.apply_url' => 'required|url',
            'job.featured' => 'required|boolean',
        ]);

        $gateway->charge($data['token']);

        // dd($data['job']);

        DB::table('jobs')->insert($data['job']);
    }

}
