<?php
namespace App\Http\Controllers;

class DeveloperController extends Controller
{
    public function index()
    {
        return view('developer.index');
    }

    public function sendJobMatchNotification(\App\Repositories\User\UserEloquent $userRepo)
    {
        $user = $userRepo->getFirstDataWhereClause('email', '=' , \Request::input('email'));
        if (!$user) {
            $message = "User not found";
        } elseif(!$user->device) {
            $message = "User device not found!";
        } else {
            $notifOptions = [
                'data' => [
                    'type' => 'jobs_match'
                ]
            ];

            $notif = new \App\Services\PushNotif\Notification(
                $user->device,
                "We found " . rand(5, 10) . " jobs suitable for you",
                $notifOptions
            );
            $notif->push();
            $message = "Notification send";
        }
        return redirect('/developer')->with('message', $message);
    }

}
