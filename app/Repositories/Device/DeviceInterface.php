<?php
namespace App\Repositories\Device;

use App\Repositories\Repository;

interface DeviceInterface extends Repository
{ 
    public function createOrUpdateDevice($user_id, $data);
}