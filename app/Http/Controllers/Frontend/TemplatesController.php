<?php

namespace App\Http\Controllers\Frontend;

use App\Helper\ZamzarApi;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\ConvertFile;
use App\Repositories\Template\TemplateInterface;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
	private $template;

    public function __construct(TemplateInterface $template)
    {
    	$this->middleware('jwt.auth');

		$this->template = $template;
    }

    public function detail($id)
    {
		$template = $this->template->getByid($id);
		
		return view()->make('frontend.template.view', compact('template'));
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
}
