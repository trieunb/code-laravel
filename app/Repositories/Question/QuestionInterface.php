<?php
namespace App\Repositories\Question;

use App\Repositories\Repository;

interface QuestionInterface extends Repository
{
	/**
	 * Get data with DataTable
	 * @return mixed 
	 */
	public function dataTable();

	/**
	 * Create or Update question from Admin Area
	 * @param  mixed $request 
	 * @return bool          
	 */
	public function saveFromAdminArea($request);
}