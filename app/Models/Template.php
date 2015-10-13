<?php

namespace App;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $table = "templates";
	/**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    /**
     * Teamplate belongs to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
