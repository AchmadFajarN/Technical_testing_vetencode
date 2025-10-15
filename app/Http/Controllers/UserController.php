<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{ 
    public function getBaristas() 
    {
        $barista = User::where('role', 'barista')
            -> where('active', 'true')
            ->get(['id', 'name']);
        
        return response()->json([
            "status"=> "success",
            "data"=> $barista
        ]);
    }  
}
