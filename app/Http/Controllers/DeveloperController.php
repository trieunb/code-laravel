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
            $notifCustomData = [
                'type' => 'jobs_match'
            ];

            $notif = new \App\Services\PushNotif\Notification(
                $user->device,
                "We found new jobs suitable for you",
                [],
                $notifCustomData
            );
            $notif->push();
            $message = "Notification send";
        }
        return redirect('/developer')->with('message', $message);
    }

}
