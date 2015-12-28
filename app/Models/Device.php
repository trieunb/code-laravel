<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\DeviceContract;

class Device extends Model implements DeviceContract
{
    /**
     * Table name
     * @var $question
     */
    protected $table = 'devices';

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int'
    ];

    /**
     * Define a many-to-many relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDeviceId()
    {
        return $this->device_id;
    }

    public function getDevicePlatform()
    {
        return $this->platform;
    }
}
