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

    public function edit(Request $request, $id)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);

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
        $info = "<span>" . $user_info->infomation . "<span>";
        $skills = [];
        foreach($user_info->soft_skill as $sk) {
            $skills[] = "<li>" . $sk['question'] . "</li>";
        }
        $skills = "<h1>Skill</h1><ul>" . implode('', $skills) . "</ul>";

        $educations = [];
        foreach ($user_info->user_educations as $edu) {
            $educations[] = "<li>" . $edu['school_name'] . "</li>";
        }
        $educations = "<ul>" . implode('', $educations) . "</ul>";

        $work_histories = [];
        foreach ($user_info->user_work_histories as $histories) {
            $work_histories[] = "<li>" . $histories['company'] . "</li>";
        }
        $work_histories = "<h1>Work</h1><ul>" . implode('', $work_histories) . "</ul>";

        $references = [];    
        foreach ($user_info->references as $ref) {
            $references[] = "<li>" . $ref['reference'] . "</li>";
        }
        $references = "<h1>References</h1><ul>" . implode('', $references) . "</ul>";

        $objectives = [];
        foreach ($user_info->objectives as $obj) {
            $objectives[] = "<li>" . $obj['title'] . "</li>";
        }
        $objectives = "<h1>Objectives</h1><ul>" . implode('', $objectives) . "</ul>";
        // return $objectives;die();
        $template = [
            "info" => $info,
            "education" => $educations,
            "work_histories" => $work_histories,
            "skills" => $skills,
            "references" => $references,
            "objectives" => $objectives
        ];

        $templates = new Template();
        $templates->user_id = $user_info->id;
        $templates->title = "Basic Template";
        $templates->template = $template;
        return $templates; die();
        if ($templates->save()) {
            return response()->json([
                "status_code" => 200,
                "status" => true,
                "message" => "saved successfully"
            ]);
        } 
    }

    public function create()
    {
        return view()->make('api.template.create');
    }

    public function postCreate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $result = $this->template->createTemplate($user->id, $request);

        $template = event(new RenderImageAfterCreateTemplate($result->id, $result->content, $result->title));

        return $template
            ? response()->json(['status_code' => 200, 'status' => true, 'data' => $template])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error occurred when create template']);
    }

    public function attach($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));

        $template = $this->template->getById($id);
        $content = $template->content;
        $render = true;
        
        \PDF::loadView('api.template.index', compact('content', 'render'))
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