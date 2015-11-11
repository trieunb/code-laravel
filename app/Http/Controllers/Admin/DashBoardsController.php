<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;
use Auth;
use Validator;

class DashBoardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('csrf');
        // $this->middleware('role:admin');
    }

	public function index(Request $request)
	{
		return view('admin.dashboard.index');
	}

    public function getLogin()
    {
        return view('admin.auth.login');
    }

    public function postLogin(Request $request)
    {

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        $remember = $request->input('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect('admin/login')
                    ->withErrors(['message' => 'Wrong email or password.'])
                    ->withInput();
        }
    }

    public function getLogout(Request $request)
    {
        Auth::logout();

        return redirect('admin/login');
    }
} 