<?php

namespace App\Http\Controllers;

use App\Job;
use App\Customer;
use Carbon\Carbon;
use App\StripeGateway;
use Illuminate\Http\Request;

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


        if ( $job->published || (auth()->check() && auth()->user()->isAdmin()) ) {
            return view('jobs.show', [
                'job' => $job
            ]);
        }

        return redirect('/');
    }

    public function create() 
    {
        return view('jobs.create');
    }

    public function store(Request $request, StripeGateway $gateway) 
    {
        if (auth()->check() && auth()->user()->isAdmin()) {
            $data = $this->validateAuthJobData($request);

            Job::create(array_merge($data, [
                'description' => markdown($data['description']),
                'published' => 1,
            ]));

            return redirect('/');
        }

        $data = $this->validateUserJobData($request);

        $customer = Customer::firstOrCreate([
            'email' => $data['email']
        ]);

        $job = $customer->purchaseJobListing($data, $gateway);

        return $job;
    }

    public function update($id, Request $request) 
    {
        $data = $this->validateAuthJobData($request);

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

    private function validateAuthJobData($request) 
    {
        return $request->validate([
            'title' => 'nullable',
            'description' => 'nullable',
            'company' => 'nullable',
            'company_logo' => 'nullable|string',
            'location' => 'nullable',
            'salary' => 'nullable',
            'type' => 'nullable',
            'apply_url' => 'nullable|url',
        ]);
    }

    private function validateUserJobData($request) 
    {
        return $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'job.title' => 'required',
            'job.description' => 'required',
            'job.company' => 'nullable',
            'job.company_logo' => 'nullable|string',
            'job.location' => 'nullable',
            'job.salary' => 'nullable',
            'job.type' => 'nullable',
            'job.apply_url' => 'required|url',
        ]);
    }

}
