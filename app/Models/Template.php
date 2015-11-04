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
    	'image' => 'json'
    ];

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
                'cat_id' => $user_id,
                'title' => $value['title'],
                'content' => $value['content'],
                'image' => $value['image'],
                'price' => $value['price'],
                'status' => $value['status'],
                'type' => $value['type'],
            ];
        }
        $this->insert($user_templates);
    }
}