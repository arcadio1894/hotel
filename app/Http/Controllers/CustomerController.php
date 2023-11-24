<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    function index(){
        return view('customer.index');
    }
    function report(){
        return view('customer.report');
    }

    function getDataCustomer(){
        return ;
    }
}
