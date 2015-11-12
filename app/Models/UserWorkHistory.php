<?php

namespace App\Models;

use App\Models\User;
use App\Models\UpdateColumnWithClauseTrait;
use Illuminate\Database\Eloquent\Model;

class UserWorkHistory extends Model
{
    use UpdateColumnWithClauseTrait;
    
    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
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

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $user_work_histories = [];
        
        foreach ($dataPrepareForCreate as $value) {
            $user_work_histories[] = [
                'company' => $value['company'],
                'sub_title' => $value['sub_title'],
                'start' => $value['start'],
                'end' => $value['end'],
                'job_title' => $value['job_title'],
                'job_description' => $value['job_description'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $this->insert($user_work_histories);
    }
}
