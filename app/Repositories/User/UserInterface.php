<?php
namespace App\Repositories\User;

use App\Repositories\Repository;

interface UserInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id  if $id == null => create else update
	 * @return mixed      
	 */
	public function saveFromApi($data, $id = null);

	/**
	 * Get profile
	 * @param  int $id 
	 * @return mixed     
	 */
	public function getProfile($id);
}