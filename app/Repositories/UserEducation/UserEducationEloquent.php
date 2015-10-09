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

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @param int $user_id
	 * @return mixed      
	 */
	public function save($data, $id = null, $user_id)
	{
		$user_education = $id ? $this->getById($id) : new UserEducation;
		if ($id == null) $user_education->user_id = $user_id;

		$user_education->school_name = $data['school_name'];
		$user_education->start = $data['start'];
		$user_education->end = $data['end'];
		$user_education->degree = $data['degree'];
		$user_education->result = $data['result'];

		return $user_education->save();
	}

}