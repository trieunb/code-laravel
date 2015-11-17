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
			$idsPrepareDelete = $this->idsPrepareDelete($ids);

			$this->delete($idsPrepareDelete);

			$dataPrepareForUpdate = $this->dataPrepareUpdate($data, $ids);

			$this->update($dataPrepareForUpdate, $user_id, $ids);
		}

		$this->create($dataPrepareForCreate, $user_id);
	}

	/**
	 * Create one/mutil record
	 * @param  array $data    
	 * @param  int $user_id 
	 * @return void          
	 */
	private function create($data, $user_id)
	{
		if (count($data) == 1) 
			$this->saveOneRecord($data, $user_id);
		else if (count($data) > 1)
			$this->model->insertMultiRecord($data, $user_id);
	}

	/**
	 * Update one/mutil record
	 * @param  array $data    
	 * @param  int $user_id 
	 * @param  array $ids     
	 * @return void          
	 */
	private function update($data, $user_id, $ids)
	{
		if (count($data) == 1) 
			$this->saveOneRecord($data, $user_id);
		else 
			$this->model->updateMultiRecord($data, $this->field_work_save, $ids);
	}

	/**
	 * Delete one/multi record
	 * @param  array  $ids 
	 * @return void      
	 */
	private function delete(array $ids)
	{
		if (count($ids) > 1) 
			$this->deleteMultiRecords($ids);
		else 
			$this->delete($ids[0]);
	}

	/**
	 * Get Ids for delete
	 * @param  array  $ids 
	 * @return array      
	 */
	private function idsPrepareDelete(array $ids)
	{
		$objects = $this->getDataWhereClause('user_id', '=', $user_id);
		$idsPrepareDelete = [];

		foreach ($objects as $object) {
			if ( !in_array($object->id, $ids))
				$idsPrepareDelete[] = $object->id;
		}

		return $idsPrepareDelete;
	}

	/**
	 * Get data for Update
	 * @param  mixed $data 
	 * @param  array  $ids  
	 * @return mixed       
	 */
	private function dataPrepareUpdate($data, array $ids)
	{
		$dataPrepareForUpdate = [];

		foreach ($ids as $id) {
			array_walk($data, function(&$value) use (&$dataPrepareForUpdate, $id) {
				if ($value['id'] == $id) {
					$dataPrepareForUpdate[] = $value;
				}
			});
		}

		return $dataPrepareForUpdate;
	}
}