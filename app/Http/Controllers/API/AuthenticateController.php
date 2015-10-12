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
                    'status' => 0,
                    'success' => false,
                    'error_description' => 'invalid_credentials'
                ], 401);
            } else {
                $user = json_decode($this->user
                    ->getDataWhereClause('email', '=', $request->input('email')),true);
                $this->user->update(['oauth_token' => $token], $user[0]['id']);
                return response()->json([
                    'status' => 1,
                    'success' => true,
                    'token' => $token
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
        $token = JWTAuth::fromUser($request);
        $data = [
            'firstname' => $request->input('firstname'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'oauth_token' => $token,
        ];
        $user = $this->user->create($data);
        if ($user) {
            return response()->json([
                    'status' => 1,
                    'success' => true,
                    'token' => $token,
                ]);
        } else {
            return response()->json([
                'status' => 0,
                'success' => false,
                'error_description' => 'could_not_create_token'
            ], 500);
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
                        'oauth_token' => $token->getAccessToken(),
                    ]);
                    Auth::login($user, true);
                } else {
                    $user = User::findOrFail($user_login->id);
                    $user->oauth_token = $token->getAccessToken();
                    $user->save();
                    Auth::login($user, true);
                }
                return response()->json([
                    'status' => 1,
                    'success' => true,
                    'token' => $token->getAccessToken(),
                ]);
            } else {
                return response()->json([
                    'status' => 0,
                    'success' => false,
                    'error_description' => 'could_not_create_token'
                ], 500);
            }
        }
        else
        {
            $url = $linkedinService->getAuthorizationUri(['state'=>'DCEEFWF45453sdffef424']);
            return redirect((string)$url);
        }
    }
}
