<?php 

namespace App;

use Newsletter;

class MailchimpGateway 
{

    public function subscribe($name, $email) 
    {
        $response = Newsletter::subscribeOrUpdate($email, ['NAME' => $name]);

        if (!Newsletter::lastActionSucceeded()) {
            return false;
        }

        return $response;
    }

}
