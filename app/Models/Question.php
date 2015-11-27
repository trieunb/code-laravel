<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
	/**
	 * Table name
	 * @var $question
	 */
    protected $table = 'questions';

    
    /**
     * Define a many-to-many relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
    	return $this->belongsToMany(User::class, 'user_questions', 'user_id', 'question_id');
    }
}
