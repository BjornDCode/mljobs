<?php

namespace App\Http\Controllers;

use App\StripeGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\PaymentFailedException;

class FeaturedJobController extends Controller
{

    public function create() 
    {
        return view('jobs.create');
    }
    
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

        try {
            $gateway->charge($data['token']);
        } catch (PaymentFailedException $e) {
            return response('The payment could not be processed', 422);
        }

        DB::table('jobs')->insert($data['job']);
    }

}
