<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserWorkHistory extends Model
{

    protected $fillable = [
        'user_id',
        'company',
        'start',
        'end',
        'job_title',
        'job_description'
    ];

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
            $sql .= " WHEN id = ".addslashes($value['id'])." THEN '".$value[$field]."'";
        }

        return $sql .= ' END';
    }

    public function updateMultiRecord($dataPrepareUpdate, array $ids)
    {
        $sql = 'UPDATE `user_work_histories` SET company = CASE ';
        $sql .= $this->updateColumnWithClause($dataPrepareUpdate, 'company');
        $sql .= ' , sub_title = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'sub_title');
        $sql .= ' , start = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'start');
        $sql .= ' , end = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'end');
        $sql .= ' , job_title = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'job_title');
        $sql .= ' , job_description = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'job_description');
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';

        \DB::update(\DB::raw($sql));
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
