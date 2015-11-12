<?php
namespace App\Repositories\TemplateMarket;

use App\Events\RenderFileWhenCreateTemplateMarket;
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

    /**
     * Check title exists
     * @param  string $title 
     * @return bool        
     */
    public function checkExistsTitle($title)
    {
        return $this->model->whereTitle($title)->exists();
    }

    /**
     * Admin create template for market place
     * @param  mixed $request 
     * @param  int $user_id 
     * @return bool          
     */
    public function createTemplateByManage($request, $user_id)
    {
        $template = new TemplateMarket;
        $template->title = $request->get('title');
        $template->user_id = $user_id;
        $template->cat_id = $request->get('cat_id');
        $template->content = $request->get('content');
        $template->price = $request->get('price');
        $template->description = $request->get('description');
        $template->version = $request->get('version');
        $template->status = $request->get('status');

        TemplateMarket::makeSlug($template);
        $result = $template->save();

        if ($result) {
            return event(new RenderFileWhenCreateTemplateMarket($template->slug, $template->content, $template->id));
        }
        
        return false;
    }
}