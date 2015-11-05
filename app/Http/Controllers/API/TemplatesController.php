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

    public function getAllTemplate(Request $request)
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
                    'content' => $value['content'],
                    'thumbnail' => $value['thumbnail'],
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

    public function getDetailTemplate(Request $request, $template_id)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        if (is_null($template_id)) {
            return response()->json([
                'status_code' => 404,
                'status' => false,
            ]);
        }
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->template->getDetailTemplate($template_id, $user->id)
        ]);
    }

    public function edit(Request $request, $id)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => [
                'id' => $id,
                'title' => $tempalte->title,
                'content' => $template->content
            ]
        ]);
    }

    public function editView($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);
        $content = $template->content;

        return view()->make('frontend.template.full', compact('content'));
    }

    public function postEdit($id, Request $request)
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
            $educations[] = '<ul style="list-style:none">
                            <li><label style="font-weight:600">School: </label>' . $edu['school_name'] . '</li>
                            <li><label style="font-weight:600">Time: </label>' . $edu['start'] . '-' . $edu['end'] . '</li>
                            <li><label style="font-weight:600">Degree: </label>' . $edu['degree'] . '</li>
                            </ul><hr>';
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
                        border-bottom: 3px solid #D8D8D8;">' 
                        . implode('', $educations) . '</div>
                    </div>';

        $skills = [];
        foreach($user_info->user_skills as $sk) {
            $skills[] = '<ul style="list-style:none">
                        <li><label style="font-weight:600">Name: </label>' . $sk['skill_name'] . '</li>
                        <li><label style="font-weight:600">Point: </label>' . $sk['skill_test_point'] . '</li>
                        <li><label style="font-weight:600">Experience: </label>' . $sk['experience'] . '</li>
                        </ul><hr>';
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
            $work_histories[] = '<ul style="list-style:none">
                                <li><label style="font-weight:600">Company: </label>' . $histories['company'] . '</li>
                                <li><label style="font-weight:600">Time: </label>' . $histories['start'] . '-' . $edu['end'] . '</li>
                                <li><label style="font-weight:600">Description: </label>' . $histories['   job_description'] . '</li>
                                </ul><hr>';
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
                        border-bottom: 3px solid #D8D8D8;">' 
                        . implode('', $work_histories) . '</div>
                    </div>';

        $references = [];    
        foreach ($user_info->references as $ref) {
            $references[] = '<ul style="list-style:none">
                            <li><label style="font-weight:600">References: </label>' . $ref['reference'] . '</li>
                            <li><label style="font-weight:600">Content: </label>' . $ref['content'] . '</li>
                            </ul><hr>';
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
            $objectives[] = '<ul style="list-style:none">
                            <li><label style="font-weight:600">Title: </label>' . $obj['title'] . '</li>
                            <li><label style="font-weight:600">Content: </label>' . $obj['content'] . '</li>
                            </ul><hr>';
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
        $template_bs = Template::where('type', '=', 1)->first();
        if ( ! $template_bs) {
            $template_bs = new Template();
            $template_bs->user_id = $user_info->id;
            $template_bs->title = "Basic Template";
            $template_bs->type = 1;
        }
        $template_bs->content = $template_html;
        $template_bs->save();
        return response()->json([
                "status_code" => 200,
                "status" => true,
                "data" => $template_bs
            ]);
        
    }

    public function updateBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template_basic = $request->get('template_basic')['content'];
        $template_bs = Template::where('type', '=', 1)->first();
        $template_bs->content = $template_basic;
        $template_bs->save();
        return $template_bs->content;
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
        $result = $this->template->createTemplate($user->id, $request);
        
        return $result
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => $result])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error occurred when create template']);
    }

    public function attach($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        $template = $this->template->getById($id);
        $content = $template->content;
        $url = $request->url();
        
        \PDF::loadView('api.template.index', compact('content', 'url'))
            ->save(public_path('pdf/'.$template->title.'.pdf'));

        event(new sendMailAttachFile($user, '', public_path('pdf/'.$template->title.'.pdf')));

        return response()->json(['status_code' => 200, 'status' => true, 'message' => 'success']);
    }

    public function view($id, Request $request)
    {
        $template = $this->template->getById($id);
        $content = str_replace('contenteditable="true"', '', $template->content);
        
        return view()->make('api.template.index', compact('content'));
    }
}