<?php

namespace App\Http\Controllers\Admin;

use Khill\Lavacharts\Lavacharts;
use App\Http\Controllers\Controller;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\User\UserInterface;
use App\Http\Requests;
use Illuminate\Http\Request;
use Lava;
use App\Models\User;
use App\Models\TemplateMarket;
use App\Models\Template;
use Carbon\Carbon;
use DB;
use Date;

class ReportController extends Controller
{

    private $template;
    private $user;

    public function __construct(TemplateInterface $template, UserInterface $user)
    {
        $this->template = $template;
        $this->user = $user;
    }

    public function reportUserByMonth(Request $request)
    {

        $users = User::select('*', DB::raw('MONTH(created_at) as month'),DB::raw('COUNT(id) AS count'))->groupBy('month')->orderBy('month', 'ASC')->get();
        $count = 0;
        foreach ($users as $key => $user) {
            $lables[] = date_format($user->created_at, 'Y-m');
            $count = $count + $user->count;
            $count_arr[] = $count;
        }
        return view('admin.report.report_user', compact('count_arr', 'lables'));
    }

    public function reportUserByGender(Request $request)
    {
        $user_gender = $this->user->reportUserGender();

        return view('admin.report.report_user_gender', compact('user_gender'));
    }

    public function reportTemplate(Request $request)
    {
        
        $chart_month = $this->template->reportTemplateMonth();
        $chart_gender = $this->template->reportTemplateGender();

        return view('admin.report.report_template', compact('chart_month', 'chart_gender'));
    }
}