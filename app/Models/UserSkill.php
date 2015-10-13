<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;

class UserSkill extends Model
{

    protected $fillable = [
        'user_id',
        'skill_name',
        'skill_test',
        'skill_test_point',
        'experience'
    ];

	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'user_skills';

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
        $sql = 'UPDATE `user_skills` SET skill_name = CASE ';
        $sql .= $this->updateColumnWithClause($dataPrepareUpdate, 'skill_name');
        $sql .= ' , skill_test = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'skill_test');
        $sql .= ' , skill_test_point = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'skill_test_point');
        $sql .= ' , experience = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'experience');
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';

        \DB::update(\DB::raw($sql));
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        // $user = User::find($user_id);
        $user_skills = [];
        foreach ($dataPrepareForCreate as $value) {
            $user_skills[] = [
                'user_id' => $user_id,
                'skill_name' => $value['skill_name'],
                'skill_test' => $value['skill_test'],
                'skill_test_point' => $value['skill_test_point'],
                'experience' => $value['experience']
            ];
        }

        $this->insert($user_skills);
        //$user->user_skills()->save($user_skills);
        
    }
}
