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
use Carbon\Carbon;;
use Hash;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

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

    public function getLoginWithLinkedin(Request $request)
    {
        $code = $request->get('code');
        $linkedinService = OAuth::consumer('Linkedin');
        if ( ! is_null($code))
        {
            $token = $linkedinService->requestAccessToken($code);
            $result = json_decode($linkedinService
                ->request('/people/~:(id,first-name,last-name,headline,member-url-resources,picture-url,location,public-profile-url,email-address)?format=json'), true);
<<<<<<< HEAD
            if ( @$result['id']) {
                $user = User::where('linkedin_id', $result['id'])->first();
                if ( ! $user) {
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
                    $user = User::findOrFail($user->id);
=======
           
            if ( $result['id']) {

                $user_login = $this->user->getFirstDataWhereClause('linkedin_id', '=', $result['id']);

                if ( !$user_login) {
                    $user = $this->user->createUserFromOAuth($result, $token->getAccessToken());
                } else {
                    $user = $this->user->getById($user_login->id);
>>>>>>> 5f2a6b95bbfd1da286db95ecf4d17a5860ef96ba
                    $user->token = $token->getAccessToken();
                    $user->save();
                }

                Auth::login($user, true);

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
}