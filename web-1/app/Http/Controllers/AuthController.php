<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    //
    public function showLogin() 
    {
        Log::channel('audit')->info('Bug');
        return view('login');
    }

    public function showProfile() 
    {
        $user = auth()->user();
        return view('profile')->with([
            "user" => $user
        ]);
    }

    public function showRegistration() 
    {
        return view('register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }    

    public function login(Request $request)
    {
        $rules = array(
            'username' => 'required',
            'password' => 'required|alphaNum|min:8'
        );
        
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('login')->withErrors($validator); 
        }
        else {
            $credentials = $request->only('username', 'password');            
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return Redirect::to('message')->withSuccess("Welcome back!");
            }
            else {
                $validator->errors()->add('customError', 'Username or password is invalid.');
                return Redirect::to('login')->withErrors($validator);
            }
        }
    }

    public function register(Request $request)
    {
        $rules = array(
            'username' => 'required|string|max:250|unique:users',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::to('register')->withErrors($validator);
        }
        else {
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
    
            return Redirect::to('login')
                ->withSuccess('You have successfully registered.');
        }
    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->route('id'));
        $user->update($request->all());
        return redirect()->back()->withSuccess('Profile has been updated.');
    }
}
