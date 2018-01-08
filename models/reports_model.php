<?php

class Reports_Model extends Model{
	public function __construct() {
        parent::__construct();
    }

    public function summaryOrder( $options=array() ){
    	$data = array();

    	$w = '';
    	$w_arr = array();

    	if( !empty($options['sale']) ){
    		$w .= !empty($w) ? " AND " : "";
    		$w .= "ord_sale_code=:sale";
    		$w_arr[':sale'] = $options['sale']; 
    	}

    	if( !empty($options["month"]) && !empty($options["year"]) ){
    		$w .= !empty($w) ? " AND " : "";
    		$w .= "ord_dateCreate LIKE :month";
    		$w_arr[":month"] = "{$options["year"]}-{$options["month"]}%";
    	}

    	if( !empty($options["process"]) ){
    		$w .= !empty($w) ? " AND " : "";
    		$w .= "ord_process=:process";
    		$w_arr[":process"] = $options["process"];
    	}

    	$w = !empty($w) ? "WHERE {$w}" : "";

    	$results = $this->db->select("SELECT * FROM orders {$w}", $w_arr);
    	foreach ($results as $key => $value) {
    		$date = date("Y-m-d", strtotime($value['ord_dateCreate']));
    	}
    }
}