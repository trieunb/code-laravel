<?php
namespace App\Repositories\UserQuestion;

use App\Models\Question;
use App\Models\UserQuestion;
use App\Repositories\AbstractRepository;
use App\Repositories\UserQuestion\UserQuestionInterface;
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
        $questions = \App\Models\UserQuestion::select(\DB::raw('CASE
                    WHEN point = 1 or point = 0 or point = 2 THEN "LOW"
                    WHEN point = 3 or point = 4 THEN "ALOW AVERAGE"
                    WHEN point = 5 or point = 6 THEN "AVERAGE"
                    WHEN point = 7 or point = 8 THEN "ABOVE AVERAGE"
                    WHEN point = 9 or point = 10 THEN "HIGH"
                    END as "level",
                    COUNT(*) as "count"'))
                ->where('question_id', $question_id)
                ->groupBy(\DB::raw('CASE 
                    WHEN point = 1 or point = 0 or point = 2 THEN "LOW"
                        WHEN point = 3 or point = 4 THEN "ALOW AVERAGE"
                        WHEN point = 5 or point = 6 THEN "AVERAGE"
                        WHEN point = 7 or point = 8 THEN "ABOVE AVERAGE"
                        WHEN point = 9 or point = 10 THEN "HIGH"
                    END'))
                ->get();
        
        $levels = ['LOW' => 0, 'ALOW AVERAGE' => 0, 'AVERAGE' => 0, 'ABOVE AVERAGE' => 0, 'HIGH' => 0];
        $response = [];

        foreach ($questions as $question) {
            $response[$question->level] = $question->count;    
        }

        $diffArray = array_diff_key($levels, $response);
        $responses = array_merge($response, $diffArray);

        $lavaChart = new Lavacharts;
        $reason = $lavaChart->DataTable()
                ->addStringColumn('Reasons')
                ->addNumberColumn('Percent');        
        foreach ($responses as $name => $value) {
            $reason->addRow([$name, (int)$value]);
        }

        $pieChart = $lavaChart->PieChart('Chart')
            ->setOptions([
                'datatable' => $reason,
                'is3D' => true,
                'width' => 988,
                'height' => 350,
                'title' => 'Report Point Skill'
            ]);

        return $lavaChart->render('PieChart', 'Chart', 'chart_skill', true);
    }
}