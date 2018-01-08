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

            if( empty($data[$date]['total']) ){
                $data[$date]['total'] = 0;
            }
            if( empty($data[$date]['amount']) ){
                $data[$date]['amount'] = 0;
            }
            if( empty($data[$date]['payment']) ){
                $data[$date]['payment'] = 0;
            }
            if( empty($data[$date]['comission']) ){
                $data[$date]['comission'] = 0;
            }
            $data[$date]['amount'] += $value['ord_net_price'];
            $data[$date]['total']++;
            
            $payment = $this->query('orders')->listsPayment($value['id']);
            if( !empty($payment) ){
                foreach ($payment as $key => $value) {
                    $data[$date]['payment'] += $value['amount'];
                    $data[$date]['comission'] += $value['comission_amount'];
                }
            }
    	}
        return $data;
    }
}