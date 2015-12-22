<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CategoryFormRequest;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
	private $category;

	public function __construct(CategoryInterface $category)
	{
		$this->category = $category;
	}

	public function index()
	{
		$categories = $this->category->getAll();

		return view('admin.category.index', compact('categories'));
	}

    public function create()
    {
    	return view('admin.category.create');
    }

    public function postCreate(CategoryFormRequest $request)
    {
    	return $this->category->save($request)
    		? redirect()->route('admin.category.get.index')->with('message', 'Create Category successfully!')
    		: redirect()->back()->with('message', 'Failed when create Category!!!');
    }

    public function checkName(Request $request)
    {
    	return $this->category->getFirstDataWhereClause('name', '=', $request->get('name'))
    		? 'false'
    		: 'true';
    }
}
