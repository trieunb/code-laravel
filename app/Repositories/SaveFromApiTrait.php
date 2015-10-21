<?php
namespace App\Repositories;

trait SaveFromApiTrait
{
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
			$objects = $this->getDataWhereClause('user_id', '=', $user_id);
			$idsPrepareDelete = [];

			foreach ($objects as $object) {
				if ( !in_array($object->id, $ids))
					$idsPrepareDelete[] = $object->id;
			}

			$this->model->whereIn('id', $idsPrepareDelete)->delete();

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
				$this->model->updateMultiRecord($dataPrepareForUpdate, $this->field_work_save, $ids);
		}

		if (count($dataPrepareForCreate) == 1) 
			$this->saveOneRecord($dataPrepareForCreate, $user_id);
		else 
			$this->model->insertMultiRecord($dataPrepareForCreate, $user_id);
	}

}