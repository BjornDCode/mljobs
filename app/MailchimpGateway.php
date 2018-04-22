<?php 

namespace App;

use Newsletter;

class MailchimpGateway 
{

    public function subscribe($email) 
    {
        $response = Newsletter::subscribeOrUpdate($email);

        if (!Newsletter::lastActionSucceeded()) {
            return false;
        }

        return $response;
    }

}
