<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\TemplateMarket\TemplateMarketInterface;

class MarketPlacesController extends Controller
{
    /**
     * TemplateMarketInterface
     * @var $template_market
     */
    protected $template_market;

    public function __construct(TemplateMarketInterface $template_market)
    {
        $this->middleware('jwt.auth');
        
        $this->template_market = $template_market;
    }

    public function getAllTemplateMarket(Request $request)
    {
        $token = \JWTAuth::toUser($request->get('token'));
        
        if (!$token) {
            return response()->json([
                'status_code' => 500,
                'status' => false,
                'message' => 'token provider'
            ], 500);
        }

        return response()->json([
            'status_code' => 200,
            'status' => true,
            'data' => $this->template_market->getAllTemplateMarket()
        ]);
    }

    public function getDetailTemplateMarket(Request $request, $template_id)
    {
        $token = \JWTAuth::toUser($request->get('token'));
        $template_market = $this->template_market->getDetailTemplateMarket($template_id);
        
        return $template_market
            ? response()->json([
                'status_code' => 200, 'status' => true, 'data' => $template_market
            ])
            : response()->json(['status_code' => 404, 'status' => false, 'message' => 'Page not found']);
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

    public function view($id, Request $request)
    {
        $template = $this->template_market->getDetailTemplateMarket($id);

        $content = str_replace('contenteditable="true"', '', $template->content);

        return view('api.market.view', compact('content'));
    }
}