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

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
