<?php

namespace App\Http\Controllers;

use App\MailchimpGateway;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
   
    public function store(Request $request, MailchimpGateway $gateway) 
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        $gateway->subscribe($data['email']);

        return response(200);
    }

}
