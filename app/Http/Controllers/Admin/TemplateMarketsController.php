<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\TemplateFormRequest;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use Illuminate\Http\Request;

class TemplateMarketsController extends Controller
{
	/**
	 * TemplateMarketInterface
	 * @var $template_market
	 */
	private $template_market;

	public function __construct(TemplateMarketInterface $template_market)
	{
		$this->template_market = $template_market;
	}

    public function create()
    {
    	return view('admin.template.create');
    }

    public function postCreate(TemplateFormRequest $request)
    {
        return $this->template_market->createTemplateByManage($request, \Auth::user()->id)
            ? response()->json(['status' => true])
            : response()->json(['status' => false]);
    }

    public function checkTitle(Request $request)
    {
    	return $this->template_market->checkExistsTitle($request->get('title')) ? 'false' : 'true';
    }

    public function getAllTemplates(Request $request)
    {
        $templates_market = $this->template_market->getAllTemplateByManager();
        return view('admin.template.list_templates', compact('templates_market'));
    }
}
