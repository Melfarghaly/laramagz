<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Create a new controller instance.
     *
     * @return RedirectResponse
     */
    public function login()
    {
        $this->validate(request(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $fieldType = filter_var(request('username'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $login = [
            $fieldType => request('username'),
            'password' => request('password')
        ];

        if (auth()->attempt($login)) {
            return redirect()->intended('admin/dashboard');
        } else {
            return redirect()->route('login')
                ->withInput()
                ->withErrors('Username or Email And Password Are Wrong', 'login');
        }

        return redirect()->route('login');
    }
}
