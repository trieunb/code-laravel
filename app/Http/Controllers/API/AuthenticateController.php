<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;;
use App\Models\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Hash;
use Auth;
use App\Http\Requests\UserRegisterRequest;
use Artdarek\OAuth\Facade\OAuth;

class AuthenticateController extends Controller
{
    public function __construct()
    {
        //$this->middleware('jwt.auth', ['except' => ['authenticate']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function getLogin()
    {
        return view('auth.login');
    }

    public function getRegister()
    {
        return view('auth.register');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $exp_datetime = Carbon::now()->addDays(5);
        $exp = ['exp' => strtotime($exp_datetime)];
        try {
            if (! $token = JWTAuth::attempt($credentials, $exp)) {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'error_description' => 'invalid_credentials'
                ], 401);
            } else {
                $user = User::where('email', $request->input('email'))->first();
                $user->oauth_token = compact('token')['token'];
                $user->exp_time_token = $exp_datetime;
                $user->save();
                return response()->json([
                    'status' => 1,
                    'success' => true,
                    'token' => compact('token')['token']
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status' => 0,
                'success' => false,
                'error_description' => 'could_not_create_token'
            ], 500);
        }
    }

    public function postRegister(UserRegisterRequest $request)
    {
        $user = new User();
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return redirect()->route('auth.login');
    }

    public function getLoginWithLinkedin(Request $request)
    {
        $code = $request->get('code');
        $linkedinService = OAuth::consumer('Linkedin');
        if ( ! is_null($code))
        {
            $token = $linkedinService->requestAccessToken($code);
            $result = json_decode($linkedinService
                ->request('/people/~:(id,first-name,last-name,headline,member-url-resources,picture-url,location,public-profile-url,email-address)?format=json'), true);
            if ( $result['id']) {
                $user_login = User::where('linkedin_id', $result['id'])->first();
                if ( !$user_login) {
                    $user = User::create([
                        'linkedin_id' => $result['id'],
                        'firstname' => $result['firstName'],
                        'lastname' => $result['lastName'],
                        'email' => $result['emailAddress'],
                        'avatar' => $result['pictureUrl'],
                        'country' => $result['location']["name"],
                    ]);
                    Auth::login($user, true);
                    return redirect('/');
                } else {
                    $user = User::findOrFail($user_login->id);
                    Auth::login($user, true);
                    return redirect('/');
                }
            }
        }
        else
        {
            $url = $linkedinService->getAuthorizationUri(['state'=>'DCEEFWF45453sdffef424']);
            return redirect((string)$url);
        }
    }
}
