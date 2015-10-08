<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;

class UserSkill extends Model
{
	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'user_skills';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
