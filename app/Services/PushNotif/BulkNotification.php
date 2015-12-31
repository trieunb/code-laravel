<?php

namespace App\Services\PushNotif;

use Queue;
use PushNotification as NotifPusher;
use Illuminate\Contracts\Support\Arrayable;

class BulkNotification
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
     * @var array
     */
    protected $devices;

    public function __construct(Arrayable $devices, $text, array $options = [])
    {
        $this->devices = [];

        /* @var App\Contracts\DeviceContract */
        foreach ($devices as $device) {
            $this->devices[] = new Device(
                $device->getDeviceId(),
                $device->getDevicePlatform()
            );
        }
        $this->text = $text;
        $this->options = $options;
    }

    public function push()
    {
        $andoidDevices = NotifPusher::DeviceCollection();
        $iosDevices = NotifPusher::DeviceCollection();
        \Log::info('message', ['test' => $this->devices]);
        /* @var App\Services\PushNotif\Device */
        foreach ($this->devices as $device) {
            $pushToDevice = NotifPusher::Device($device->id);
            if ($device->isAndroidPlatform()) {
                $andoidDevices->add($pushToDevice);
            } elseif ($device->isIOSPlatform()) {
                $iosDevices->add($pushToDevice);
            }
        }

        $message = NotifPusher::Message($this->text, $this->options);
        if (count($andoidDevices->getTokens())) {
            NotifPusher::app('AndroidApp')
                ->to($andoidDevices)
                ->send($message);
        }
        if (count($iosDevices->getTokens())) {
            NotifPusher::app('IOSApp')
                ->to($iosDevices)
                ->send($message);
        }
    }

    public function pushLater()
    {
        Queue::push(function($job) {
            \Log::info('message', ['test']);
            $this->push();
            $job->delete();
        });
    }
}
