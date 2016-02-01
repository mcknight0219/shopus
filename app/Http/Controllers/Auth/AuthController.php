<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    
    public function getLogin()
    {
        return view('login')->with('loginError', []);
    }

    public function postLogin(Request $request)
    {
        $input  = $request->all();
        $email  = $input['email'];
        $pass   = $input['password'];
        if( strlen($email) === 0 )  return redirect()->action('Auth\AuthController@getLogin');
        
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

        if( strlen($email) === 0 ) {
            return redirect()->action('Auth\AuthController@getRegister');
        }

        if( count(User::where('email', $email)->get()) > 0 ) {
            return view('register')->with('registerError', ['Email has been registered']);
        }
        
        $user = new User($input);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
