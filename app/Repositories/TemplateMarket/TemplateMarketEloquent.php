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
    public function getAllTemplateMarket($sortby, $order, $page, $cat_id, $search)
    {
        $offset = ($page -1 ) * config('paginate.limit');
        $query = $this->model->whereStatus(2);

        if ($search != null && $search != '') {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        if ( $cat_id ) {
            $query->where('cat_id', $cat_id);
        }

        $query = $sortby != null
            ? $query->orderBy($sortby, $order)
            : $query->orderBy('price');
            
        $templates = $query->skip($offset)
                ->take(config('paginate.limit'))
                ->get();
        return [
            'templates' => $templates,
            'total' => $query->count(),
            'per_page' => config('paginate.limit')
        ]; 
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
       /* if ($request->has('id') &&
            $template->content !=  $request->get('content')
        ) {
            $template = new TemplateMarket;
            $template_old = $this->getById($request->get('id'));
            $template_old->updated_version = 1;
            $template_old->save();
        }*/

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
            if (\File::exists($template->source_file_pdf)) {
                \File::delete($template->source_file_pdf, $template->image['origin'], $template->image['thumb']);
            }
            event(new RenderFileWhenCreateTemplateMarket($template->slug, $template->content, $template->id));
        }
    
        return $result ? $template : false;
    }

    /**
     * Get data with DataTable
     * @return mixed 
     */
    public function dataTableTemplate()
    {
        $templates = $this->model->select('*');

        return \Datatables::of($templates)
            ->addColumn('checkbox', function($template) {
                return '<input type="checkbox" value="'.$template->id.'"/>';
            })
            ->editColumn('price', function($template) {
                return custom_format_money($template->price);
            })
            ->addColumn('action', function ($template) {
                return '<div class="btn-group" role="group" aria-label="...">
                    <a class="btn btn-default" href="' .route('admin.template.get.view', $template->id) . '"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-primary edit" href="' .route('admin.template.get.edit', $template->id) . '"><i class="glyphicon glyphicon-edit"></i></a>
                    <a class="delete-data btn btn-danger" data-src="' .route('admin.template.delete', $template->id) . '"><i class="glyphicon glyphicon-remove"></i></a>
                </div>';
            })
            ->addColumn('status', function($template) {
                return ($template->status == 2)
                    ? '<a class="status-data btn btn-success" data-src="' .route('admin.template.status', $template->id) . '">Publish</a>'
                    : '<a class="status-data btn btn-warning" data-src="' .route('admin.template.status', $template->id) . '">Pending</a>';
            })
            ->make(true);
    }

    /**
     * Publish or Pending template multi record
     * @param  int $status 
     * @param  array $ids    
     * @return mixed         
     */
    public function publishOrPendingMultiRecord($status, $ids)
    {
       return $this->model->whereIn('id', $ids)->update(['status' => $status]);
    }

    /*
     * Report Template in Admin area
     * @param  int $year 
     * @return array       
     */
    public function reportTemplate($year = null)
    {
        $templates = $this->model->select('id', 
                \DB::raw('MONTH(created_at) as month'),
                \DB::raw('COUNT(id) AS count')
            )
            ->groupBy('month')
            ->orderBy('month');

        $templates = ! is_null($year) 
            ? $templates->whereYear('created_at', '=', $year)->get()
            : $templates->get();

        return getCountDataOfMonth($templates);
    }
}