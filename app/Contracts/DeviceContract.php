<?php

namespace App\Contracts;

interface DeviceContract
{
    /**
     * Get the device Id.
     *
     * @return string
     */
    public function getDeviceId();

    /**
     * Get the device OS platform.
     *
     * @return string
     */
    public function getDevicePlatform();
}
