<?php
namespace App\Repositories\Category;

use App\Repositories\Repository;

interface CategoryInterface extends Repository
{
	/**
	 * Get DataTable
	 * @return mixex
	 */
	public function datatable();
}