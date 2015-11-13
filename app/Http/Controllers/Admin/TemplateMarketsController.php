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
        
        /*$html = new \Htmldom();
        $html->load($request->get('content'));
        $contentProfile = '';
        $str = $request->get('content');
        foreach ($html->find('div.profile') as $key => $e) {
           
            if ($key != 0) {
                $contentProfile .= '<br>'.$e->innertext;
                $content = str_replace($e->outertext, '', $str);
                $str = $content;
            }
           
        }
         
        foreach ($html->find('div.profile') as $k => $e) {
            if ($k == 0) {
                $contentProfile = $e->innertext.$contentProfile;
                $outerCurrent = $e->outertext;
                $e->{'contentediable'} = 'true';
                $outer = str_replace($outerCurrent, $e->outertext, $str);
                $content = str_replace($e->innertext, $contentProfile, $outer);
            }
        }*/
        $sections = ['div.profile', 'div.education', 'div.work',
            'div.objective', 'div.reference', 'div.skill',
        ];
        $result = createSection($request->get('content'), $sections);
        // $content = createSection($content, 'div.education');

        return $this->template_market->createOrUpdateTemplateByManage($request, $result, \Auth::user()->id)
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
        $sections = ['div.profile', 'div.education', 'div.work',
            'div.objective', 'div.reference', 'div.skill',
        ];
        $data = createSection($request->get('content'), $sections);
        
        return $this->template_market->createOrUpdateTemplateByManage($request, $result, \Auth::user()->id)

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

    public function changeStatus(Request $request, $status)
    {
        
    }
}
