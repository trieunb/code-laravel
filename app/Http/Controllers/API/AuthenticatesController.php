<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Repositories\User\UserInterface;
use App\Repositories\Device\DeviceInterface;
use App\ValidatorApi\RegisterForm_Rule;
use App\ValidatorApi\ChangePass_Rule;
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
use Crypt;

class AuthenticatesController extends Controller
{
    /**
     * UserInterface
     * @var $user
     */
    protected $user;
    protected $device;

    public function __construct(UserInterface $user, DeviceInterface $device)
    {
        $this->user = $user;
        $this->device = $device;
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

            $this->device->createOrUpdateDevice($user->id, $request->get('data_device'));

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

            $this->device->createOrUpdateDevice($user->id, $request->get('data_device'));

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
        $url = "https://api.linkedin.com/v1/people/~:(id,first-name,last-name,headline,picture-urls::(original),location:(name),public-profile-url,email-address,date-of-birth)?oauth2_access_token=".$token."&format=json";
        $response = json_decode(file_get_contents($url), true);
        $user = $this->user->getFirstDataWhereClause('linkedin_id', '=', $response['id']);
        $firstlogin = false;

        if ( ! $user) {
            if (isset($response['emailAddress'])) {
                $user = $this->user->getFirstDataWhereClause('email', '=', $response['emailAddress']);
                if ( ! $user) {
                    $user = $this->user->createUserFromOAuth($response, $token);
                    $firstlogin = true;
                } else {
                    $this->user->updateUserFromOauth($response, $token, $user->id);
                    $firstlogin = false;
                }
            } else {
                $user = $this->user->createUserFromOAuth($response, $token);
                $firstlogin = true;
            }
            
        }
        $this->device->createOrUpdateDevice($user->id, $request->get('data_device'));
        $token = \JWTAuth::fromUser($user);
        $this->user->updateUserLogin($user, $token);
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'firstlogin' => $firstlogin,
            'token' => $token
        ]);
        
    }

    public function loginWithFacebook(Request $request)
    {
        $token = $request->get('token');
        $url = "https://graph.facebook.com/me?fields=picture.width(720).height(720),id,gender,first_name,email,birthday,last_name,link&access_token=".$token;
        $response = json_decode(file_get_contents($url), true);
        \Log::info('test Login Face', ['response' => $response, 'token' => $token, 'url' => $url]);
        $user = $this->user->getFirstDataWhereClause('facebook_id', '=', $response['id']);
        $firstlogin = false;

        if ( !$user ) {
            if ( isset($response['email'] )) {
                $user = $this->user->getFirstDataWhereClause('email', '=', $response['email']);
                if ( ! $user) {
                    $this->user->createOrUpdateUserFacebook($response, $token, $id = null);
                    $firstlogin = true;
                } else {
                    $this->user->createOrUpdateUserFacebook($response, $token, $user->id);
                    $firstlogin = false;
                }
            } else {
                $this->user->createOrUpdateUserFacebook($response, $token, $id = null);
                $firstlogin = true;
            }
        }
        
        $user = $this->user->getFirstDataWhereClause('facebook_id', '=', $response['id']);
        $this->device->createOrUpdateDevice($user->id, $request->get('data_device'));
        $token = \JWTAuth::fromUser($user);
        $this->user->updateUserLogin($user, $token);
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'firstlogin' => $firstlogin,
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
            'message' => 'Email not found'
        ], 403);
    }

    public function postChangePassword(Request $request, ChangePass_Rule $rules)
    {
        try {
            $user = \JWTAuth::toUser($request->get('token'));
            $rules->validate($request->all());

            if (Hash::check($request->get('old_pass'), $user->password)) {
                
                $this->user->update(['password' => Hash::make($request->get('new_pass'))], $user->id);
                
                return response()->json([
                    'status_code' => 200,
                    'status' => true,
                    'message' => "Success! Password Change Requested."
                ]);
            }
            return response()->json([
                'status_code' => 403,
                'status' => false,
                'message' => 'Password is incorrect!'
            ], 403);

        } catch(ValidatorAPiException $e) {
            return response()->json([
                'status_code' => 401,
                'status' => false,
                'message' => $e->getErrors()
            ], 401);
        }
        
    }

}