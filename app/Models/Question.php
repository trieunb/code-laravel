<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
	/**
	 * Table name
	 * @var $question
	 */
    protected $table = 'questions';

    protected $casts = [
        'id' => 'int',
        'point' => 'int'
    ];
    
    /**
     * Define a many-to-many relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
    	return $this->belongsToMany(User::class, 'user_questions', 'user_id', 'question_id');
    }

    /**
     * Set Question for Create Or Update
     * @param  mixed $questionInputs 
     * @return array                 
     */
    public static function prepareQuestionsForSave($questionInputs)
    {
        $questions = static::all();
        $prepareQuestions = [];
        
        foreach ($questions as $question) {
            
            array_walk($questionInputs, function($value, $key) use (&$question, &$prepareQuestions) {

                if (intval($key) == $question->id) {
                    $prepareQuestions[$key] = ['point' => $value];
                }
            });
        }

        return $prepareQuestions;
    }
}
