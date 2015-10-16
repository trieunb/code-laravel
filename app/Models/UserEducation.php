<?php

namespace App\Models;

use App\Models\User;
use App\Models\UpdateColumnWithClauseTrait;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    use UpdateColumnWithClauseTrait;
    
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

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $user_educations = [];
        
        foreach ($dataPrepareForCreate as $value) {
            $user_educations[] = [
                'user_id' => $user_id,
                'school_name' => $value['school_name'],
                'sub_title' => $value['sub_title'],
                'start' => $value['start'],
                'end' => $value['end'],
                'degree' => $value['degree'],
                'result' => $value['result']
            ];
        }

        $this->insert($user_educations);
    }
}
