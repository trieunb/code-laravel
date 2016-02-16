<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\SendNotificationFormRequest;
use App\Http\Requests\UserAnswersFormRequest;
use App\Models\Question;
use App\Repositories\Device\DeviceInterface;
use App\Repositories\User\UserInterface;
use App\Services\PushNotif\BulkNotification;
use App\Services\PushNotif\Notification;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    /**
     * UserInterface
     * @var $user
     */
    private $user;
    private $device;

    public function __construct(UserInterface $user,DeviceInterface $device)
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
        return view('admin.user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
    	$user = $this->user->getById($id);
        return view('admin.user.detail', compact('user', $user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->user->delete($id)
            ? response()->json(['status' => true])
            : response()->json(['status' => false]);
    }


    public function postAnswer(UserAnswersFormRequest $request)
    {
        try {
            $this->user->setPointForAnswer(\Auth::user()->id, $request->get('points'));

            return redirect()->route('admin.user.get.index')->with('message', 'Save data successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('message', $e->getMessage());
        }
    }

    public function dataTable()
    {
        return $this->user->dataTable();
    }

    public function postDelete(Request $request)
    {
        return $this->user->deleteMultiRecords($request->get('ids'))
            ? response()->json(['status_code' => 200])
            : response()->json(['status_code' => 400]);
    }

    public function getSendNotification()
    {
        return view('admin.user.send-notification');
    }

    public function postSendNotification(SendNotificationFormRequest $request)
    {
        if ($request->has('send_all')) {
            $devices = $this->device->getAll(['device_id', 'platform']);

            if (count($devices)) {
                (new BulkNotification(
                    $devices, $request->get('message'))
                )->pushLater();

                return redirect()->back()->with('message', 'Send notification successfully!');
            }
        } elseif ($request->has('user_id')) {
            $usersIds = $request->get('user_id');
            $devices = $this->device->getDataWhereIn('user_id', $usersIds, 
                ['device_id', 'platform']
            );

            if (count($usersIds) > 1) {
                (new BulkNotification(
                    $devices, $request->get('message'))
                )->pushLater();

                return redirect()->back()->with('message', 'Send notification successfully!');
            }
            if(count($usersIds)) {
                $device = $this->device->getFirstDataWhereClause('user_id', '=', $request->get('user_id')[0]);

                if ($device) {
                    (new Notification(
                        $device, $request->get('message'))
                    )->pushLater();

                    return redirect()->back()->with('message', 'Send notification successfully!');
                }
            }
        }

        return redirect()->back()->with('error', 'Chooses not select use for send notification!');
    }

    public function getLogin()
    {
        return view('user.login');
    }

    public function dashBoard(Request $reques)
    {
        return view('user.index');
    }

    public function postLogin(Request $request)
    {
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ];
        $remember = $request->input('remember');
        $user = $this->user->getFirstDataWhereClause('email', '=', $request->input('email'));
        
        if ($user && $user->is('user')) {
            if (Auth::attempt($credentials, $remember)) {
                return redirect()->route('user.dashboard');
            }
        }

        return redirect('user/login')
            ->withErrors(['message' => 'Wrong email or password.'])
            ->withInput();
        
    }

    public function loginWithFacebook(Request $request)
    {
        $code = $request->get('code');
        $fb = \OAuth::consumer('Facebook');

        if ( ! is_null($code))
        {
            $token = $fb->requestAccessToken($code);
            $result = json_decode($fb->request('/me'), true);

            if ( @$result['email']) {
                $user = $this->user->getFirstDataWhereClause('email', '=', $result['email']);
                if ( $user) {
                    Auth::login($user);
                    return redirect()->route('user.dashboard');
                } else {
                    return redirect('user/login')
                        ->withErrors(['message' => 'Wrong, please register app account with facebook.']);
                }
            }
        }
        else
        {
            $url = $fb->getAuthorizationUri();
            return redirect((string)$url);
        }
    }

    public function loginWithLinkedin(Request $request)
    {
        $code = $request->get('code');
        $linkedinService = \OAuth::consumer('Linkedin');

        if ( ! is_null($code))
        {
            $token = $linkedinService->requestAccessToken($code);
            $result = json_decode($linkedinService->request('/people/~:(id,first-name,last-name,headline,picture-urls::(original),location:(name),public-profile-url,email-address,date-of-birth)?format=json'), true);

            if ( @$result['emailAddress']) {
                $user = $this->user->getFirstDataWhereClause('email', '=', $result['emailAddress']);
                if ( $user) {
                    Auth::login($user);
                    return redirect()->route('user.dashboard');
                } else {
                    return redirect('user/login')
                        ->withErrors(['message' => 'Wrong, please register app account with linkedin.']);
                }
            }

        }
        else
        {
            $url = $linkedinService->getAuthorizationUri(['state'=>'DCEEFWF45453sdffef424']);
            return redirect((string)$url);
        }
    }

    public function getLogout()
    {
        Auth::logout();

        return redirect('user/login');
    }

    public function getProfile()
    {
        $user = \Auth::user();
        return view('user.profile.detail', compact('user', $user));
    }
}
