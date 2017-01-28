<?php
/**
 * Created by PhpStorm.
 * User: ateeq-ahmed
 * Date: 28/1/17
 * Time: 6:09 PM
 */

namespace App\Http\Controllers\HALAteeqControllers;


use App\Http\Controllers\Controller;

class SBAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function main()
    {
        return view('sb-admin.main');
    }
}