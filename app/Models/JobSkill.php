<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Job;
use App\Models\User;

class JobSkill extends Model
{
    protected $table = 'job_skills';

    protected $casts = [
        'id' => 'integer'
    ];

    protected $hidden = ['pivot'];

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_skill_pivot', 'job_id', 'job_skill_id');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (! $this->exists) {
            $this->attributes['slug'] = str_slug($value);
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills', 'user_id', 'job_skill_id')->withPivot('level');
    }
    
}
