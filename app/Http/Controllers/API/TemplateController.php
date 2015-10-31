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

class TemplateController extends Controller
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
            'data' => $this->user->getTemplateFromUser($user->id)
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
                    'source' => $value['source'],
                    'source_convert' => $value['source_convert'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            Template::insert($data);
        }
        return response()->json(['status_code' => 200, 'status' => true]);
        
    }

    public function getAllTemplatesFromMarket(Request $request)
    {
        $user = \JWTAuth::toUser($request->get('token'));
        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->user->getAlltemplatesFromMarketPlace($user->id)
        ]);

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
            'data' => $this->template_market->getDetailTemplateMarket($template_id)
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