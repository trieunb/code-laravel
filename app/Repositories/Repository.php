<?php

interface Repository
{
	/**
	 * Get all data
	 * @return mixed
	 */
	public function getAll();

	/**
	 * Get one record
	 * @param  [int] $id [primary key]
	 * @throws Exception [not found id]
	 * @return mixed
	 */
	public function getById($id);

	/**
	 * Delete one record
	 * @param  [int] $id [primary key]
	 * @return [bool]
	 */
	public function delete($id);

	/**
	 * Delete multi record
	 * @param  array  $id [list id]
	 * @return bool     
	 */
	public function deleteMultiRecords(array $ids);

	/**
	 * Eager Loading
	 * @param  array  $relationship [relationship]
	 */
	public function make(array $relationship);

	/**
	 * List data
	 * @param  [string] $key   
	 * @param  [string] $value 
	 * @return [mixed]        
	 */
	public function lists($key, $value);

}