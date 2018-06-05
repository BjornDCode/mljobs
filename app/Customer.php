<?php

namespace App;

use App\Mail\FeaturedJobPurchased;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\PaymentFailedException;

class Customer extends Model
{
    
    protected $guarded = [];

    public function jobs() 
    {
        return $this->hasMany(Job::class);
    }

    public function purchaseJobListing($data, $gateway) 
    {
        try {
            $charge = $gateway->charge($data['token']);
        } catch (PaymentFailedException $e) {
            return NULL;
        }

        $job = Job::create(array_merge($data['job'], [
            'description' => markdown($data['job']['description']),
            'published' => 1,
            'customer_id' => $this->id
        ]));

        Payment::create([
            'job_id' => $job->id,
            'customer_id' => $this->id,
            'stripe_charge_id' => $charge->id(),
            'amount' => $charge->amount()
        ]);

        Mail::to($this)->send(new FeaturedJobPurchased($job));

        return $job;
    }

}
