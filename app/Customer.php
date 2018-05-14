<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    
    protected $guarded = [];

    public function jobs() 
    {
        return $this->hasMany(Job::class);
    }

}
