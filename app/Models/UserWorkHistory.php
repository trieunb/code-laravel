<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserWorkHistory extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

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

    public function updateColumnWithClause($data, $field, &$params = []) 
    {
        $params['sql'] = '';
        foreach ($data as $value) {
            $params['sql'] .= " WHEN id = ? THEN ? ";
            $params['param'][] = $value['id']; 
            $params['param'][] = $value[$field]; 
        }
        $params['sql'] .= ' END';

        return $params;
    }

    public function updateMultiRecord($dataPrepareUpdate, array $ids)
    {
        $sql = 'UPDATE `user_work_histories` SET company = CASE ';
        $params = ['sql' => '', 'param' => []];
        $sql .= $this->updateColumnWithClause($dataPrepareUpdate, 'company', $params)['sql'];
        $sql .= ' , sub_title = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'sub_title', $params)['sql'];
        $sql .= ' , start = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'start', $params)['sql'];
        $sql .= ' , end = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'end', $params)['sql'];
        $sql .= ' , job_title = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'job_title', $params)['sql'];
        $sql .= ' , job_description = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'job_description', $params)['sql'];
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';

        \DB::update($sql, $params['param']);
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        //$user = User::find($user_id);
        $user_work_histories = [];
        foreach ($dataPrepareForCreate as $value) {
            $user_work_histories[] = [
                'company' => $value['company'],
                'sub_title' => $value['sub_title'],
                'start' => $value['start'],
                'end' => $value['end'],
                'job_title' => $value['job_title'],
                'job_description' => $value['job_description']
            ];
        }

        $this->insert($user_work_histories);
        // $user->user_work_histories()->save($user_work_histories);
    }
}
