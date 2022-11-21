<?php

namespace App\Http\Controllers;

use Session;

class CustomerController extends Controller
{
    public function profile()
    {
        $me = Session::get('me');

        return view('customer.profile', compact('me'));
    }
}
