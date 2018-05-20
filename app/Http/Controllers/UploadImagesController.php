<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadImagesController extends Controller
{
    
    public function store(Request $request) 
    {
        $request->validate([
            'image' => 'required|image|dimensions:min_width=64,min_height=64'
        ]);

        return [
            'path' => $request->file('image')->store('images', 'public')
        ];
    }    


}
