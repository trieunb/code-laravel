<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
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
    protected $table = 'user_educations';

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

    public function updateMultiRecord($dataPrepareUpdate,array $ids)
    {
        $sql = 'UPDATE `user_educations` SET school_name = CASE ';
        $params = ['sql' => '', 'param' => []];
        $sql .= $this->updateColumnWithClause($dataPrepareUpdate, 'school_name', $params)['sql'];
        $sql .= ' , sub_title = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'sub_title', $params)['sql'];
        $sql .= ' , start = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'start', $params)['sql'];
        $sql .= ' , end = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'end', $params)['sql'];
        $sql .= ' , degree = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'degree', $params)['sql'];
        $sql .= ' , result = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'result', $params)['sql'];
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';
        
        \DB::update($sql, $params['param']);
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $user_educations = [];
        foreach ($dataPrepareForCreate as $value) {
            $user_educations[] = [
                'user_id' => $user_id,
                'school_name' => $value['school_name'],
                'start' => $value['start'],
                'end' => $value['end'],
                'degree' => $value['degree'],
                'result' => $value['result']
            ];
        }

        $this->insert($user_educations);
    }
}
