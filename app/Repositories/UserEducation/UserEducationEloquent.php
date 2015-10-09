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
	 * @return bool      
	 */
	public function save($request, $id = null)
	{
		$user_education = $id ? $this->getById($id) : new UserEducation;

		if ( !$id) $user_education->user_id = \Auth::user()->id;

		$user_education->school_name = $request->get('school_name');
		$user_education->start = $request->get('start');
		$user_education->end = $request->get('end');
		$user_education->degree = $request->get('degree');
		$user_education->result = $request->get('result');

		return $user_education->save();
	}
}