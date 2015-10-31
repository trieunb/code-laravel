<?php

namespace App\Models;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\UpdateColumnWithClauseTrait;

class Template extends Model
{

    use UpdateColumnWithClauseTrait;

    protected $table = "templates";
    
    protected $fillable = [
        'user_id',
        'source',
        'source_convert'
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
                'source' => $value['source'],
                'source_convert' => $value['source_convert']
            ];
        }
        $this->insert($user_templates);
    }
}