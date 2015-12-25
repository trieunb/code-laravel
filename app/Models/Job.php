<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobCompany;
use App\Models\JobCategory;
use App\Models\JobSkill;

class Job extends Model
{

    protected $table = 'jobs';
    
    public function job_company()
    {
        return $this->belongsTo(JobCompany::class);
    }

    public function job_skills()
    {
        return $this->belongsToMany(JobSkill::class, 'job_skill_pivot', 'job_id', 'job_skill_id');
    }

    public function job_category()
    {
        return $this->belongsTo(JobCategory::class);
    }
}
