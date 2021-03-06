<?php

namespace App\Models;

use App\Models\User;
use App\Models\UpdateColumnWithClauseTrait;
use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
	use UpdateColumnWithClauseTrait;
	/**
	 * Table name
	 * @var $table
	 */
    protected $table = 'qualifications';

    /**
     * Mass Assigment field
     * @var array
     */
    protected $fillable = ['content'];

    protected $casts = [
    	'id' => 'int',
    	'user_id' => 'int',
        'position' => 'int'
	];
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

     public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $qualifications = [];

        foreach ($dataPrepareForCreate as $value) {
            $qualifications[] = new Qualification([
            	'content' => $value['content'],
                'position' => $value['position'],
            ]);
        }
        $user = \App\Models\User::findOrFail($user_id);
        $user->qualifications()->saveMany($qualifications);
    }
}
