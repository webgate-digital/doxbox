<?php

namespace App\Http\Controllers;

class CustomerController extends Controller
{
    public function profile()
    {
        $me = session()->get('me');
        return view('customer.profile', compact('me'));
    }
}
