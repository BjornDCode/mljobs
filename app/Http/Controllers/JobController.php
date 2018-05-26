<?php

namespace App\Http\Controllers;

use App\Job;
use App\Customer;
use Carbon\Carbon;
use App\StripeGateway;
use Illuminate\Http\Request;
use App\Mail\FeaturedJobPurchased;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\PaymentFailedException;


class JobController extends Controller
{
    
    public function index(Request $request) 
    {
        $jobs = Job::orderBy('featured', 'desc')->where('published', 1)->latest();

        if ($filter = $request->query('filter')) {
            $filter = title_case(str_replace('-', ' ', $filter));
            $jobs->where('type', $filter);
        }

        $groups = $this->mapJobsToGroups($jobs->get());

        return view('jobs.index', [
            'groups' => $groups
        ]);
    }

    public function show($id) 
    {
        $job = Job::findOrFail($id);

        if (! $job->published) {
            return redirect('/');
        }

        return view('jobs.show', [
            'job' => $job
        ]);
    }

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

    public function update($id, Request $request) 
    {
        $data = $request->validate([
            'title' => 'nullable',
            'description' => 'nullable',
            'company' => 'nullable',
            'location' => 'nullable',
            'salary' => 'nullable',
            'type' => 'nullable',
            'apply_url' => 'nullable|url',
            'logo' => 'nullable',
        ]);

        $job = Job::findOrFail($id);

        $job->update(array_merge($data, [
            'published' => 1,
        ]));

        return redirect('/dashboard');
    }

    public function delete($id) 
    {
        $job = Job::findOrFail($id);
        
        $job->delete();

        return redirect('/dashboard');
    }

    private function mapJobsToGroups($jobs) 
    {
        return $jobs->mapToGroups(function($item, $key) {
            if ($item->featured) {
                return ['Featured' => $item];
            }
            if ($item->created_at->isSameDay(Carbon::now())) {
                return ['Today' => $item];
            } else if ($item->created_at->isSameDay(Carbon::parse('-1 day'))) {
                return ['Yesterday' => $item];
            } else if ($item->created_at->addWeek()->isFuture()) {
                return ['This Week' => $item];
            } else if ($item->created_at->addMonth()->isFuture()) {
                return ['This Month' => $item];
            } else if ($item->created_at->addMonths(2)->isFuture()) {
                return ['Last Month' => $item];
            } else  {
                return ['Older' => $item];
            }

            return ['Misc' => $item];
        });
    }

    private function createJob($data) 
    {
         $customer = Customer::firstOrCreate([
            'email' => $data['email']
        ]);

        $job = Job::create(array_merge($data['job'], [
            'description' => markdown($data['job']['description']),
            'featured' => 1,
            'published' => 1,
            'company_logo' => array_key_exists('logo', $data) ? $data['logo'] : null,
            'customer_id' => $customer->id
        ]));

        Mail::to($customer)->send(new FeaturedJobPurchased($job));

        return $job;
    }

}
