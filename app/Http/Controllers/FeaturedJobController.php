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
            'logo' => 'nullable|image|dimensions:min_width=64,min_height=64',
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

        $job = $this->createJob($data, $request);

        return response()->json($job);
    }

    private function getImagePath($request) 
    {
        if (! $request->logo || ! $request->file('logo')->isValid()) {
            return null;
        }

        return $request->file('logo')->store('images', 'public');
    }

    private function createJob($data, $request) 
    {
        $customer = Customer::firstOrCreate([
            'email' => $data['email']
        ]);

        $job = Job::create(array_merge($data['job'], [
            'featured' => 1,
            'company_logo' => $this->getImagePath($request),
            'customer_id' => $customer->id
        ]));

        Mail::to($customer)->send(new FeaturedJobPurchased($job));

        return $job;
    }

}
