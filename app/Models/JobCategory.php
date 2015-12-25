<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job;

class JobCategory extends Model
{
    
    protected $table = 'job_categories';

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
