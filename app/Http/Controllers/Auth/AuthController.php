<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Session;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }


    public function getLogout()
    {
        Auth::logout();
        return redirect()->action('CmsController@index');
    }
    
    public function getLogin(Request $request)
    {
        return view('login')->with('loginError', []);
    }

    public function postLogin(Request $request)
    {
        $input  = $request->all();
        $email  = $input['email'];
        $pass   = $input['password'];
        
        $user = User::where('email', $email)->first();
        if( $user === null ) {
            return view('login')->with('loginError', ['Email doesn\'t have an account associated with it']);
        }

        if( !$this->authenticateUser($user, $pass) ) {
            return view('login')->with('loginError', ['Incorrect password']);
        }

        Auth::login($user);
        return redirect()->action('CmsController@index');
    }

    public function getRegister()
    {
        return view('register')->with('registerError', []);
    }

    public function postRegister(Request $request)
    {
        $input = $request->all();
        $email = $input['email'];
        $password = $input['password'];

        if( User::where('email', $email)->first() ) {
            return view('register')->with('registerError', ['Email has been registered']);
        }
        
        try {
            $user = new User($input);
            $user->password = password_hash($password, PASSWORD_BCRYPT);
            $user->save();

            Auth::login($user);
            return redirect()->action('CmsController@index');
        } catch( Exception $e) {
            return view('erros.503');
        }
    }

    protected function authenticateUser($user, $password)
    {
        return password_verify($password, $user->password);
    }
}
