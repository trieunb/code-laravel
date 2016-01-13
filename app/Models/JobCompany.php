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

    protected $visible = ['id', 'name', 'address', 'website', 'logo', 
                        'overview', 'benefits', 'registration_no', 
                        'industry', 'company_size', 'why_join_us'];

    protected $casts = [
        'id' => 'integer'
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
