<?php 

namespace App;

class Charge 
{
    private $chargeId;
    private $amount;

    public function __construct($chargeId, $amount) {
        $this->chargeId = $chargeId;
        $this->amount = $amount;
    }

    public function id() 
    {
        return $this->chargeId;
    }

    public function amount() 
    {
        return $this->amount;
    }
}
