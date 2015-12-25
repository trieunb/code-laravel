<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job;

class JobSkill extends Model
{
    protected $table = 'job_skills';

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skill_pivot', 'job_id', 'job_skill_id');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        if (! $this->exists) {
            $this->attributes['slug'] = str_slug($value);
        }
    }
}
