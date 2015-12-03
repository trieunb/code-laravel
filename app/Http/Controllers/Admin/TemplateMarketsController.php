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
        $sections = createClassSection();
        $result = createSection($request->get('content'), $sections);
        
        return $this->template_market->createOrUpdateTemplateByManage($request, $result, \Auth::user()->id)
            ? redirect()->route('admin.template.get.index')->with('message', 'Create Template successfully!')
            : redirect()->back()->with('message', 'Error when create template!');
    }

    public function edit($id)
    {
    	$template = $this->template_market->getById($id);

    	return view('admin.template.edit', compact('template'));
    }

    public function postEdit(TemplateFormRequest $request, $id)
    {
        $dom = new \DOMDocument();
        dd($dom->loadHTML( $request->get('content')));
       
        return;
        // return element_to_obj($dom->documentElement);
        $sections = createClassSection();
        $data = createSection($request->get('content'), $sections);
        
        return $this->template_market->createOrUpdateTemplateByManage($request, $data, \Auth::user()->id)
            ? redirect()->route('admin.template.get.index')->with('message', 'Create Template successfully!')
            : redirect()->back()->with('message', 'Error when create template!');
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

    public function changeStatus(Request $request, $id)
    {
        $data = [
            'status' => $request->input('status')
        ];
        $this->template_market->update($data, $id);

        return redirect()->back();
    }
}
