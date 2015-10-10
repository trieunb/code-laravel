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
			if ($value['id'] != null) {
				$ids[] = $value['id'];
			} else {
				$dataPrepareForCreate[] = $value;
			}
		}

		$dataIds_has_ids = $this->getDataWhereIn('id', $ids);
		
		if ( count($dataIds_has_ids) > 0) {
			$dataPrepareForUpdate = [];
			foreach ($dataIds_has_ids as $user_education) {
				array_walk($data, function(&$value) use (&$dataPrepareForUpdate, $user_education) {
					if ($value['id'] == $user_education->id) {
						$dataPrepareForUpdate[] = $value;
					}
				});
			}

			if (count($dataPrepareForUpdate) == 1) 
				$this->saveOneRecord($dataPrepareForUpdate, $user_id);
			else 
				$this->model->updateMultiRecord($dataPrepareForUpdate);
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
		$user_education = $data['id'] ? $this->getById($data['id']) : new UserEducation;
		if ($data['id'] == null) $user_education->user_id = $user_id;

		$user_education->school_name = $data['school_name'];
		$user_education->start = $data['start'];
		$user_education->end = $data['end'];
		$user_education->degree = $data['degree'];
		$user_education->result = $data['result'];

		return $user_education->save();
	}
}