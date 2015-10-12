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
use App\Repositories\User\UserInterface;

class AuthenticateController extends Controller
{
    protected $user;
    public function __construct(UserInterface $user)
    {
        //$this->middleware('jwt.auth', ['except' => ['authenticate']]);
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->user->getAll();
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
            $token = JWTAuth::attempt($credentials, $exp);
            if (! $token) {
                return response()->json([
                    'status_code' => 500,
                    'status' => false,
                    'message' => 'invalid credentials'
                ], 500);
            } else {
                $user = json_decode($this->user
                    ->getDataWhereClause('email', '=', $request->input('email')),true);
                $this->user->update(['token' => $token], $user[0]['id']);
                return response()->json([
                    'status_code' => 200,
                    'status' => true,
                    'token' => $token
                ]);
            }
        } catch (JWTException $e) {
            return response()->json([
                'status_code' => 500,
                'status' => false,
                'message' => 'could not create token'
            ], 500);
        }
    }

    public function postRegister(Request $request)
    {
        $token = JWTAuth::fromUser($request);
        $rules = [
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:45',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|min:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 500,
                'status' => false,
                'message' => 'The provided authorization grant is invalid'
            ], 500);
        } else {
            $data = [
                'firstname' => $request->input('firstname'),
                'lastname' => $request->input('lastname'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'token' => $token,
            ];
            $this->user->create($data);
            return response()->json([
                    'status_code' => 200,
                    'status' => true,
                    'token' => $token,
                ]);
        }
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
                        'token' => $token->getAccessToken(),
                    ]);
                    Auth::login($user, true);
                } else {
                    $user = User::findOrFail($user_login->id);
                    $user->token = $token->getAccessToken();
                    $user->save();
                    Auth::login($user, true);
                }
                return response()->json([
                    'status_code' => 200,
                    'status' => true,
                    'token' => $token->getAccessToken(),
                ]);
            } else {
                return response()->json([
                    'status_code' => 500,
                    'status' => false,
                    'message' => 'could not create token'
                ], 500);
            }
        }
        else
        {
            $url = $linkedinService->getAuthorizationUri(['state'=>'DCEEFWF45453sdffef424']);
            return redirect((string)$url);
        }
    }
    public function getLoginWithGoogle(Request $request)
    {
        // get data from request
        $code = $request->get('code');

        // get google service
        $googleService = \OAuth::consumer('Google');

        // check if code is valid

        // if code is provided get user data and sign in
        if ( ! is_null($code))
        {
            // This was a callback request from google, get the token
            $token = $googleService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

            $message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
            echo $message. "<br/>";

            //Var_dump
            //display whole array.
            dd($result);
        }
        // if not ask for permission first
        else
        {
            // get googleService authorization
            $url = $googleService->getAuthorizationUri();

            // return to google login url
            return redirect((string)$url);
        }
    }
}
