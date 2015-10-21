<?php
namespace App\Repositories\Reference;

use App\Repositories\Repository;

interface ReferenceInterface extends Repository
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