<?php
namespace App\Models;

trait UpdateColumnWithClauseTrait
{
	/**
	 * query update column with case then
	 * @param  array $data    
	 * @param  string $field   field name
	 * @param  array  &$params 
	 * @return array          
	 */
  	public function updateColumnWithClause($data, $field, &$params = []) 
    {
        $params['sql'] = '';
        foreach ($data as $value) {
            $params['sql'] .= " WHEN id = ? THEN ? ";
            $params['param'][] = $value['id']; 
            $params['param'][] = $value[$field]; 
        }
        $params['sql'] .= ' END';

        return $params;
    }

     public function updateMultiRecord($dataPrepareUpdate, array $fields, array $ids)
    {
        $sql = 'UPDATE `'.$this->table.'` SET '.$fields[0].' = CASE ';
        $params = ['sql' => '', 'param' => []];

        $sql .= $this->updateColumnWithClause($dataPrepareUpdate, $fields[0], $params)['sql'];
        unset($fields[0]);
  
        foreach ($fields as $field) {
        	 $sql .= ' , '.$field.' = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, $field, $params)['sql'];
        }
  
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';

        \DB::update($sql, $params['param']);
    }
}