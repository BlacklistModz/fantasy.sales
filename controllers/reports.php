<?php 
class Reports extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function orders(){
        $month = isset($_REQUEST["month"]) ? sprintf("%02d", $_REQUEST["month"]) : date("m");
        $year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : date("Y");

        $this->view->setData('topbar', array(
            'title' => array( 0 =>
                array( 'text' => '<i class="icon-money"></i> Sale Report' ),
            ),
            'nav' => array(
                0 => array(
                                    // 'type' => 'link',
                    'icon' => 'icon-remove',
                                    // 'text' => 'Cancel',
                    'url' => URL.'orders'
                ),
            )
        ) );

        $options = array(
            'sale'=>$this->me['sale_code'],
            'month'=>$month,
            'year'=>$year,
            'process'=>3
        );

        $this->view->setData('month', $month);
        $this->view->setData('year', $year);

        $results = $this->model->summaryOrder( $options );
        $this->view->setData('results', $results);
        $this->view->render('reports/orders');
    }

    public function due(){
        $month = isset($_REQUEST["month"]) ? sprintf("%02d", $_REQUEST["month"]) : date("m");
        $year = isset($_REQUEST["year"]) ? $_REQUEST["year"] : date("Y");

        $this->view->setData('topbar', array(
            'title' => array( 0 =>
                array( 'text' => '<i class="icon-handshake-o"></i> Sale Due' ),
            ),
            'nav' => array(
                0 => array(
                                    // 'type' => 'link',
                    'icon' => 'icon-remove',
                                    // 'text' => 'Cancel',
                    'url' => URL.'orders'
                ),
            )
        ) );

        $this->view->setData('month', $month);
        $this->view->setData('year', $year);

        $options = array(
            // 'sale'=>$this->me['sale_code'],
            'month'=>$month,
            'year'=>$year,
            // 'process'=>3,
            'not_paid'=>true
        );

        $results = $this->model->orderNotPaid( $options );
        $this->view->setData('results', $results);
        $this->view->render('reports/due');
    }
}