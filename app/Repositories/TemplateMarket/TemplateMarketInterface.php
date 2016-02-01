<?php
namespace App\Repositories\TemplateMarket;

use App\Repositories\Repository;

interface TemplateMarketInterface extends Repository
{
	/**
	 * Get all template in market place
	 * @return mixed 
	 */
    public function getAllTemplateMarket($sortby, $order, $page, $cat_id, $search);
    
	public function getDetailTemplateMarket($template_id);

	/**
	 * Check title exists
	 * @param  string $title 
	 * @return bool        
	 */
	public function checkExistsTitle($title);

	/**
	 * Admin create template for market place
	 * @param  mixed $request 
	 * @param  array $data 
	 * @param  mixed $user_id 
	 * @return bool          
	 */

	public function createOrUpdateTemplateByManage($request, $data, $user_id);

	/**
	 * Publish or Pending template multi record
	 * @param  int $status 
	 * @param  array $ids    
	 * @return mixed         
	 */
	public function publishOrPendingMultiRecord($status, $ids);

	/*
	 * Report Template in Admin area
	 * @param  int $year 
	 * @return array       
	 */
	public function reportTemplate($year = null);
}