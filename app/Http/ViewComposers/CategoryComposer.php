<?php
namespace App\Http\ViewComposers;

use App\Repositories\Category\CategoryInterface;
use Illuminate\Contracts\View\View;

class CategoryComposer
{
	private $category;

	public function __construct(CategoryInterface $category)
	{
		$this->category = $category;
	}

	public function compose(View $view)
	{
		$view->with('list_category', $this->category->lists('name', 'id'));
	}
}