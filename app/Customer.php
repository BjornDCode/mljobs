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
            $gateway->charge($data['token']);
        } catch (PaymentFailedException $e) {
            return response('The payment could not be processed', 422);
        }

        $job = Job::create(array_merge($data['job'], [
            'description' => markdown($data['job']['description']),
            'featured' => 1,
            'published' => 1,
            'customer_id' => $this->id
        ]));

        Mail::to($this)->send(new FeaturedJobPurchased($job));

        return $job;
    }

}
