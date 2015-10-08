<?php
namespace App\Repositories\UserEducation;

use App\Models\UserEducation;
use App\Repositories\AbstractRepository;
use App\Repositories\UserEducation\UserEducationInterface;

class UserEducationEloquent extends AbstractRepository implements UserEducationInterface
{
	protected $model;

	public function __construct(UserEducation $user_education)
	{
		$this->model = $user_education;
	}
}