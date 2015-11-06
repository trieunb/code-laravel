<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TemplateMarket extends Model
{
    protected $casts = [
        'image' => 'json'
    ];
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function category()
    {
    	return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
