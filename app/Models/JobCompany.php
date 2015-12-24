<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job;

class JobCompany extends Model
{
    protected $table = 'job_companies';

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
