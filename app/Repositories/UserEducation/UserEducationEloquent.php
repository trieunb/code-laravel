<?php
namespace App\Repositories\UserEducation;

use App\Models\UserEducation;
use App\Repositories\SaveFromApiTrait;
use App\Repositories\AbstractDefineMethodRepository;
use App\Repositories\UserEducation\UserEducationInterface;

class UserEducationEloquent extends AbstractDefineMethodRepository implements UserEducationInterface
{
	use SaveFromApiTrait;
	/**
	 * UserEducation
	 * @var $model
	 */
	protected $model;

	/**
	 * Fields for update data
	 * @var $field_work_save
	 */
	protected $field_work_save = [
		'school_name','title', 'start', 'end',
		'degree', 'result', 'position'
	];

	public function __construct(UserEducation $user_education)
	{
		$this->model = $user_education;
	}

	/**
	 * Save Or Update One Record
	 * @param  mixed $data    
	 * @param  int $user_id 
	 * @return mixed          
	 */
	public function saveOneRecord($data, $user_id)
	{
		$dataPrepareSave = $data[0];
		$user_education = $dataPrepareSave['id'] ? $this->getById($dataPrepareSave['id']) : new UserEducation;
		if ($dataPrepareSave['id'] == null) $user_education->user_id = $user_id;

		$user_education->school_name = $dataPrepareSave['school_name'];
		$user_education->title = $dataPrepareSave['title'];
		$user_education->start = $dataPrepareSave['start'];
		$user_education->end = $dataPrepareSave['end'];
		$user_education->degree = $dataPrepareSave['degree'];
		$user_education->result = $dataPrepareSave['result'];
		$user_education->position = $dataPrepareSave['position'];

		return $user_education->save();
	}
}