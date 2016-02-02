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
        $response = $this->template_market->createOrUpdateTemplateByManage($request, $result, \Auth::user()->id);
       
        return $response
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
        $sections = createClassSection();
        $data = createSection($request->get('content'), $sections);

        return $this->template_market->createOrUpdateTemplateByManage($request, $data, \Auth::user()->id)
            ? redirect()->route('admin.template.get.index')->with('message', 'Edit Template successfully!')
            : redirect()->back()->with('message', 'Error when create template!');
    }

    public function checkTitle(Request $request)
    {
    	return $this->template_market->checkExistsTitle($request->get('title'), $request->get('id')) ? 'false' : 'true';
    }

    public function index(Request $request)
    {
        $templates_market = $this->template_market->getAll();

        return view('admin.template.index', compact('templates_market'));
    }

    public function delete(Request $request, $id)
    {
        return $this->template_market->delete($id)
            ? response()->json(['status' => true])
            : response()->json(['status' => false]);
    }

    public function changeStatus(Request $request, $id)
    {
        $publish = $this->template_market->getById($id);
        $data = [
            'status' => ($publish->status == 1) ? $publish->status = 2 : $publish->status = 1
        ];

        return $this->template_market->update($data, $id)
            ? response()->json(['status' => true])
            : response()->json(['status' => false]);
    }

    public function postUpload(Request $request)
    {
        try {
            $file = $request->file('upload');
            $folder = public_path('uploads/template');
            $filename = md5(str_random(40)).time().'.'.$file->getClientOriginalExtension();
            $file->move($folder, $filename);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400, 'message' => $e->getMessage()]);
        }
    }

    public function browseImage(Request $request)
    {
        $test = $request->get('CKEditorFuncNum');
        $images = [];
        $files = \File::files(public_path('uploads/template'));

        foreach ($files as $file) {
            $images[] = pathinfo($file);
        }

        return view('admin.template.files', compact('test', 'images'));
    }

    public function showDatatableTemplate()
    {
        return $this->template_market->dataTableTemplate();
    }

    public function getView($id)
    {
        $template = $this->template_market->getById($id);

        return view('admin.template.view', ['title' => $template->title, 'content' => $template->content]);
    }

    public function postAction(Request $request)
    {
        try {
            if ($request->get('action') == 'delete') {
                $this->template_market->deleteMultiRecords($request->get('ids'));
            } else {
                $this->template_market->publishOrPendingMultiRecord($request->get('action'), $request->get('ids'));
            }

            return response()->json(['status_code' => 200]);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400]);
        }    
    }

    /**
     * For User
     */
    public function createForUser()
    {
        return view('user.template.create');
    }

    public function editTemplate($id)
    {
        $template = $this->template_market->getById($id);

        return view('user.template.edit', compact('template'));
    }

    public function postEditTemplate(TemplateFormRequest $request, $id)
    {
        $sections = createClassSection();
        $data = createSection($request->get('content'), $sections);

        return $this->template_market->createOrUpdateTemplateByManage($request, $data, \Auth::user()->id)
            ? redirect()->route('admin.template.get.index')->with('message', 'Edit Template successfully!')
            : redirect()->back()->with('message', 'Error when create template!');
    }

    public function getViewTemplate($id)
    {
        $template = $this->template_market->getById($id);

        return view('user.template.view', ['title' => $template->title, 'content' => $template->content]);
    }

    public function getTemplateForUser()
    {
        return view('user.template.index');
    }

    public function getDtatabableTemplateForUser()
    {
        return $this->template_market->DatatableTemplateForUser();
    }
}
