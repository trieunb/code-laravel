<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JobCompany;
use App\Models\JobCategory;
use App\Models\JobSkill;

class Job extends Model
{

    /**
     * Table name
     * @var $table
     */
    protected $table = 'jobs';

    protected $visible = [
        'id', 'title', 'country', 'min_salary', 'experience', 'description',
        'location', 'company', 'category', 'created_at'
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

}
