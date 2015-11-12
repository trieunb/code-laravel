<?php
namespace App\Repositories\TemplateMarket;

use App\Repositories\Repository;

interface TemplateMarketInterface extends Repository
{
	/**
	 * Get all template in market place
	 * @return mixed 
	 */
    public function getAllTemplateMarket();
    
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
	 * @param  mixed $user_id 
	 * @return bool          
	 */

	public function createOrUpdateTemplateByManage($request, $user_id);

}