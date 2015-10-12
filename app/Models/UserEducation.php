<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    protected $fillable = [
        'user_id',
        'school_name',
        'start',
        'end',
        'degree',
        'result'
    ];
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

    public function updateColumnWithClause($data, $field) 
    {
        $sql = '';
        foreach ($data as $value) {
            $sql .= " WHEN id = ".addslashes($value['id'])." THEN '".$value[$field]."'";
        }

        return $sql .= ' END';
    }

    public function updateMultiRecord($dataPrepareUpdate,array $ids)
    {
        $sql = 'UPDATE `user_educations` SET school_name = CASE ';
        $sql .= $this->updateColumnWithClause($dataPrepareUpdate, 'school_name');
        $sql .= ' , start = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'start');
        $sql .= ' , end = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'end');
        $sql .= ' , degree = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'degree');
        $sql .= ' , result = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'result');
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';
        
        \DB::update(\DB::raw($sql));
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
        // $user->user_educations()->save($user_educations);
    }
}
