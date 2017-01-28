<?php

namespace App\Http\Controllers\HALAteeqControllers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Created by PhpStorm.
 * User: ateeq-ahmed
 * Date: 28/1/17
 * Time: 6:06 PM
 */
class FreelancerController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function main()
    {
        if(Auth::check()) {
            $user = Auth::user()->name;
        }
        else{
            $user = 'Guest';
        }
        return view('freelancer.freelancerhome')->with('userName',$user);
    }

}