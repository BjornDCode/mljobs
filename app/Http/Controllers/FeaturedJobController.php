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

        DB::table('jobs')->insert(array_merge($data['job'], [
            'featured' => 1,
            'company_logo' => $this->getImagePath($request)
        ]));

        return response(200);
    }

    private function getImagePath($request) 
    {
        if (! $request->logo || ! $request->file('logo')->isValid()) {
            return null;
        }

        return $request->file('logo')->store('images', 'public');
    }

}
