<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job;

class JobCompany extends Model
{
    /**
     * Table name
     * @var $table
     */
    protected $table = 'job_companies';

    protected $visible = ['name', 'address', 'website', 'logo'];

    protected $casts = [
        'id' => 'integer'
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
