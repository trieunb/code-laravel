<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateMarket extends Model
{
    use SoftDeletes, PresentableTrait;

    protected $table = 'template_markets';
    
    protected $presenter = 'App\Presenter\TemplateMarketPresenter';

    protected $casts = [
        'image' => 'json',
        'section' => 'json',
        'id' => 'int',
        'cat_id' => 'int',
        'user_id' => 'int',
        'price' => 'double',
        'status' => 'int',
    ];

    /**
     * Fillable 
     */
    // protected $fillable = ['status'];
	/**
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
    public function category()
    {
    	return $this->belongsTo(Category::class, 'cat_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public static function makeSlug($template)
    {
        $template->slug = str_slug($template->title);

        $latestSlug = static::whereRaw("slug RLIKE '^{$template->slug}(-[0-9]*)?$'")
            ->latest('id')
            ->pluck('slug');

        if ($latestSlug) {
            $pieces = explode('-', $latestSlug);
            $number = intval(end($pieces));
            $template->slug .= '-'. ($number + 1);
        }
    }
}
