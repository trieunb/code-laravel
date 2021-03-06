<?php
namespace App\Repositories\Objective;

use App\Repositories\Repository;

interface ObjectiveInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @param int $user_id
	 * @return mixed      
	 */
	public function saveFromApi($data, $user_id);
}