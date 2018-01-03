<?php

class Orders extends Controller {

	function __construct() {
		parent::__construct();
	}

	public function index($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

		$this->view->setPage('on', 'orders');
        $this->view->setPage('title', 'Orders');

		if( !empty($id) ){
			$item = $this->model->get($id, array('items'=>true));
            if( empty($item) ) $this->error();

            $this->view->setData('topbar', array(
                'title' => array( 0 =>
                    array( 'text' => '<i class="icon-cube"></i> Orders ('.$item['code'].')' ),
                ),
                'nav' => array(
                    0 => array(
                                    // 'type' => 'link',
                        'icon' => 'icon-remove',
                                    // 'text' => 'Cancel',
                        'url' => URL.'mobile/orders'
                    ),
                )
            ) );

            $this->view->setData('item', $item);
            $render = 'orders/profile/display';
		}
		else{
			$options = array(
				'sort'=>'ord_dateCreate',
				'dir'=>'DESC'
			);
			$options['sale'] = !empty($this->me['sale_code']) ? $this->me['sale_code'] : '';
            $results = $this->model->lists( $options );

            $this->view->setData('topbar', array(
                'title' => array( 0 =>
                    array( 'text' => '<i class="icon-cube"></i> Orders ('.$results['total'].')' ),
                ),
            ) );

            if( $this->format=='json' ){
                $this->view->setData('results', $results);
                $this->view->render('orders/lists/json');
                exit;
            }
            $render = 'orders/lists/display';
		}
		$this->view->render($render);
	}
	public function create($cus=null){
		$this->view->setPage('title', 'Orders - New Order');

		$cus = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $cus;
		if( empty($cus) || empty($this->me) ) $this->error();

		$customer = $this->model->query('customers')->get($cus);
        if( empty($customer) ) $this->error();
        $this->view->setData('customer', $customer);

        $cate = $this->model->query('categories')->getFirst(array('show_sub'=>true));
       	if( empty($cate) ) $this->error();
       	$this->view->setData('cate', $cate);

        $category = $this->model->query('categories')->lists( array('sort'=>'seq', 'dir'=>'ASC', 'not_is_sub'=>true, 'show_sub'=>true) );
        $this->view->setData('category', $category);

        $total_qty = $this->model->summaryItemSaleOrder($this->me['id'], $customer['id']);

        $this->view->setData('topbar', array(
            'title'=>array(
                0 => array(
                    'text' => '<i class="icon-home"></i> '.$customer['name_store']
                ),
                1 => array(
                    'text' => $customer['sub_code']
                )
            ),
            'nav' => array(
                0 => array(
                                    // 'type' => 'link',
                    'icon' => 'icon-cart-plus',
                    'text' => '(<span id="total">'.$total_qty.'</span>)',
                    'url' => URL.'orders/checkout/'.$customer['id']
                ),
            )
        ) );

        $render = 'orders/cart/display';
        $this->view->render($render);
	}
	public function saveSaleOrder(){
		if( empty($_POST) ) $this->error();

		$id = isset($_POST["id"]) ? $_POST["id"] : null;
		if( empty($id) ) $this->error();

		if( !empty($id) ){
			$item = $this->model->query('products')->get($id);
			if( empty($item) ) $this->error();
		}

		$pro_price = !empty($item['pricing']) ? $item['pricing']['frontend'] : $item['pds_price_frontend'];
		$total_pro_price = $pro_price * $_POST["quantity"];

		$order = $this->model->getSaleOrder($this->me['id'], array('customer'=>$_POST["cus_id"], 'not_delete'=>true));

		if( !empty($order) ){
			$id = $order['id'];
			$data['net_price'] = $order['net_price'] + $total_pro_price;
			$this->model->updateSaleOrder($id, $data);
		}
		else{
			$customer = $this->model->query('customers')->get($_POST["cus_id"]);
			$data = array(
				'sale_id'=>$this->me['id'],
				'customer_id'=>$_POST["cus_id"],
				'name_store'=>$customer['name_store'],
				'sub_code'=>$customer['sub_code'],
				'sale_code'=>$this->me['sale_code'],
				'net_price'=>$total_pro_price
			);
			$this->model->insertSaleOrder($data);
			$id = $data['id'];
		}

		if( !empty($id) ){
			$postData = array(
				'sale_orders_id'=>$id,
				'products_id'=>$_POST["id"],
				'products_name'=>$item['pds_name'],
				'quantity'=>$_POST["quantity"],
				'price'=>$pro_price,
				'discount'=>'0.00',
				'prices'=>$total_pro_price
			);
			$_item = $this->model->getItemSaleOrder($id, $_POST["id"]);
			if( !empty($_item) ){
				$postData['id'] = $_item['id'];
				$postData['quantity'] += $_item['quantity'];
				// $postData['price'] = $pro_price;
				$postData['prices'] += $total_pro_price;
			}

			$this->model->setItemSaleOrder($postData);
		}

		$arr['message'] = 'เลือกสินค้าเรียบร้อย';
		// $arr['url'] = 'refresh';
		echo json_encode($arr);
	}
	public function checkout($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($id) || empty($this->me) ) $this->error();

		$this->view->setPage('title', 'Checkout');

		$customer = $this->model->query('customers')->get($id);
		if( empty($customer) ) $this->error();
		$this->view->setData('customer', $customer);

		$order = $this->model->getSaleOrder($this->me['id'], array('customer'=>$id, 'not_delete'=>true));
		$this->view->setData('order', $order);

		if( !empty($order['items']) ){
			$total = $this->model->getSummary($order['items']);
			$this->view->setData('total', $total);
		}

		$this->view->setData('topbar', array(
            'title'=>array(
                0 => array(
                    'text' => '<i class="icon-home"></i> '.$customer['name_store']
                ),
                1 => array(
                    'text' => $customer['sub_code']
                )
            ),
            'nav' => array(
                0 => array(
                                    // 'type' => 'link',
                    'icon' => 'icon-remove',
                    'url' => URL.'orders/create/'.$customer['id'],
                ),
            )
        ) );
		$this->view->render('orders/checkout/display');
	}
	public function updateItemSaleOder($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

		$item = $this->model->getItemSale($id);
		if( empty($item) ) $this->error();

		$order = $this->model->get_saleOrder($item['sale_orders_id']);
		if( empty($order) ) $this->error();

		$product = $this->model->query('products')->get($item['products_id']);
		$pro_price = !empty($product['pricing']) ? $product['pricing']['frontend'] : 0;
		$total_pro_price =  $pro_price * $_POST["quantity"];

		$postData['id'] = $item['id'];
		$postData['quantity'] = $_POST['quantity'];
		$postData['prices'] = $total_pro_price;
		$this->model->setItemSaleOrder($postData);

		$net_price = ($order['net_price'] - $item['prices']) + $total_pro_price;
		$this->model->updateSaleOrder($order['id'], array('net_price'=>$net_price));

		$_order = $this->model->get_saleOrder( $item['sale_orders_id'] );
		$total = $this->model->getSummary( $_order['items'] );

		echo json_encode($total);
	}
	public function del_sale_item($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;

		$item = $this->model->getItemSale($id);
		if( empty($item) ) $this->error();

		$order = $this->model->get_saleOrder($item['sale_orders_id']);
		if( empty($order) ) $this->error();

		$this->model->updateSaleOrder($order['id'], array('net_price'=>$order['net_price'] - $item['prices']));
		$this->model->unsetItemSaleOrder($id);

		$checkItem = $this->model->checkItemSaleOrder($order['id']);
		if( empty($checkItem) ) $this->model->deleteSaleOrder($order['id']);

		$total = array(
			'total'=>0,
			'discount'=>0,
			'amount'=>0
		);
		if( !empty($checkItem) ){
			$_order = $this->model->get_saleOrder( $item['sale_orders_id'] );
			$total = $this->model->getSummary( $_order['items'] );
		}

		// $arr['message'] = 'ยกเลิกรายการเรียบร้อยแล้ว';
		echo json_encode($total);
	}
	public function confirmOrder($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
		if( empty($id) || empty($this->me) ) $this->error();

		$order = $this->model->get_saleOrder($id, array('items'=>true));
		if( empty($order) ) $this->error();

		if( empty($_POST['term_of_payment']) ){
			$arr['error']['term_of_payment'] = 'กรุณาเลือกประเภทการจ่ายเงินสด';
		}
		if( empty($arr['error']) ){
			$postData = array(
				'site_id'=>0,
				'create_user_id'=>$this->me['id'],
				'create_user_type'=>'Sale',
				'ord_customer_id'=>$order['customer_id'],
				'ord_sale_code'=>$order['sale_code'],
				'ord_dateCreate'=>date("Y-m-d"),
				'ord_type_commission'=>'sales',
				'user_name'=>$order['name_store'],
				'user_code'=>$order['sub_code'],
				'ord_process'=>0,
				'term_of_payment'=>$_POST["term_of_payment"],
				'ord_status'=>'A',
				'order_note'=>$_POST["order_note"],
				'ord_net_price'=>$order['net_price'],
				'ord_discount_extra'=>'0.00',
				'ord_tax'=>'0.00'
			);
			$this->model->insert($postData);
			$_id = $postData['id'];
			$order_code = 'B'.sprintf("%06d",$_id);
			$total_prices = 0;
			if( !empty($_id) ){
				foreach ($order['items'] as $key => $value) {
					$product = $this->model->query('products')->get($value['products_id']);

					/* FIX PRICE ITEMS */
					$prices = $value['price'] * $value['quantity'];

					$data = array(
						'site_id'=>0,
						'ord_id'=>$_id,
						'ord_code'=>$order_code,
						'itm_type'=>'d',
						'itm_id'=>$value['products_id'],
						'itm_name'=>$value['products_name'],
						'itm_code'=>$product['pds_code'],
						'itm_qty'=>$value['quantity'],
						'itm_unit'=>'1',
						'itm_price'=>$value['price'],
						'itm_discount'=>$value['discount'],
						'itm_prices'=>$prices,
						'itm_status'=>'A',
						'itm_remark'=>null
					);
					$this->model->setItem($data);
					$total_prices += $prices;
				}

				$this->model->update($_id, array('ord_code'=>$order_code, 'ord_net_price'=>$total_prices));
				$this->model->updateSaleOrder($order['id'], array('deleted_at'=>date("c")));

				$arr['message'] = 'ยืนยันการสั่งซื้อเรียบร้อย';
				$arr['url'] = URL.'orders';
			}
			else{
				$arr['message'] = 'Error ! ไม่สามารถยืนยันการสั่งซื้อได้';
			}
		}

		echo json_encode($arr);
	}
	public function updateOrder($id=null){
		if( empty($_POST) ) $this->error();

		$id = isset($_POST["id"]) ? $_POST["id"] : $id;
		if( !empty($id) ){
			$item = $this->model->get($id);
			if( empty($item) ) $this->error();
		}

		try{
			$form = new Form();
			$form 	->post('order_note');
			$form->submit();
			$postData = $form->fetch();

			if( empty($_POST['term_of_payment']) ){
				$arr['error']['term_of_payment'] = 'กรุณาเลือกประเภทการจ่ายเงินสด';
			}
			else{
				$postData['term_of_payment'] = $_POST['term_of_payment'];
			}

			if( empty($arr['error']) ){
				$postData['ord_process'] = 0;
				$this->model->update($id, $postData);

				$arr['message'] = 'ยืนยันออร์เดอร์เรียบร้อย';
				$arr['url'] = URL.'orders';
			}

		} catch (Exception $e) {
			$arr['error'] = $this->_getError($e->getMessage());
		}
		echo json_encode($arr);
	}

	#SETUP FOR JSON
	public function setsubMenu($id=null){
		$id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $id;
        $results = $this->model->query('categories')->listsSubCategories( $id );

        $this->view->setData('results', $results);
        $this->view->render('orders/cart/sections/sub-category');
	}
	public function setProducts($cate=null, $cus=null){
		$cate = isset($_REQUEST["cate"]) ? $_REQUEST["cate"] : $cate;
		$cus = isset($_REQUEST["cus"]) ? $_REQUEST["cus"] : $cus;
		$key = isset($_REQUEST["key"]) ? $_REQUEST["key"] : null;

		$item = $this->model->query('categories')->get($cate);
		if( empty($item) ) $this->error();

		$customer = $this->model->query('customers')->get($cus);
		if( empty($customer) ) $this->error();

		$this->view->item = $item;
		$this->view->key = $key;
		$this->view->customer = $customer;
		$this->view->render('orders/cart/sections/lists');
	}
}
