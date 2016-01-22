<?php

namespace App\Http\Controllers\API;

use App\Events\RenderImageAfterCreateTemplate;
use App\Events\sendMailAttachFile;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Template;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\ValidatorApi\RenameResume_rule;
use Illuminate\Contracts\Validation\ValidationException;
use App\ValidatorApi\ValidatorAPiException;

class TemplatesController extends Controller
{
    protected $user;

    protected $template;

    public function __construct(UserInterface $user, TemplateInterface $template)
    {
        $this->middleware('jwt.auth', ['except' => 'view']);

        $this->user = $user;
        $this->template = $template;
    }

    public function index(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->template->getMyTemplates($user->id, $request->get('page'), $request->get('search'))
        ]);
    }

    public function show($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->template->forUser($id, $user->id)
        ]);
    }

    public function getSections($id, Request $request)
    {
        $sections = $this->template->getById($id)->section;
        $user_id = \JWTAuth::toUser($request->get('token'));
        return view('api.template.section', compact('sections'));
    }

    public function edit($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->forUser($id, $user->id);

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => [
                'id' => $id,
                'title' => $template->title,
                'content' => $template->content
            ]
        ]);
    }

    public function editView($id, $section, Request $request)
    {
        try {
            $user_id = \JWTAuth::toUser($request->get('token'))->id;
            $template = $this->template->forUser($id, $user_id);
            $content = array_get($template->section, $section);
            $status = null;
            $setting = [];

            foreach (\Setting::get('user_status') as $k => $v) {
                if ($v['id'] == $template->user->status)
                    $status = $v;
                $setting[$v['id']] = $v['value'];
            }


            $template->user->status = $template->user->status != 0 && $template->user->status != null ? $status : null;

            return view()->make('api.template.edit', compact('content', 'section',
                'user_id', 'template', 'setting')
            );
        } catch (\Exception $e) {
           return response()->json(['status_code' => 400, 'message' => $e->getMessage()]);
        }

    }

    public function postEdit($id, $section, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        $result = $this->template->editTemplate($id, $user->id, $section, $request);

        if (!$result)
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);

        $render = event(new RenderImageAfterCreateTemplate($result->id, $result->content));

        return $render
            ? response()->json(['status_code' => 200, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'message' => 'Error when render file']);
    }

    public function editPhoto($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        if ( !$request->hasFile('photo'))
            return response()->json(['status_code' => '400']);

        $validator = \Validator::make($request->all(), ['avatar' => 'image']);

        if ($validator->fails()) {
            return response()->json(['status_code' => 422]);
        }

        $response = $this->template->editPhoto($id, $user->id, $request->file('photo'));

        if ( !$response)
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when save file.']);
        if (\File::delete(public_path($response['template']->source_file_pdf)))
            event(new RenderImageAfterCreateTemplate($response['template']->id, $response['template']->content));
        \Log::info('response edit photo', ['img' => $response['template']->image, 'pdf' => $response['template']->source_file_pdf]);


        return $response
            // ? response()->json(['status_code' => 200, 'data' => asset($response['avatar'])])
            ? redirect()->back()
            : response()->json(['status_code' => 400]);
    }

    public function postBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $user_info = $this->user->getProfile($user->id);
        $content = view('frontend.template.basic_template', ['user_info' => $user_info])->render();
        $sections = createClassSection();
        $data = createSection($content, $sections);
        $template = $this->template->createTemplateBasic($user_info->id, $data);
        return $template->content;
        if ( !$template) {
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when create template']);
        }

        $render = event(new RenderImageAfterCreateTemplate($template->id, $template->content));
        if ( !$render) {
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when render pdf']);
        }
        return response()->json([
            "status_code" => 200,
            "status" => true,
            "data" => $template
        ]);
    }

    public function postDelete($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getById($id);

        if ( !$template) {
            return response()->json([
                'status_code' => 404,
                'status' => false,
                'message' => 'page not found'
            ]);
        }

        return $this->template->deleteTemplate($id, $user->id)
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Delete template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when delete Template']);
    }

    public function create()
    {
        return view()->make('api.template.create');
    }

    public function postCreate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $result = $this->template->createTemplate($user->id, $request);

        return $result
            ? response()->json(['status_code' => 200, 'status' => true, 'data' => $result])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error occurred when create template']);
    }

    public function attach($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getById($id);
        $sourcePDF = public_path($template->source_file_pdf);
        \Log::info('Send resume to user mail', [$id, $user->id, \File::exists($sourcePDF), $sourcePDF]);
        event(new sendMailAttachFile($user, $sourcePDF));

        return response()->json(['status_code' => 200, 'status' => true, 'message' => 'success']);
    }

    public function view($id, Request $request)
    {
        $template = $this->template->getById($id);
        $content = str_replace('contenteditable="true"', '', $template->content);
        $content = str_replace("contenteditable='true'", '', $template->content);
        return view('api.template.view', compact('content','template'));
    }

    public function menu($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->forUser($id, $user->id);
        $token = $request->get('token');
        try {
            $section = createSectionData($template);

            return view('api.template.section', compact('section', 'token', 'template', 'user'));
        } catch (\Exception $e) {
            return response()->json(['status' => 400, 'message' => $e->getMessage()]);
        }
    }

    public function editFullTemplate(Request $request, $id)
    {
        return $this->template->editFullScreenTempalte($id, \Auth::user()->id, $request)
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);
    }

    public function apply($id, $section, Request $request)
    {
        try {
            $user_id = \JWTAuth::toUser($request->get('token'))->id;
            $template = $this->template->forUser($id, $user_id);
            $token = $request->get('token');
            $data = null;
            $personalInfomation = [
                'address', 'email', 'infomation'
            ];

            if (in_array($section, $personalInfomation)) {
                $data = $this->user->getById($user_id)->$section;
            } else if ($section == 'name') {
                $data = $this->user->getById($user_id)->present()->name;
            } else if ($section == 'linkedin' || $section == 'profile_website') {
                $data =  $this->user->getById($user_id)->link_profile;
            } else if ($section == 'phone') {
                $data = $this->user->getById($user_id)->mobile_phone;
            } else if ($section == 'photo') {
                $data = $this->user->getById($user_id)->avatar['origin'];
            } else if ($section == 'availability') {
                foreach (\Setting::get('user_status') as $status) {
                    if ($status['id'] == $this->user->getById($user_id)->status) {
                        $data =  $template->type == 2
                            ? '<div lang="availability" class="availability content-box" contenteditable="true">'
                                .'<div class="header-title" style="color: red;font-weight:600;padding:15px;">'
                                .'<span>Availability</span></div>'
                                .'<div class="box" style="background: #f3f3f3;padding: 15px;border-top: 3px solid #D8D8D8;border-bottom: 3px solid #D8D8D8;">'
                                .'<p>'.$status['value'].'</p></div></div>'
                            : '<div lang="availability" class="availability" contenteditable="true"><h3 style="font-weight:600">Availability</h3><p style="font-weight:600">'.$status['value'].'</p></div>';
                    }
                }
            }

            $result = is_string($data)
                ? apply_data_for_section_infomation($section, $data, $template->content)
                :apply_data_for_other($section, $template->content, $user_id);

           /* $response = $this->template->applyForInfo($template, $section, $result);

            event(new RenderImageAfterCreateTemplate(
                $response['template']->id,
                $response['template']->content,
                $response['template']->slug)
            );*/

            return $result
                ? response()->json(['status_code' => 200, 'data' => $result['section']])
                : response()->json(['status_code' => 400]);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400]);
        }
    }

    public function getFromProfile($id, $section, Request $request)
    {
        $template = $this->template->getById($id);
        $html = new \Htmldom($template->content);

        foreach ($html->find('.'.$section) as $element) {
            $element->outertext = $request->get('content');
            $data['section'] = $element->outertext;
        }

        $data['content'] = $html->save();

        return $this->template->applyForInfo($template, $section, $data)
            ? response()->json(['status_code' => 200])
            : response()->json(['status_code' => 400]);
    }

    public function renameResume(Request $request, $id, RenameResume_rule $rename_rule)
    {

        try {
        $user = \JWTAuth::toUser($request->get('token'));

        $rename_rule->validate($request->all());

        $template = $this->template->forUser($id, $user->id);
        $template->title = $request->get('title');

        return $template->save()
            ? response()->json(['status_code' => 200, 'status' => true])
            : response()->json(['status_code' => 404, 'status' => false]);

        } catch(ValidatorAPiException $e) {
            return response()->json([
                'status_code' => 401,
                'status' => false,
                'message' => $e->getErrors()
            ], 401);
        }

    }

}
