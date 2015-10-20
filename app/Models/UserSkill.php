<?php

namespace App\Models;

use App\Models\User;
use App\Models\UpdateColumnWithClauseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;

class UserSkill extends Model
{
    use UpdateColumnWithClauseTrait;

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

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $user_skills = [];
        
        foreach ($dataPrepareForCreate as $value) {
            $user_skills[] = [
                'user_id' => $user_id,
                'skill_name' => $value['skill_name'],
                'skill_test' => $value['skill_test'],
                'skill_test_point' => $value['skill_test_point'],
                'experience' => $value['experience'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $this->insert($user_skills);
    }
}
