<?php

namespace App\Http\Controllers\API;

use App\Events\sendMailAttachFile;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Template;
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

    public function getTemplates(Request $request)
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
                    'title' => $value['title'],
                    'source' => $value['source'],
                    'source_convert' => $value['source_convert'],
                    'template' => $value['template'],
                    'thumbnail' => $value['thumbnail'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            Template::insert($data);
        }
        return response()->json(['status_code' => 200, 'status' => true]);
        
    }

    public function getDetailTemplate(Request $request, $template_id)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($template_id, $user->id);

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => [
                'id' => $template_id,
                'title' => $tempalte->title,
                'content' => $template->template
            ]
        ]);
    }

    public function getFull($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);

        $content = $template->template_full;

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => ['id' => $id,'title' => $template->title,'content' => $content]
        ]);
    }

    public function getFullEdit($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);
        $content = $template->template_full;

        return view()->make('frontend.template.full', compact('content'));
    }

    public function postFullEdit($id, Request $request)
    {
        return$this->template->getById($id, $request->get('content'))
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);
    }

    public function getBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->user->getProfile($user->id);

        return view()->make('frontend.template.basic_template', compact('template'));
    }

    public function postBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $user_info = $this->user->getProfile($user->id);
        $avatar = ($user_info->avatar)
                ? asset($user_info->avatar['origin']) 
                : asset('images/avatar.jpg');
        $header = '<div class="image-avatar" 
                    style="  position: relative;
                    width: 100%;">
                    <img style="width:100%;" id="image" src="' . $avatar . '">
                    <div class="text-info" 
                    style="position: absolute;
                    bottom: 30px;
                    width: 100%;
                    text-align:center;">
                        <p style="font-size:30px;">' . $user_info->firstname . ' ' . $user_info->lastname .'</p>
                        <span>' . $user_info->link_profile .'</span><br>
                        <span>'. $user_info->email .'</span>
                    </div></div>';
        
        $info = '<div class="info text-center" 
        style="background: #9b8578;
        color: white;
        font-weight:600;
        text-align:center;">
                    <span>' . $user_info->address . '</span><br>
                    <span>' . $user_info->city . ',' . $user_info->state . '</span><br>
                    <span>Tell:' . $user_info->mobile_phone . '</span>
                </div>';
        $intro = '<div class="content-box">
                    <div class="header-title" 
                    style="color: red;
                    font-weight:600;
                    padding:15px;">
                        <span>Infomation</span>
                    </div>
                    <div class="box" 
                    style="background: #f3f3f3;
                    padding: 15px;
                    border-top: 3px solid #D8D8D8;
                    border-bottom: 3px solid #D8D8D8;"><span>' . $user_info->infomation . '</span></div>
                </div>';
        $educations = [];
        foreach ($user_info->user_educations as $edu) {
            $educations[] = '<span>' . $edu['school_name'] . '</span> , ';
        }

        $educations = '<div class="content-box">
                        <div class="header-title"
                        style="color: red;
                        font-weight:600;
                        padding:15px;">
                            <span>Education</span>
                        </div>
                        <div class="box"
                        style="background: #f3f3f3;
                        padding: 15px;
                        border-top: 3px solid #D8D8D8;
                        border-bottom: 3px solid #D8D8D8;">
                            <label>School Name: </label>' . implode('', $educations) . '</div>
                    </div>';

        $skills = [];
        foreach($user_info->soft_skill as $sk) {
            $skills[] = "<span>" . $sk['question'] . "</span>,";
        }
        $skills = '<div class="content-box">
                        <div class="header-title"
                        style="color: red;
                        font-weight:600;
                        padding:15px;">
                            <span>Skills</span>
                        </div>
                        <div class="box"
                        style="background: #f3f3f3;
                        padding: 15px;
                        border-top: 3px solid #D8D8D8;
                        border-bottom: 3px solid #D8D8D8;">' . implode('', $skills) . ' </div>
                    </div>';

        $work_histories = [];
        foreach ($user_info->user_work_histories as $histories) {
            $work_histories[] = "<span>" . $histories['company'] . "</span> ,";
        }
        $work_histories = '<div class="content-box">
                        <div class="header-title"
                        style="color: red;
                        font-weight:600;
                        padding:15px;">
                            <span>Work Experience</span>
                        </div>
                        <div class="box"
                        style="background: #f3f3f3;
                        padding: 15px;
                        border-top: 3px solid #D8D8D8;
                        border-bottom: 3px solid #D8D8D8;">
                            <label>Company: </label>' . implode('', $work_histories) . '</div>
                    </div>';

        $references = [];    
        foreach ($user_info->references as $ref) {
            $references[] = "<span>" . $ref['reference'] . "</span>,";
        }
        $references = '<div class="content-box">
                        <div class="header-title"
                        style="color: red;
                        font-weight:600;
                        padding:15px;">
                            <span>Eeferences</span>
                        </div>
                        <div class="box"
                        style="background: #f3f3f3;
                        padding: 15px;
                        border-top: 3px solid #D8D8D8;
                        border-bottom: 3px solid #D8D8D8;">' . implode('', $references) . '</div>
                    </div>';

        $objectives = [];
        foreach ($user_info->objectives as $obj) {
            $objectives[] = "<span>" . $obj['title'] . "</span>,";
        }
        $objectives = '<div class="content-box">
                        <div class="header-title"
                        style="color: red;
                        font-weight:600;
                        padding:15px;">
                            <span>objectives</span>
                        </div>
                        <div class="box"
                        style="background: #f3f3f3;
                        padding: 15px;
                        border-top: 3px solid #D8D8D8;
                        border-bottom: 3px solid #D8D8D8;">' . implode('', $objectives) . '</div>
                    </div>';

        $template_html = '<div class="container">
            <div class="row">'
                . $header
                . $info
                . $intro
                . $educations 
                . $skills
                . $work_histories
                . $references
                . $objectives .
            '</div>
        </div>';
        $template_bs = Template::where('template_basic', '=', 1)->first();
        if ( ! $template_bs) {
            $template_bs = new Template();
            $template_bs->user_id = $user_info->id;
            $template_bs->title = "Basic Template";
            $template_bs->template_basic = 1;
        }
        $template_bs->template_full = $template_html;
        $template_bs->save();
        return $template_bs->template_full;
        // return response()->json([
        //         "status_code" => 200,
        //         "status" => true,
        //         "template" => preg_replace('/\t|\n+/', '', $template_bs->template_full)
        //     ]);
        
    }

    public function updateBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template_basic = $request->get('template_full');
        $template_bs = Template::where('template_basic', '=', 1)->first();
        $template_bs->template_full = $template_basic;
        $template_bs->save();
        return $template_bs->template_full;
        // return response()->json([
        //         "status_code" => 200,
        //         "status" => true,
        //         "message" => "updated successfully"
        //     ]);
    }

    public function create()
    {
        return view()->make('api.template.create');
    }

    public function postCreate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template_full = preg_replace('/\t|\n+/', '', $request->get('template_full'));

        return $this->template->createTemplate($user->id, $request)
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Create template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error occurred when create template']);
    }

    public function attach($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        $template = $this->template->getById($id);
        $template_full = $template->template_full;
        $url = $request->url();
        
        \PDF::loadView('api.template.index', compact('template_full', 'url'))
            ->save(public_path('pdf/'.$template->title.'.pdf'));

        event(new sendMailAttachFile($user, '', public_path('pdf/'.$template->title.'.pdf')));

        return response()->json(['status_code' => 200, 'status' => true, 'message' => 'success']);
    }

    public function view($id, Request $request)
    {
        $template = $this->template->getById($id);
        $template_full = str_replace('contenteditable="true"', '', $template->template_full);
        
        return view()->make('api.template.index', compact('template_full'));
    }
}