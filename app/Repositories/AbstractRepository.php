<?php

namespace App\Repositories;

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
	 * @param array $data
	 * @param $id
	 * @return mixed
	 */
	public function update(array $data, $id) {
        return $this->model->where('id', '=', $id)->update($data);
    }
 	/**
 	 * @param array $data
 	 * @return mixed
 	 */
 	public function create(array $data)
 	{
 		return $this->model->create($data);
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

	/**
	 * get data with clause 
	 * @param  string $field    [column table]
	 * @param  string $operator Ex: '=', '!='
	 * @param  mixed $value    
	 * @return mixed           
	 */
	public function getDataWhereClause($field, $operator, $value)
	{
		return $this->model->where($field, $operator, $value)->get();
	}

	/**
	 * get data has in array with column
	 * @param  string $field 
	 * @param  array  $data  
	 * @return mixed        
	 */
	public function getDataWhereIn($field,array $data)
	{
		return $this->model->whereIn($field, $data)->get();
	}

	/**
	 * get data has not in array with column
	 * @param  string $field 
	 * @param  array  $data  
	 * @return mixed        
	 */
	public function getDataWhereNotIn($field, array $data)
	{
		return $this->model->whereNotIn($field, $data)->get();
	}
}