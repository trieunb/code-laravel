<?php
namespace App\Repositories\TemplateMarket;

use App\Models\TemplateMarket;
use App\Repositories\AbstractRepository;
use App\Repositories\TemplateMarket\TemplateMarketInterface;

class TemplateMarketEloquent extends AbstractRepository implements TemplateMarketInterface
{
    /**
     * TemplateMarket 
     * @var $model
     */
	protected $model;

	public function __construct(TemplateMarket $template_market)
	{
		$this->model = $template_market;
	}

    /**
     * Get all template in market place
     * @return mixed 
     */
    public function getAllTemplateMarket()
    {
        return $this->getDataWhereClause('status', '=', 1);
    }

    public function getDetailTemplateMarket($template_id)
    {
        $template_mk = $this->model->findOrFail($template_id);

        return $template_mk->status == 1 ? $template_mk : null;
    }


}