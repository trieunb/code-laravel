<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Template;
use App\Models\TemplateMarket;
use App\Models\User;
use App\Repositories\Invoice\InvoiceInterface;
use App\Repositories\Template\TemplateInterface;
use App\Repositories\User\UserInterface;
use Carbon\Carbon;
use DB;
use Date;
use Illuminate\Http\Request;
use Khill\Lavacharts\Lavacharts;
use Lava;

class ReportController extends Controller
{

    private $template;
    private $user;
    private $invoice;

    public function __construct(TemplateInterface $template, UserInterface $user,
        InvoiceInterface $invoice
    ){
        $this->template = $template;
        $this->user = $user;
        $this->invoice = $invoice;
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
        return view('admin.report.report_user', 
            compact('count_arr', 'lables', 'chart_gender', 'chart_age'));
    }

    public function reportUserByGender(Request $request)
    {
        $user_gender = $this->user->reportUserGender();

        return view('admin.report.report_user_gender', compact('user_gender'));
    }

    public function reportTemplate(Request $request)
    {
        
        $chart_month = $this->template->reportTemplateMonth($request->get('year'));
        $bought_report = $this->invoice->report($request->get('year'));
        
        return view('admin.report.report_template', compact('chart_month', 'bought_report'))
            ->with('year', $request->get('year'));
    }
}