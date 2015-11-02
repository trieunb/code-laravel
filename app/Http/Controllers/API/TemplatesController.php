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

    public function showEditContent($id, $section, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id)->template;
        $content = array_get($template, $section);

        return $content == null
            ? response()->json(['status_code' => 400, 'status' => false, 'message' => 'Section not exists'])
            : view()->make('frontend.template.edit_content', compact('content'));
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

    public function getFull($id, Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        $template = $this->template->getDetailTemplate($id, $user->id);
        $content = '';
        foreach ($template->template as $html) {
            $content .= $html;
        }

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

    public function postTemplatesFromMarket(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $request->get('option_templates')
        ]);
    }
}