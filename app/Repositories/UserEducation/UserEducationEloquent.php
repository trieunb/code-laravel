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
	 * @param int $user_id
	 * @return mixed      
	 */
	public function saveFromApi($data, $user_id)
	{
		if (count($data) == 1) {
			$this->saveOneRecord($data, $user_id);
		} 

		$ids = [];
		$dataPrepareForCreate = [];
		foreach ($data as $value) {
			if ($value['id'] != null && $value['id'] != '') {
				$ids[] = $value['id'];
			} else {
				$dataPrepareForCreate[] = $value;
			}
		}
		if ( count($ids) > 0) {
			$dataPrepareForUpdate = [];
			foreach ($ids as $id) {
				array_walk($data, function(&$value) use (&$dataPrepareForUpdate, $id) {
					if ($value['id'] == $id) {
						$dataPrepareForUpdate[] = $value;
					}
				});
			}
			if (count($dataPrepareForUpdate) == 1) 
				$this->saveOneRecord($dataPrepareForUpdate, $user_id);
			else 
				$this->model->updateMultiRecord($dataPrepareForUpdate, $ids);
		}

		if (count($dataPrepareForCreate) == 1) 
			$this->saveOneRecord($dataPrepareForCreate, $user_id);
		else 
			$this->model->insertMultiRecord($dataPrepareForCreate, $user_id);
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
		$user_education->start = $dataPrepareSave['start'];
		$user_education->end = $dataPrepareSave['end'];
		$user_education->degree = $dataPrepareSave['degree'];
		$user_education->result = $dataPrepareSave['result'];

		return $user_education->save();
	}
}