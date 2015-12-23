<?php
namespace App\Repositories\Category;

use App\Events\UpdatePathWhenSaved;
use App\Models\Category;
use App\Repositories\AbstractRepository;
use App\Repositories\Category\CategoryInterface;

class CategoryEloquent extends AbstractRepository implements CategoryInterface
{
	protected $model;

	public function __construct(Category $category)
	{
		$this->model = $category;
	}

	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @return mixed      
	 */
	public function save($request)
	{

		$category = $request->has('id') ? $this->getById($request->get('id')) : new Category;

		if ( ! $request->has('id')) $category->user_id = \Auth::user()->id;

		$category->name = $request->get('name');
		Category::makeSlug($category);
		$category->meta = [
			'description' => $request->get('description'),
			'keyword' => $request->get('keyword')
		];
		$category->parent_id = $request->get('parent_id') != 0 
			? $request->get('parent_id') 
			: null;

		return $category->save();
	}

	public function checkName($name = null, $id = null)
	{
		return is_null($id)
			? $this->getFirstDataWhereClause('name', '=', $name)
			: $this->model->whereName($name)->where('id', '!=', $id)->first();
	}

	public function datatable()
	{
		return \Datatables::of($this->model->select('*'))
            ->addColumn('description', function($category) {
            	return $category->meta['description'];
            })
            ->addColumn('keyword', function($category) {
            	return $category->meta['keyword'];
            })
            ->addColumn('action', function ($category) {
                return '<div class="btn-group" role="group" aria-label="...">
                    <a class="btn btn-default" href="' .route('admin.category.get.detail', $category->id) . '"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a class="btn btn-primary edit" href="' .route('admin.category.get.edit', $category->id) . '"><i class="glyphicon glyphicon-edit"></i></a>
                </div>';
            })
            ->make(true);
	}

	public function listParent($key, $value, $id)
	{
		$node = $this->getById($id);
		$childrens = $node->getDescendants();
		$ids = [$id];

		foreach ($childrens as $children) {
			$ids[] = $children->id;
		}

		return $this->model->whereNotIn('id', $ids)->lists($key, $value);
	}

	public function paginate()
	{
		return $this->model->orderBy('created_at', 'DESC')
			->paginate(config('paginate.offset'));
	}
}