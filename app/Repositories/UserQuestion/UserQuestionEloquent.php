<?php
namespace App\Repositories\UserQuestion;

use App\Repositories\AbstractRepository;
use App\Repositories\UserQuestion\UserQuestionInterface;
use App\Models\UserQuestion; 
class UserQuestionEloquent extends AbstractRepository implements UserQuestionInterface
{
    /**
     * UserQuestion
     * @var $model
     */
    protected $model;

    protected $field_work_save = [
        'result','point'
    ];

    public function __construct(UserQuestion $model)
    {
        $this->model = $model;
    }

    public function saveUserAnswer($data, $user_id)
    {
        return UserQuestion::insert($data);
    }

    public function updateUserAnswer($data, $user_id)
    {
        foreach ($data as $value) {
            $question_id[] = [
                'question_id' => $value['question_id']
            ];
        }
        return $this->updateMultiRecord($data,$this->field_work_save, $question_id);
    }
}