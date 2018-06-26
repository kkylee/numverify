<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function authenticate(Request $request)
    {
       $credentials = array(
            'name' => $request->get('name'),
            'password' => $request->get('password')
        );

        if (Auth::attempt($credentials)) 
        {
            return redirect()->intended('dashboard');
        }
        else
        {
            // if fails
            return back()->withErrors(['Invalid Login!']);
        }
    }
}
