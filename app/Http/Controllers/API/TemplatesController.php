<?php

namespace App\Http\Controllers\API;

use App\Events\RenderImageAfterCreateTemplate;
use App\Events\sendMailAttachFile;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Template;
use App\Models\User;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    protected $user;

    protected $template;

    public function __construct(UserInterface $user, TemplateInterface $template)
    {
        $this->middleware('jwt.auth');

        $this->user = $user;
        $this->template = $template;
    }

    public function index(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->user->getTemplateFromUser($user->id)->templates
        ]); 
    }

    public function postTemplates(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        if ($request->has('templates')) {
            $data = [];

            foreach ($request->get('templates') as $value) {
                $data[] = [
                    'user_id' => $user->id,
                    'cat_id' => $value['cat_id'],
                    'title' => $value['title'],
                    'slug' => str_slug($value['title']),
                    'content' => $value['content'],
                    'image' => $value['image'],
                    'price' => $value['price'],
                    'status' => $value['status'],
                    'type' => $value['type'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            Template::insert($data);
        }

        return response()->json(['status_code' => 200, 'status' => true]);
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

    public function getSections($id)
    {
        $sections = $this->template->getById($id)->section;

        return view('template.api.section', compact('sections'));
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
    }

    public function postEdit($id, $section, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        $result = $this->template->editTemplate($id, $user->id, $section, $request);

        if (!$result) 
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);
        
        $render = event(new RenderImageAfterCreateTemplate($result->id, $result->content, $result->slug));
        
        return $render
            ? response()->json(['status_code' => 200, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'message' => 'Error when render file']);
    }

    public function editPhoto($id, Request $request)
    {

        if ( !$request->hasFile('avatar')) 
            return response()->json(['status_code' => '400']);
        
        $validator = \Validator::make($request->all(), ['avatar' => 'image']);

        if ($validator->fails()) {
            return response()->json(['status_code' => 422]);
        }

        $response = $this->template->editPhoto($id, \Auth::user()->id, $request->file('avatar'));
        
        if ( !$response) 
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);

        event(new RenderImageAfterCreateTemplate($response['template']->id, $response['template']->content, $response['template']->slug));
        
        return $response
            ? response()->json(['status_code' => 200, 'data' => asset($response['avatar'])])
            : response()->json(['status_code' => 400]);
    }

    public function postBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $user_info = $this->user->getProfile($user->id);
        $age = Carbon::createFromFormat("Y-m-d", $user_info->dob)->age;
        $content = view('frontend.template.basic_template', ['user_info' => $user_info, 'age' => $age])->render();

        $section = [
            'reference' => createSectionBasic('.reference', $content),
            'name' => createSectionBasic('.name', $content),
            'address' => createSectionBasic('.address', $content),
            'email' => createSectionBasic('.email', $content),
            'phone' => createSectionBasic('.phone', $content),
            'activitie' => createSectionBasic('.activitie', $content),
            'profile_website' => createSectionBasic('.profile_website', $content),
            'education' => createSectionBasic('.education', $content),
            'personal_test' => createSectionBasic('.personal_test', $content),
            'work' => createSectionBasic('.work', $content),

            'reference' => createSectionBasic('.reference', $content),
            'objective' => createSectionBasic('.objective', $content),
            'key_qualification' => createSectionBasic('.key_qualification', $content),
            'photo' => createSectionBasic('.photo', $content),
            'availability' => createSectionBasic('.availability', $content),
        ];

        $template = $this->template->createTemplateBasic($user_info->id, $section, $content);
        if ( !$template) {
            return response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when create template']);
        }
        
        $render = event(new RenderImageAfterCreateTemplate($template->id, $template->content, $template->slug));
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

        if ( ! \File::exists($sourcePDF)) {
            \PDF::loadView('api.template.index', ['content' => $template->content])
            ->save(public_path('pdf/'.$template->slug.'.pdf'));
        }
      
        event(new sendMailAttachFile($user, '', $sourcePDF));

        return response()->json(['status_code' => 200, 'status' => true, 'message' => 'success']);
    }

    public function view($id, Request $request)
    {
        $template = $this->template->getById($id);
        $content = str_replace('contenteditable="true"', '', $template->content);

        return view('api.template.view', compact('content','template'));
    }

    public function menu($id, Request $request)
    {
        $template = $this->template->forUser($id, \Auth::user()->id);
        $token = $request->get('token');
        $section = createSectionData($template);

        return view('api.template.section', compact('section', 'token', 'template'));
    }

    public function editFullTemplate(Request $request, $id)
    {
        
        $template = $this->template->forUser($id, \Auth::user()->id);
        $sections = createClassSection();
        $result = createSection($request->get('content'), $sections);
        $template->content = $request->get('content');
        unset($result['content']);
        $template->section = $result;
        $template->save();
        $render = event(new RenderImageAfterCreateTemplate($template->id, $template->content, $template->slug));
        return $render 
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);
    }

    public function apply($id, $section, Request $request)
    {
        try {
            $template = $this->template->forUser($id, \Auth::user()->id);
            $token = $request->get('token');
            $data = null;
            $personalInfomation = [
                'address', 'email', 'infomation'
            ];

            if (in_array($section, $personalInfomation)) {
                $data = User::findOrFail(\Auth::user()->id)->pluck($section);
            } else if ($section == 'name') {
                $data = User::findOrFail(\Auth::user()->id)->present()->name;
            } else if ($section == 'linkedin' || $section == 'profile_website') {
                $data =  User::findOrFail(\Auth::user()->id)->link_profile;
            } else if ($section == 'phone') {
                $data = User::findOrFail(\Auth::user()->id)->mobile_phone;
            } else if ($section == 'photo') {
                $data = User::findOrFail(\Auth::user()->id)->avatar['origin'];
            } else if ($section == 'availability') {
                foreach (\Setting::get('user_status') as $status) {
                    if ($status['id'] == \App\Models\User::findOrFail(\Auth::user()->id)->status) {    
                        $data =  $template->type == 2 
                            ? '<div class="availability content-box" contenteditable="true">'
                                .'<div class="header-title" style="color: red;font-weight:600;padding:15px;">'
                                .'<span>Availability</span></div>'
                                .'<div class="box" style="background: #f3f3f3;padding: 15px;border-top: 3px solid #D8D8D8;border-bottom: 3px solid #D8D8D8;">'
                                .'<p>'.$status['value'].'</p></div></div>'
                            : '<div class="availability" contenteditable="true"><h3 style="font-weight:600">Availability</h3><p style="font-weight:600">'.$status['value'].'</p></div>';        
                    }
                }
            }

            if ( is_string($data)) {
                $result = apply_data_for_section_infomation($section, $data, $template->content); 
            } else  {
                $result = apply_data_for_other($section, $template->content);
            }

            $response = $this->template->applyForInfo($template, $section, $result);
            
            event(new RenderImageAfterCreateTemplate($response['template']->id, $response['template']->content, $response['template']->slug));
            
            return $response
                ? response()->json(['status_code' => 200, 'data' => $response['section']])
                : response()->json(['status_code' => 400]);
        } catch (\Exception $e) {
            return response()->json(['status_code' => 400]);
        }
    }
}