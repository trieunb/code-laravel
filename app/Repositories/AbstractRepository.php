<?php

abstract class AbstractRepository
{
	/**
	 * Get all data
	 * @return mixed
	 */
	public function getAll()
	{
		return $this->model->all();
	}

	/**
	 * Get one record
	 * @param  [int] $id [primary key]
	 * @throws Exception [not found id]
	 * @return mixed
	 */
	public function getById($id)
	{
		return $this->model->FindOrfail($id);
	}


	/**
	 * delete one record
	 * @param  [int] $id [primary key]
	 * @return [bool]
	 */
	public function delete($id)
	{
		return $this->model->getById($id)->delete();
	}

	/**
	 * Delete multi record
	 * @param  array  $id [list id]
	 * @return bool     
	 */
	public function deleteMultiRecords(array $ids)
	{
		return $this->model->whereIn('id', $ids)->delete();
	}

	/**
	 * Eager Loading
	 * @param  array  $relationship [relationship]
	 */
	public function make(array $relationship)
	{
		return $this->model->with($relationship);
	}

	/**
	 * List data
	 * @param  [string] $key   
	 * @param  [string] $value 
	 * @return [mixed]        
	 */
	public function lists($key, $value)
	{
		return $this->model->lists($key, $value);
	}
}