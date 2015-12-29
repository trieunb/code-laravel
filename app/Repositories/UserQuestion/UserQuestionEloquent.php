<?php
namespace App\Repositories\UserQuestion;

use App\Models\Question;
use App\Models\UserQuestion;
use App\Repositories\AbstractRepository;
use App\Repositories\UserQuestion\UserQuestionInterface;
use App\Services\Report\Report;
use Khill\Lavacharts\Lavacharts;
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

    /**
     * Report matrix skill 
     * @param int $question_id
     * @return array 
     */
    public function reportSkill($question_id)
    {
        $sql = 'CASE
                    WHEN point = 1 or point = 0 or point = 2 THEN "Low"
                    WHEN point = 3 or point = 4 THEN "Below Average"
                    WHEN point = 5 or point = 6 THEN "Average"
                    WHEN point = 7 or point = 8 THEN "Above Average"
                    WHEN point = 9 or point = 10 THEN "High"
                    END as "level"';
        $groupBy = \DB::raw('CASE 
                    WHEN point = 1 or point = 0 or point = 2 THEN "Low"
                        WHEN point = 3 or point = 4 THEN "Below Average"
                        WHEN point = 5 or point = 6 THEN "Average"
                        WHEN point = 7 or point = 8 THEN "Above Average"
                        WHEN point = 9 or point = 10 THEN "High"
                    END');
        $report = new Report($this->model, $sql, $groupBy, null, null, [['field' => 'question_id', 'operator' => '=', 'value' => $question_id]]);

        $levels = ['Low' => 0, 'Below Average' => 0, 'Average' => 0, 'Above Average' => 0, 'High' => 0];
      
        $options = [
        'is3D' => true,
                'width' => 988,
                'height' => 350,
                'sliceVisibilityThreshold' => 0
        ];
        return $report->prepareRender('level', $levels, 'Reasons', 'Percent', $options);
    }
}