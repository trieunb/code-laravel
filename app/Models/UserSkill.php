<?php

namespace App\Models;

use App\Models\User;
use App\Models\UpdateColumnWithClauseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;

class UserSkill extends Model
{
    use UpdateColumnWithClauseTrait;

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'postion' => 'int'
    ];

    protected $visible = ['id', 'name', 'level', 'postion'];

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
                'name' => $value['name'],
                'level' => $value['level'],
                'postion' => $value['postion'],
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ];
        }

        $this->insert($user_skills);
    }
}
