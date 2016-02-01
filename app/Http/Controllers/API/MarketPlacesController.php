<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Repositories\TemplateMarket\TemplateMarketInterface;
use App\Repositories\Category\CategoryInterface;

class MarketPlacesController extends Controller
{
    /**
     * TemplateMarketInterface
     * @var $template_market
     */
    protected $template_market;

    public function __construct(TemplateMarketInterface $template_market)
    {
        // $this->middleware('jwt.auth');
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
            'data' => $this->template_market
                    ->getAllTemplateMarket(
                        $request->get('sortby'), 
                        $request->get('order'), 
                        $request->get('page'), 
                        $request->get('cat_id'), 
                        $request->get('search'))
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

        return view('api.market.view', compact('content'));
    }

    public function getListTemplateCategory(Request $request, CategoryInterface $category)
    {
        return count($category->getAll())
            ? response()->json(['status_code' => 200, 'data' => $category->getAll()], 200, [], JSON_NUMERIC_CHECK)
            : response()->json(['status_code' => 400, 'message' => 'Data not found!']);
    }
}