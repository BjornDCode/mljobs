<?php

namespace App\Http\Controllers;

use App\Job;
use App\Customer;
use App\StripeGateway;
use Illuminate\Http\Request;
use App\Mail\FeaturedJobPurchased;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
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
            'email' => 'required|email',
            'logo' => 'nullable|string',
            'job.title' => 'required',
            'job.description' => 'required',
            'job.company' => 'nullable',
            'job.location' => 'nullable',
            'job.salary' => 'nullable',
            'job.type' => 'nullable',
            'job.apply_url' => 'required|url',
        ]);

        try {
            $gateway->charge($data['token']);
        } catch (PaymentFailedException $e) {
            return response('The payment could not be processed', 422);
        }

        return $this->createJob($data);
    }

    private function createJob($data) 
    {
         $customer = Customer::firstOrCreate([
            'email' => $data['email']
        ]);

        $job = Job::create(array_merge($data['job'], [
            'featured' => 1,
            'company_logo' => array_key_exists('logo', $data) ? $data['logo'] : null,
            'customer_id' => $customer->id
        ]));

        Mail::to($customer)->send(new FeaturedJobPurchased($job));

        return $job;
    }

}
