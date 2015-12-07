<?php

namespace App\Models;

use App\Models\User;
use App\Models\UpdateColumnWithClauseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;
use App\Models\Question;

class UserQuestion extends Model
{
    protected $table = 'user_questions';

    public $timestamps = false;

    protected $casts = [
        'user_id' => 'int',
        'question_id' => 'int',
        'point' => 'int'
    ];

    protected $fillable = [
        'question_id',
        'user_id',
        'result',
        'content',
        'point'
    ];

    
}