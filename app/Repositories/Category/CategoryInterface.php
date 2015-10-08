<?php
namespace App\Repositories\Category;

use App\Repositories\Repository;

interface CategoryInterface extends Repository
{
	/**
	 * [findPath description]
	 * @param  [type] $parent_id [description]
	 * @return [type]            [description]
	 */
	public function findPath($parent_id);
}