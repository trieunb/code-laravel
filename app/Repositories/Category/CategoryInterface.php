<?php
namespace App\Repositories\Category;

use App\Repositories\Repository;

interface CategoryInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @return mixed      
	 */
	public function save($request);

	/**
	 * Get DataTable
	 * @return mixex
	 */
	public function datatable();
}