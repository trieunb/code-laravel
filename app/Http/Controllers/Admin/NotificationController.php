<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PushNotif\Notification;
use App\Services\PushNotif\BulkNotification;
use App\Models\Device;
use App\Http\Requests\PushNotifRequest;
use App\Http\Requests\BulkPushNotifRequest;

class NotificationController extends Controller
{
    public function __construct(Device $deviceModel)
    {
        $this->deviceModel = $deviceModel;
    }

    /**
     * Push to a specified user.
     */
    public function push(PushNotifRequest $request)
    {
        $device = $this->deviceModel
            ->where(['user_id' => $request->get('id')])
            ->first();
        if ($device) {
            (new Notification(
                $device, $request->get('text')))->pushLater();
        }
    }

    /**
     * Push to specified or all users.
     */
    public function bulkPush(BulkPushNotifRequest $request)
    {
        $userIds = $request->get('ids');
        $devices = [];
        if (is_array($userIds)) {
            $devices = $this->deviceModel
                ->whereIn('user_id', $userIds)
                ->select('device_id', 'platform')
                ->get();
        } elseif ($userIds === '*') {
            $devices = $this->deviceModel
                ->select('device_id', 'platform')
                ->get();
        }
        if (count($devices)) {
            (new BulkNotification(
                $devices, $request->get('text')))->pushLater();
        }
    }
}
