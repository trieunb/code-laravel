<?php
namespace App\Repositories\TemplateMarket;

use App\Repositories\Repository;

interface TemplateMarketInterface extends Repository
{
    public function getAllTemplateMarket();
    
	public function getDetailTemplateMarket($template_id);
}