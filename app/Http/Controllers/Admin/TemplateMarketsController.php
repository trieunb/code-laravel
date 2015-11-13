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
        return $this->template_market->createOrUpdateTemplateByManage($request, \Auth::user()->id)
            ? response()->json(['status' => true])
            : response()->json(['status' => false]);
    }

    public function edit($id)
    {
    	$template = $this->template_market->getById($id);

    	return view('admin.template.edit', compact('template'));
    }

    public function postEdit(TemplateFormRequest $request)
    {
        return $this->template_market->createOrUpdateTemplateByManage($request, \Auth::user()->id)

            ? response()->json(['status' => true])
            : response()->json(['status' => false]);
    }

    public function checkTitle(Request $request)
    {
    	return $this->template_market->checkExistsTitle($request->get('title'), $request->get('id')) ? 'false' : 'true';
    }

    public function detail($id)
    {
        $template = $this->template_market->getById($id);
        
        return view('admin.template.detail', compact('template'));
    }

    public function index(Request $request)
    {
        $templates_market = $this->template_market->getAll();
        return view('admin.template.index', compact('templates_market'));
    }

    public function delete(Request $request, $id)
    {
        $this->template_market->delete($id);
        return redirect()->back();

    }

    public function changeStatus(Request $request)
    {
        $status = $request->input('status');
        if ($request->ajax()) {
            return response()->json([
                'status' => $status,
                'message' => "ok"
            ]);
        }
    }
}
