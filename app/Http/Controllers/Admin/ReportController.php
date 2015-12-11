<?php

namespace App\Http\Controllers\Admin;

use Khill\Lavacharts\Lavacharts;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;


class ReportController extends Controller
{

    public function index(Request $request)
    {
        $lava = new Lavacharts; // See note below for Laravel

        $finances = $lava->DataTable();

        $finances->addDateColumn('Year')
                 ->addNumberColumn('Sales')
                 ->addNumberColumn('Expenses')
                 ->setDateTimeFormat('Y')
                 ->addRow(array('2004', 1000, 400))
                 ->addRow(array('2005', 1170, 460))
                 ->addRow(array('2006', 660, 1120))
                 ->addRow(array('2007', 1030, 54));

        $columnchart = $lava->ColumnChart('Finances')
                            ->setOptions(array(
                              'datatable' => $finances,
                              'title' => 'Company Performance',
                              'titleTextStyle' => $lava->TextStyle(array(
                                'color' => '#eb6b2c',
                                'fontSize' => 14
                              ))
                            ));
        return $finances; die();

        return view('admin.report.index', compact('columnchart'));
    }
}