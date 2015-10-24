<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
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
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Http\Requests\PasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Mail;

class AuthenticateController extends Controller
{

    use ResetsPasswords;

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
        $token = $request->get('token');
        $OAuth = new OAuth();
        $OAuth::setHttpClient('CurlClient');
        $linkedinService = $OAuth::consumer('Linkedin');
        // $linkedinService = OAuth::consumer('Linkedin');
        // $token = $linkedinService->requestAccessToken($linkedin_token);
        $result = json_decode($linkedinService
            ->request('/people/~:(id,first-name,last-name,headline,member-url-resources,picture-url,location,public-profile-url,email-address)?format=json'), true);
        return $result; die();

        if ( @$result['id']) {
            $user = $this->user->getFirstDataWhereClause('linkedin_id', '=', $result['id']);
            if ( !$user) {
                $user = $this->user->createUserFromOAuth($result, $token);
            } else {
                $user = $this->user->getById($user->id);
                $this->user->updateUserFromOauth($result, $token, $user->id);
            }
            Auth::login($user);
            $user = Auth::user();
            $token = \JWTAuth::fromUser($user);
            $this->user->update(['token' => $token], $user->id);
            return response()->json([
                'status_code' => 200,
                'status' => true,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'status_code' => 500,
                'status' => false,
                'message' => 'could not create token'
            ], 500);
        }
    }

    // public function postLoginWithLinkedin(Request $request)
    // {
    //     // get data from request
    //     $code = $request->get('code');

    //     $linkedinService = OAuth::consumer('Linkedin');


    //     if ( ! is_null($code))
    //     {
    //         // This was a callback request from linkedin, get the token
    //         $token = $linkedinService->requestAccessToken($code);

    //         // Send a request with it. Please note that XML is the default format.
    //         $result = json_decode($linkedinService->request
    //             (
    //                 '/people/~?format=json'
    //             ), true);

    //         // Show some of the resultant data
    //         echo 'Your linkedin first name is ' . $result['firstName'] . ' and your last name is ' . $result['lastName'];

    //         //Var_dump
    //         //display whole array.
    //         dd($result);

    //     }
    //     // if not ask for permission first
    //     else
    //     {
    //         // get linkedinService authorization
    //         $url = $linkedinService->getAuthorizationUri(['state'=>'DCEEFWF45453sdffef424']);

    //         // return to linkedin login url
    //         return redirect((string)$url);
    //     }
    // }

    public function postResetPassword(Request $request)
    {
        $email = $request->get('email');
        $user = $this->user->getFirstDataWhereClause('email', '=', $email);
        if (!is_null($user)) {
            $password = $this->randomPassword(8);
            $this->user->update(['password' => Hash::make($password)], $user->id);
            
            $response = Password::sendResetLink($request->only('email'), 
                function (Message $message) {
                    $message->subject('Your Password Reset Link');
            });
            switch ($response) {
                case Password::RESET_LINK_SENT:
                   return redirect()->back()->with('success', trans($response));

                case Password::INVALID_USER:
                   return redirect()->back()->withErrors(['email' => trans($response)]);
            }

            return response()->json([
                'status_code' => 200,
                'status' => true,
                'password' => $password
            ]);
        } else {
            return response()->json([
                'status_code' => 403,
                'status' => false,
                'message' => 'Invalid email'
            ], 403);
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