<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\User\UserInterface;
use App\ValidatorApi\RegisterForm_Rule;
use App\ValidatorApi\ValidatorAPiException;
use Artdarek\OAuth\Facade\OAuth;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use JWTFactory;
use App\Models\User;

class AuthenticatesController extends Controller
{
    /**
     * UserInterface
     * @var $user
     */
    protected $user;

    public function __construct(UserInterface $user)
    {
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
                    'status_code' => 401,
                    'status' => false,
                    'message' => 'invalid credentials'
                ], 401);
            }

            $user = $this->user->getFirstDataWhereClause('email', '=', $request->input('email'));
            $this->user->update(['token' => $token], $user->id);

            return response()->json([
                'status_code' => 200,
                'status' => true,
                'token' => $token
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status_code' => 401,
                'status' => false,
                'message' => 'could not create token'
            ], 401);
        }
    }

    public function postRegister(Request $request, RegisterForm_Rule $rules)
    {
        $token = JWTAuth::fromUser($request);
       
        try {
            $rules->validate($request->all());

            $this->user->registerUser($request, $token);
            
            $user = $this->user->getFirstDataWhereClause('email', '=', $request->input('email'));
            $token = JWTAuth::fromUser($user);
            $this->user->update(['token' => $token], $user->id);

            return response()->json([
                    'status_code' => 200,
                    'status' => true,
                    'token' => $token,
                ]);
        } catch(ValidatorAPiException $e) {
            return response()->json([
                'status_code' => 401,
                'status' => false,
                'message' => $e->getErrors()
            ], 401);
        }
    }

    public function loginWithLinkedin(Request $request)
    {
        $token = $request->get('token');
        $url = "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,picture-url,picture-urls::(original),location,public-profile-url,email-address)?oauth2_access_token=".$token."&format=json";
        $response = json_decode(file_get_contents($url), true);
        $user = $this->user->getFirstDataWhereClause('email', '=', $response['emailAddress']);
        $linkedin = $this->user->getFirstDataWhereClause('linkedin_id', '=', $response['id']);
        
        if ( ! $user) {
            $user = $linkedin ? $linkedin :  $this->user->createUserFromOAuth($response, $token);
        } else if ( !$linkedin ) {
            $this->user->updateUserFromOauth($response, $token, $user->id);
        }
        
        Auth::login($user);
        $token = \JWTAuth::fromUser($user);
        $this->user->update(['token' => $token], $user->id);

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'token' => $token
        ]);
    }

    public function loginWithFacebook(Request $request)
    {
        $token = $request->get('token');
        $url = "https://graph.facebook.com/me?fields=picture.width(720).height(720),id,gender,first_name,email,birthday,last_name,link&access_token=".$token;
        $response = json_decode(file_get_contents($url), true);
        $user = $this->user->getFirstDataWhereClause('email', '=', $response['email']);
        $facebook = $this->user->getFirstDataWhereClause('facebook_id', '=', $response['id']);

        if ( ! $user) {
            $user = $facebook ? $facebook : $this->user->createUserFacebook($response, $token);
        } else if ( !$facebook ) {
            $this->user->updateUserFacebook($response, $token, $user->id);
            
        }
        
        Auth::login($user);
        $token = \JWTAuth::fromUser($user);
        $this->user->update(['token' => $token], $user->id);

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'token' => $token
        ]);
    }

    public function postForgetPassword(Request $request)
    {
        $email = $request->get('email');
        $user = $this->user->getFirstDataWhereClause('email', '=', $email);

        if (!is_null($user)) {
            $password = str_random(8);
            $this->user->update(['password' => Hash::make($password)], $user->id);

            \Mail::send('emails.forgetPassword', ['pass' => $password], function($m) use ($user) {
                $m->to($user->email, $user->firstname . ' ' . $user->lastname)
                  ->subject('Welcome');
            });

            return response()->json([
                'status_code' => 200,
                'status' => true,
                'message' => "Success! check your email to reset your password"
            ]);
        }

        return response()->json([
            'status_code' => 403,
            'status' => false,
            'message' => 'Invalid email'
        ], 403);
    }

    public function postChangePassword(Request $request)
    {
        $user = \JWTAuth::toUser('token');
        $password = $request->get('password');

        if ( $user ) {
            $this->user->update(['password' => Hash::make($password)], $user->id);
            
            return response()->json([
                'status_code' => 200,
                'status' => true,
                'message' => "Success! Password Change Requested"
            ]);
        }
    }

}