<?php

namespace App\Repositories\Role;

use App\Repositories\Repository;

interface RoleInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id  if $id == null => create else update
	 * @return mixed      
	 */
	public function save($data, $id = null);
}