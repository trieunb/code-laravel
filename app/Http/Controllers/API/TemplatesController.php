<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\User\UserInterface;
use App\Repositories\Template\TemplateInterface;
use Carbon\Carbon;

class TemplatesController extends Controller
{
    protected $user;

    protected $template;

    public function __construct(UserInterface $user, TemplateInterface $template)
    {
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
        
        if (is_null($template_id)) {
            return response()->json([
                'status_code' => 404,
                'status' => false,
            ]);
        }
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => [
                'id' => $template_id,
                'title' => $this->template->getDetailTemplate($template_id, $user->id)->title,
                'content' => $this->template->getDetailTemplate($template_id, $user->id)->template
            ]
        ]);
    }

    public function showEditContent($id, $section, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id)->template;
        $content = array_get($template, $section);

        return $content == null
            ? response()->json(['status_code' => 400, 'status' => false, 'message' => 'Section not exists'])
            : view()->make('frontend.template.edit_content', compact('content'));
    }

    public function postEdit($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);
        $data = $template->template;
        array_set($data, $request->get('section'), $request->get('content'));
        $template->template = $data;
        return $template->save()
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);
    }

    public function getFull($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);

        $content = $template->template_full;

         return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => [
                'id' => $id,
                'title' => $template->title,
                'content' => $content
            ]
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
        $template = $this->template->getById($id);
        $template->template_full = $request->get('content');

        return $template->save()
            ? response()->json(['status_code' => 200, 'status' => true, 'message' => 'Edit template successfully'])
            : response()->json(['status_code' => 400, 'status' => false, 'message' => 'Error when edit Template']);
    }

    public function getBasicTemplate(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->user->getProfile($user->id);
        return $template;die();
        // return view()->make('frontend.template.basic_template', compact('template'));
    }

}