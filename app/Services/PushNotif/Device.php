<?php

namespace App\Services\PushNotif;

class Device
{
    const ANDROID_PLATFORM = 'Android';
    const IOS_PLATFORM     = 'IOS';

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $platform;

    public function __construct($deviceId, $devicePlatform)
    {
        $this->id = $deviceId;
        $this->platform = $devicePlatform;
    }

    public function isAndroidPlatform()
    {
        return $this->platform === self::ANDROID_PLATFORM;
    }

    public function isIOSPlatform()
    {
        return $this->platform === self::IOS_PLATFORM;
    }
}
