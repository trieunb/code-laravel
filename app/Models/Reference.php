<?php

namespace App\Models;

use App\Models\UpdateColumnWithClauseTrait;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
	use UpdateColumnWithClauseTrait;
	/**
	 * Table name
	 * @var $table
	 */
    protected $table = 'references';

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int'
    ];
     /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $references = [];
        foreach ($dataPrepareForCreate as $value) {
            $references[] = [
                'user_id' => $user_id,
                'reference' => $value['reference'],
                'content' => $value['content'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        $this->insert($references);
    }
}
