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
                    'status_code' => 500,
                    'status' => false,
                    'message' => 'invalid credentials'
                ], 500);
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
                'status_code' => 500,
                'status' => false,
                'message' => 'could not create token'
            ], 500);
        }
    }

    public function postRegister(Request $request, RegisterForm_Rule $rules)
    {
        $token = JWTAuth::fromUser($request);
       
        try {
            $rules->validate($request->all());
            $this->user->registerUser($request, $token);

            return response()->json([
                    'status_code' => 200,
                    'status' => true,
                    'token' => $token,
                ]);
        } catch(ValidatorAPiException $e) {
            return response()->json([
                'status_code' => 500,
                'status' => false,
                'message' => $e->getErrors()
            ], 500);
        }
    }

    public function postLoginWithLinkedin(Request $request)
    {
        $user_linkedin = $request->get('user_info');
        $payload = JWTFactory::make($user_linkedin);
        $token = JWTAuth::encode($payload);
        if (! is_null($user_linkedin)) {
            $user = $this->user->getFirstDataWhereClause('linkedin_id', '=', $user_linkedin['linkedin_id']);
            if (empty($user)) {
                $user = $this->user->createUserFromOAuth($user_linkedin, $token);
            } else {
                $user = $this->user->getById($user->id);
                $this->user->updateUserFromOauth($user_linkedin, $token, $user->id);
            }
            Auth::login($user);
            $token = \JWTAuth::fromUser($user);
            $this->user->update(['token' => $token], $user->id);
            return response()->json([
                'status_code' => 200,
                'status' => true,
                'token' => $token,
            ]);
        }

        return response()->json([
            'status_code' => 401,
            'status' => false,
            'message' => 'could not create token'
        ], 401);
    }

    public function postForgetPassword(Request $request)
    {
        $email = $request->get('email');
        $user = $this->user->getFirstDataWhereClause('email', '=', $email);

        if (!is_null($user)) {
            $password = $this->randomPassword(8);
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

    function randomPassword($length) {
        $str = "";
        $characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
}