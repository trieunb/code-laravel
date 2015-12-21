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

        $users = User::select('*', 
            DB::raw('YEAR(created_at) as year'), 
            DB::raw('MONTH(created_at) as month'), 
            DB::raw('COUNT(id) AS count'))
        ->groupBy('year')
        ->groupBy('month')
        ->orderBy('created_at', 'ASC')
        ->get();
        $count = 0;
        foreach ($users as $key => $user) {
            $lables[] = date_format($user->created_at, 'Y-m');
            $count = $count + $user->count;
            $count_arr[] = $count;
        }
        $chart_gender = $this->user->reportUserGender();
        $chart_age = $this->user->reportUserAge();
        $chart_region = $this->user->reportUserRegion();
        return view('admin.report.report_user', 
            compact('count_arr', 'lables', 'chart_gender', 'chart_age', 'chart_region'));
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