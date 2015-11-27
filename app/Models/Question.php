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

    

    public function users()
    {
    	return $this->belongsToMany(User::class);
    }
}
