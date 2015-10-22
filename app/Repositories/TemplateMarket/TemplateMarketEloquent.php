<?php
namespace App\Repositories\TemplateMarket;

use App\Models\TemplateMarket;
use App\Repositories\AbstractRepository;
use App\Repositories\TemplateMarket\TemplateMarketInterface;

class TemplateMarketEloquent extends AbstractRepository implements TemplateMarketInterface
{
	protected $model;

	public function __construct(TemplateMarket $template_market)
	{
		$this->model = $template_market;
	}

    public function getAllTemplateMarket()
    {
        return $this->getAll();
    }

    public function getDetailTemplateMarket($template_id)
    {
        return $this->model->findOrFail($template_id);
    }

}