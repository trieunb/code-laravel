<?php
namespace App\Repositories\TemplateMarket;

use App\Models\TemplateMarket;
use App\Repositories\AbstractRepository;
use App\Repositories\TemplateMarket\TemplateMarketInterface;

class TemplateMarketEloquent extends AbstractRepository implements TemplateMarketInterface
{
	protected $model;

	public function __construct(TemplateMarket $model)
	{
		$this->model = $model;
	}


}