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
    public function getAllTemplateMarket($sortby, $order, $page,$search)
    {
        $offset = ($page -1 ) * 10;
        $query = $this->model->whereStatus(2);

        if ($search != null && $search != '') {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        $query = $sortby != null
            ? $query->orderBy($sortby, $order)
            : $query->orderBy('price');
            
        return $query->skip($offset)
            ->take(10)
            ->get();
    }

    public function getDetailTemplateMarket($template_id)
    {
        $template_mk = $this->model->findOrFail($template_id);

        return $template_mk->status == 2 ? $template_mk : null;
    }

    /**
     * Check title exists
     * @param  string $title 
     * @return bool        
     */
    public function checkExistsTitle($title, $id = null)
    {
        $queryBuilder = $this->model->whereTitle($title);
        
        return $id == null
            ? $queryBuilder->exists()
            : $queryBuilder->where('id', '!=' , $id)->exists();
    }

    /**
     * Admin create template for market place
     * @param  mixed $request 
     * @param  array $data 
     * @param  int $user_id 
     * @return bool          
     */
    public function createOrUpdateTemplateByManage($request, $data, $user_id)
    {
        $template = $request->has('id') 
            ? $this->getById($request->get('id')) 
            : new TemplateMarket;
        $template->title = $request->get('title');
        $template->user_id = $user_id;
        $template->cat_id = $request->get('cat_id');

        if (count($data) != 0) {
            $template->content = $data['content'];
            unset($data['content']);
            $template->section = $data;
        } else {
            $template->content = $request->get('content');
            $template->section = null;
        }
        
        $template->price = $request->get('price');
        $template->description = $request->get('description');
        $template->version = $request->get('version');
        $template->status = $request->get('status');
        if ($request->get('status') == 2) {
            $template->published_at = time();
        }
        TemplateMarket::makeSlug($template);
        $result = $template->save();

        if ($result) {
            return event(new RenderFileWhenCreateTemplateMarket($template->slug, $template->content, $template->id));
        }
        
        return false;
    }

    /**
     * Search template In Market Area
     * @param  string $name 
     * @return        
     */
    public function search($name)
    {
        dd($name);
        return $this->model
            ->whereStatus(2)
            ->where('slug', 'LIKE', "%{$name}%")->get();
        return $this->getDataWhereClause('slug', 'LIKE', "%{$name}%");
    }

    /**
     * Get data with DataTable
     * @return mixed 
     */
    public function dataTableTemplate()
    {
        $templates = $this->model->select('*');
        return \Datatables::of($templates)
            ->addColumn('action', function ($template) {
                return '<div class="btn-group" role="group" aria-label="...">
                    <a class="btn btn-primary edit" href="' .route('admin.template.get.edit', $template->id) . '"><i class="glyphicon glyphicon-edit"></i></a>
                    <a class="delete-data btn btn-danger"  href="' .route('admin.template.delete', $template->id) . '"><i class="glyphicon glyphicon-remove"></i></a>
                  
                </div>';
            })
        ->make(true);
    }
}