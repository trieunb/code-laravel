<?php

namespace App\Services\PushNotif;

use PushNotification as NotifPusher;
use Queue;
use App\Contracts\DeviceContract;

class Notification
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var App\Services\PushNotif\Device
     */
    protected $device;

    /**
     * @param DeviceContract $device
     * @param string         $text         title of notification
     * @param array          $notifOptions notification option, such as: alert, sound ...
     * @param array          $customData   notification custom data, will be wrapped by 'custom' key
     */
    public function __construct(DeviceContract $device, $text, array $notifOptions = [], array $customData = [])
    {
        $this->device = new Device(
            $device->getDeviceId(),
            $device->getDevicePlatform()
        );
        $this->text = $text;
        if ($customData) {
            $notifOptions['custom'] = $customData;
        }
        $this->options = $notifOptions;
    }

    public function push()
    {
        $pushAdapter = null;

        if ($this->device->isIOSPlatform()) {
            $pushAdapter = NotifPusher::app('IOSApp');
        } elseif ($this->device->isAndroidPlatform()) {
            $pushAdapter = NotifPusher::app('AndroidApp');
        }

        if ($pushAdapter) {
            $pushAdapter->to($this->device->id)
                ->send($this->text, $this->options);
            return true;
        } else {
            throw new \Exception("The device platform is not support.", 1);
        }
    }

    public function pushLater()
    {
        Queue::push(function($job) {
            $this->push();
            $job->delete();
        });
    }
}
