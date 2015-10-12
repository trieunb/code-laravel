<?php
namespace App\Repositories\Role;

use App\Models\Role;
use App\Repositories\AbstractRepository;
use App\Repositories\Role\RoleInterface;

class RoleEloquent extends AbstractRepository implements RoleInterface
{
	protected $model;

	public function __construct(Role $role)
	{
		$this->model = $role;
	}

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @return bool      
	 */
	public function save($request, $id = null)
	{
		$role = $id ? $this->getById($id) : new Role;
		$role->name = $request->get('name');
		$role->slug = $request->get('name');

		return $role->save();
	}
}