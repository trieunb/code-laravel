<?php

namespace App\Http\Controllers\Admin;

use Khill\Lavacharts\Lavacharts;
use App\Http\Controllers\Controller;
use App\Repositories\Template\TemplateInterface;
use App\Http\Requests;
use Illuminate\Http\Request;
use Lava;
use App\Models\User;
use App\Models\TemplateMarket;
use App\Models\Template;
use Carbon\Carbon;
use DB;

class ReportController extends Controller
{

    private $template;

    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    public function reportUser(Request $request)
    {
        $lava = new Lavacharts;
        $userTable = $lava->DataTable();

        $userTable->addStringColumn('Date of Month')
                    ->addNumberColumn('Users');

        $users = User::select('*', DB::raw('MONTH(created_at) as month'),DB::raw('COUNT(id) AS count'))->groupBy('month')->orderBy('month', 'ASC')->get();

        foreach ($users as $user) {
            $rowData = array(
                date_format($user->created_at, 'Y-m'), $user->count
            );
            $userTable->addRow($rowData);
        }

        $chart = $lava->ColumnChart('UserChart');

        $chart->datatable($userTable);

        $chart = $lava->render('ColumnChart', 'UserChart', 'user-chart', true);

        return view('admin.report.report_user', compact('chart'));
    }

    public function reportTemplate(Request $request)
    {
        
        $chart_month = $this->template->reportTemplateMonth();
        $chart_gender = $this->template->reportTemplateGender();

        return view('admin.report.report_template', compact('chart_month', 'chart_gender'));
    }
}