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
        $dt = Carbon::now()->addDays(5);
        $exp = ['exp' => strtotime($dt)];
        try {
            if (! $token = JWTAuth::attempt($credentials, $exp)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }

    public function postRegister(UserRegisterRequest $request)
    {
        $validate = Validator::make($request->all(), $request->rules());
        if ($validate->fails()) {
            return redirect('auth.register')
                ->withInput()
                ->withErrors($validate);
        } else {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();
        } 
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
            // dd($result);die();
            if ( @$result['id']) {
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
