<?php
namespace App\Repositories\Device;

use App\Models\Device;
use App\Repositories\AbstractRepository;
use App\Repositories\Device\DeviceInterface;

class DeviceEloquent extends AbstractRepository implements DeviceInterface
{
    protected $model;

    public function __construct(Device $decive)
    {
        $this->model = $decive;
    }

    public function createOrUpdateDevice($user_id, $data)
    {
        $this->getFirstDataWhereClause('user_id', '=', $user_id)
        ? $device = $this->getFirstDataWhereClause('user_id', '=', $user_id)
        : $device = new Device();
        $device->user_id = $user_id;
        $device->device_id = $data['device_id'];
        $device->platform = $data['device_platform'];

        return $device->save(); 
    }

}