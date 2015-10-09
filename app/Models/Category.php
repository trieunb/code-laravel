<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'categories';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = ['meta' => 'json'];

    /**
     * [user relationship]
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /**
     * get path parent id
     * @param  int $cat_id 
     * @return string         
     */
    public static function getPathParent($cat_id)
    {
        return $this->findOrFail($cat_id)->path;
    }
}
