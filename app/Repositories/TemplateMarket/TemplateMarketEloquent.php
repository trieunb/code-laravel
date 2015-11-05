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
        return $this->getDataWhereClause('status', '=', 1);
    }

    public function getDetailTemplateMarket($template_id)
    {
        $status = $this->model->findOrFail($template_id)->status;
        if ( $status ) {
            return response()->json([
                'status_code' => 200,
                'status' => true,
                'data' => $this->model->where('status', 1)->findOrFail($template_id)
            ]); 
        } else {
            return response()->json([
                "status_code" => 404,
                "status" => false,
                "message" => "page not found"
            ]);
        }
        
    }

}