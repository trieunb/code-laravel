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
}