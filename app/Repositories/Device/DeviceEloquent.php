<?php
namespace App\Repositories\Device;

use App\Models\Device;
use App\Repositories\AbstractRepository;
use App\Repositories\Device\DeviceInterface;
use App\Services\Report\Report;
use Khill\Lavacharts\Lavacharts;

class DeviceEloquent extends AbstractRepository implements DeviceInterface
{
    protected $model;

    public function __construct(Device $device)
    {
        $this->model = $device;
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

    public function reportUserOs()
    {
        // return $this->model
        //     ->select('*', DB::raw('COUNT(*) AS count'))
        //     ->groupBy('platform')
        //     ->leftjoin('devices', 'users.id', '=', 'devices.user_id')
        //     ->orderBy('platform', 'DESC')
        //     ->get();

        // $with = 'devices';
        $report = new Report($this->model, 'platform', 'platform');
        // $report->setReportNotdAdmin(true);
        $options = [
             'is3D' => true,
                'width' => 988,
                'height' => 350,
                'sliceVisibilityThreshold' => 0
        ];
        return $report->prepareRender('platform', [], 'Reasons', 'Percent', $options);
    }

}
