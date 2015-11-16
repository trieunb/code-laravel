<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
	/**
	 * Table name
	 * @var $table
	 */
    protected $table = 'sections';

  /*  public function templates()
    {
    	return $this->belongsToMany(Template::class);
    }*/
}
