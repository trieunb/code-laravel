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
        \Log::info('test Search', $request->all());
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
            'data' => json_decode($this->template_market->getAllTemplateMarket($request->get('sortby'), $request->get('order'), $request->get('page'), $request->get('search')), true)
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

    public function view($id, Request $request)
    {
        $template = $this->template_market->getDetailTemplateMarket($id);
        $content = preg_replace('/contenteditable="true"|contenteditable=\'true\'/', '', $template->content);
        $content = str_replace("contenteditable='true'", '', $template->content);

        return view('api.market.view', compact('content'));
    }

    public function search(Request $request)
    {        
        return response()->json([
            'status_code' => 200,
            'data' => $this->template_market->search($request->get('search'))]
        );
    }
}