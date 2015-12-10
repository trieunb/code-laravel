<?php
namespace App\Repositories\UserQuestion;

use App\Repositories\AbstractRepository;
use App\Repositories\UserQuestion\UserQuestionInterface;
use App\Models\UserQuestion; 
use App\Models\Question;
class UserQuestionEloquent extends AbstractRepository implements UserQuestionInterface
{
    /**
     * UserQuestion
     * @var $model
     */
    protected $model;

    public function __construct(UserQuestion $model)
    {
        $this->model = $model;
    }

    public function saveUserAnswer($data, $user_id)
    {
        foreach ($data as $value) {
            $id = Question::where('id', $value['question_id'])->first();
            $data_save[] = [
                'question_id' => $value['question_id'],
                'user_id' => $value['user_id'],
                'content' => $id['content'],
                'point' => $value['point']
            ];
        }
        return UserQuestion::insert($data_save);
    }
}