<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // show registration form
    public function create(){
        return view('user.register');
    }

    // store new users
    public function store(Request $request){
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' =>['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6'
        ]);

        // hash password
        $formFields['password'] = bcrypt($formFields['password']);

        // crete user
        $user = User::create($formFields);

        // login
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in successfully');
    }
}
