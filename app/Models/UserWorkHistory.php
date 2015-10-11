<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserWorkHistory extends Model
{
	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'user_work_histories';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function updateColumnWithClause($data, $field) 
    {
        $sql = '';
        foreach ($data as $value) {
            $sql .= " WHEN id = ".addslashes($value['id']).' THEN '.$field;
        }

        return $sql .= ' END';
    }

    public function updateMultiRecord($dataPrepareUpdate)
    {
        $sql = 'UPDATE `user_work_histories` SET company = CASE ';
        $sql .= $this->model->updateColumnWithClause($dataPrepareUpdate, 'company');
        $sql .= ' , start = CASE '.$this->model->updateColumnWithClause($dataPrepareUpdate, 'start');
        $sql .= ' , end = CASE '.$this->model->updateColumnWithClause($dataPrepareUpdate, 'end');
        $sql .= ' , job_title = CASE '.$this->model->updateColumnWithClause($dataPrepareUpdate, 'job_title');
        $sql .= ' , job_description = CASE '.$this->model->updateColumnWithClause($dataPrepareUpdate, 'job_description');
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';

        DB::update(DB::raw($sql));
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $user = User::find($user_id);
        $user_work_histories = [];
        foreach ($dataPrepareForCreate as $value) {
            $user_work_histories[] = new UserWorkHistory([
                'company' => $value['company'],
                'start' => $value['start'],
                'end' => $value['end'],
                'job_title' => $value['job_title'],
                'job_description' => $value['job_description']
            ]);
        }

        $user->user_work_histories->save($user_work_histories);
    }
}
