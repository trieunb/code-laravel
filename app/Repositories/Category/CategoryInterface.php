<?php
namespace App\Repositories\Category;

use App\Repositories\Repository;

interface CategoryInterface extends Repository
{
	/**
	 * Get first record
	 * @return mixed 
	 */
	public function getFrist();
}