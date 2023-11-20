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

    //logout user
    public function logout(Request $request){
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have logged out successfully');
    }

    //login form
    public function login(){
        return view('user.login');
    }

    //user authentication
    public function authenticate(Request $request){
        $formFields = $request->validate([
            'email' =>['required', 'email'],
            'password' => 'required'
        ]);

        if(auth()->attempt($formFields)){
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You have logged in successfully');
        }

        return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
    }
}
