<?php

namespace App\Models;

use App\Models\TemplateMarket;
use App\Models\User;
use Baum\Node;
use Illuminate\Database\Eloquent\Model;

class Category extends Node
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function template_markets()
    {
        return $this->hasMany(TemplateMarket::class);
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
