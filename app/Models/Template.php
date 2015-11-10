<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\UpdateColumnWithClauseTrait;

class Template extends Model
{

    use UpdateColumnWithClauseTrait;

    /**
     * Table name
     * @var $table
     */
    protected $table = "templates";
    
    protected $casts = [
    	'image' => 'json',
        'id' => 'int',
        'user_id' => 'int',
        'type' => 'int'
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
    */
    public static function boot()
    {
        parent::boot();

        static::creating(function($post) {
            /*$post->slug = str_slug($post->title);

            $latestSlug = static::whereRaw("slug RLIKE '^{$post->slug}(-[0-9]*)?$'")
                ->latest('id')
                ->pluck('slug');

            if ($latestSlug) {
                $pieces = explode('-', $latestSlug);
                $number = intval(end($pieces));
                $post->slug .= '-'. ($number + 1);
            }*/
        });
    }


    /**
     * Teamplate belongs to user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        $user_templates = [];
        foreach ($dataPrepareForCreate as $value) {
            $user_templates[] = [
                'user_id' => $user_id,
                'title' => $value['title'],
                'content' => $value['content'],
                'image' => $value['image'],
            ];
        }
        // $this->insert($user_templates);
        $this->saveMany($user_templates);
    }

    public static function makeSlug($template, $generatePDF = true)
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
        
        if ($generatePDF)
            $template->source_file_pdf = asset('pdf/'.$template->slug.'.pdf');
    }
}