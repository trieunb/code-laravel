<?php

namespace App\Http\Controllers\Admin;

use Khill\Lavacharts\Lavacharts;
use App\Http\Controllers\Controller;
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
        $lava = new Lavacharts;
        $templateTable = $lava->DataTable();

        $templateTable->addStringColumn('Date of Month')
                    ->addNumberColumn('Templates');

        $templates_m = Template::select('*', DB::raw('MONTH(created_at) as month'),DB::raw('COUNT(id) AS count'))->groupBy('month')->orderBy('month', 'ASC')->get();

        foreach ($templates_m as $temp_m) {
            $rowData = array(
                date_format($temp_m->created_at, 'Y-m'), $temp_m->count
            );
            $templateTable->addRow($rowData);
        }

        $chart_month = $lava->ColumnChart('TemplateChartMonth');

        $chart_month->datatable($templateTable);

        $chart_month = $lava->render('ColumnChart', 'TemplateChartMonth', 'template-chart', true);
        
        //// template

        $templates_g = Template::select('*', DB::raw('MONTH(created_at) as month'),DB::raw('COUNT(id) AS count'))->groupBy('month')->orderBy('month', 'ASC')->get();

        foreach ($templates_g as $temp_g) {
            $rowData = array(
                date_format($temp_m->created_at, 'Y-m'), $temp_m->count
            );
            $templateTable->addRow($rowData);
        }

        $chart_gender = $lava->ColumnChart('TemplateChartGender');

        $chart_gender->datatable($templateTable);
        $chart_gender = $lava->render('ColumnChart', 'TemplateChartGender', 'template-chart', true);

        return view('admin.report.report_template', compact('chart_month', 'chart_gender'));
    }
}