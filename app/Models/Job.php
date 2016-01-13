<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobCompany;
use App\Models\JobCategory;
use App\Models\JobSkill;
use App\Models\User;

class Job extends Model
{

    /**
     * Table name
     * @var $table
     */
    protected $table = 'jobs';

    protected $visible = [
        'id', 'company_id', 'title', 'country', 'min_salary', 'experience', 'description',
        'location', 'company', 'category', 'responsibilities', 'requirements', 'created_at'
    ];

    protected $casts = [
        'id' => 'integer',
        'min_salary' => 'double',
        'job_cat_id' => 'integer',
        'company_id' => 'integer'
    ];

    public function company()
    {
        return $this->belongsTo(JobCompany::class, 'company_id');
    }

    public function skills()
    {
        return $this->belongsToMany(JobSkill::class, 'job_skill_pivot', 'job_id', 'job_skill_id');
    }

    public function category()
    {
        return $this->belongsTo(JobCategory::class, 'job_cat_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'job_applies', 'user_id', 'job_id');
    }

}
