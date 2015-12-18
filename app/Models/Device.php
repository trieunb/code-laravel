<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * Table name
     * @var $question
     */
    protected $table = 'devices';

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int'
    ];
    
    /**
     * Define a many-to-many relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
