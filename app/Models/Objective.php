<?php

namespace App\Models;

use App\Models\User;
use App\Models\UpdateColumnWithClauseTrait;
use Illuminate\Database\Eloquent\Model;

class Objective extends Model
{
	use UpdateColumnWithClauseTrait;
	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'objectives';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $objectives = [];
        foreach ($dataPrepareForCreate as $value) {
            $objectives[] = [
                'user_id' => $user_id,
                'title' => $value['title'],
                'content' => $value['content']
            ];
        }

        $this->insert($objectives);
    }
}
