<?php

namespace App\Http\Controllers\Frontend;

use App\Events\RenderImageAfterCreateTemplate;
use App\Helper\ZamzarApi;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\CreateTemplateRequest;
use App\Jobs\ConvertFile;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Http\Request;
use Imagick;

class TemplatesController extends Controller
{
	private $template;

    public function __construct(TemplateInterface $template)
    {
    	$this->middleware('jwt.auth', ['except' => ['create']]);

		$this->template = $template;
    }

    public function detail($id, Request $request)
    {
		$template = $this->template->getByid($id);
		return response()->json(['data' => $template->template]);
		return view()->make('frontend.template.detail', compact('template'));
    }

    public function convert(Request $request)
	{
		// $template = $this->template->getByid($)
		$convert = new ZamzarApi(public_path('CURRICULUM VITAE_TanHt.docx'), 'html');
		$data = $convert->startingConvert();
		if ( !is_array($data)) {
			return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when convert file']);
		}

		$this->dispatch(new ConvertFile($convert, $data, public_path('test.zip')));
	}

	public function edit($id, Request $request)
	{
		$template = $this->template->getByid($id);
		\Storage::delete($template->source);
		$html = new \Htmldom($template->source_convert);

		foreach ($html->find('body') as $element) {
			$element->outertext = $request->body; 
		}
		
		$html->save($template->source_convert);
	}

	public function create(Request $request)
	{
		$token = $request->get('token');

		return view()->make('frontend.template.create', compact('token'));
	}

	public function postCreate(CreateTemplateRequest $request)
	{
		$user = \JWTAuth::toUser($request->get('token'));
		$result = $this->template->createTemplate($user->id, $request);
	  	$template = event(new RenderImageAfterCreateTemplate($result->id, $result->content, $result->title));
	  	
		return $template
			? response()->json(['status_code' => 200, 'status' => true, 'data' => $template])
			: response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error occurred when create template']);
	}
}
