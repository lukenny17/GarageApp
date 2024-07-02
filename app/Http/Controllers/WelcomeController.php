<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(){

        // $user = new User([
        //     'name' => 'Carol',
        //     'email' => 'carol@example.com',
        //     'password' => 'password1', //password appears to be automatically hashed when reviewing in db
        //     'role' => 'custmomer' //if this changes, then needs to be saved as new employee/admin object instead
        // ]);
        // $user -> save();

        // $customer = new Customer([
        //     'user_id' => $user->id,
        // ]);
        // $customer->save();

        return view('welcome');
    }
}
