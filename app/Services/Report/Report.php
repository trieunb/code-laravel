<?php
namespace App\Services\Report;

use Khill\Lavacharts\Lavacharts;

class Report
{
	private $model;
	private $sqlClause;
	private $groupBy;
	private $orderBy;
	private $whereClause;
	private $with;
	private $query;
	private $reportNotAdmin;
	
	public function __construct($model, $sqlClause, $groupBy, $orderBy = [], $whereClause = null)
	{
		$this->model = $model;
		$this->sqlClause = $sqlClause;
		$this->groupBy = $groupBy;
		$this->orderBy = $orderBy;
		$this->whereClause = $whereClause;
	}

	public function setReportNotdAdmin($report)
	{
		$this->reportNotAdmin = $report;

		return $this;
	}

	private function groupByPrepareReport()
	{
		$query = $this->model->select(\DB::raw('COUNT(*) as count'), \DB::raw($this->sqlClause))
		    ->groupBy($this->groupBy); 
		
		if ($this->whereClause != null && ! is_array($this->whereClause)) {
			throw new Exception('Clause query isnt array');
		}

		if ($this->whereClause != null && count($this->whereClause) == 1) {
			$query = $query->where($this->whereClause[0]['field'], $this->whereClause[0]['operator'], $this->whereClause[0]['value']);
		} elseif ($this->whereClause != null && count($this->whereClause) > 1) {
			foreach ($this->whereClause as $where) {
				$query = $query->where($where['field'], $where['operator'], $where['value']);
			}
		}

		if ( $this->reportNotAdmin != null) {
			$query = $query->whereDoesntHave('roles');
		}

		return $query;
	}	


	private function orderByPreprareReport()
	{

		if (count($this->orderBy) == 1 ) {
			foreach ($this->orderBy as $field => $order) {
				return $this->groupByPrepareReport()->orderBy($field, $order);
			}
		} elseif (count($this->orderBy) > 1) {
			$this->query = $this->groupByPrepareReport();

			foreach ($this->orderBy as $field => $order) 
				$this->query = $this->query->orderBy($field, $order);
							
			return $this->query;
		}

		return $this->groupByPrepareReport();
	}	

	public function getDataPreprareRender()
	{
		return $this->orderByPreprareReport()->get();
	}

	public function prepareRender($name, $response, $stringColumn = '', $numberColumn = '', $options = [])
	{
		$datas = $this->getDataPreprareRender();
		
		if (count($datas) == 0) return null;

		$response = $this->transferResponse($datas, $name, $response);

		$lavaChart = new Lavacharts;
		$reason = $lavaChart->DataTable()
                ->addStringColumn($stringColumn)
                ->addNumberColumn($numberColumn);   

        foreach ($response as $name => $value) {
            $reason->addRow([$name, $value]);
        }
        
        $options = array_merge($options, ['datatable' => $reason]);
        $pieChart = $lavaChart->PieChart('Chart')
            ->setOptions($options);

        return $lavaChart;
	}

	private function transferResponse($datas, $name, $response = null) {
		if ($response != null) {
			foreach ($datas as $data) {
				if (array_key_exists($data->{$name}, $response)) {
	                $response[$data->{$name}] = (int)$data->count;
	            }	
			}
		} else {
			foreach ($datas as $data) {
				if ($data->{$name} != null) {
					$response[$data->{$name}] = (int)$data->count;
				} else $response['Unknown'] = (int)$data->count;
			}
		}

		return $response;
	}
}