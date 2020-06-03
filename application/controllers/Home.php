<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Home_model');
    }

    public function index() {
        $this->data['currentPageCode'] = '';
        $this->data['pageHeading'] = 'Dashboard';

        $this->data['countValues'] = $this->Home_model->getDashboardCountValues();
        $this->data['receivable'] = $this->Home_model->getReceivableValues();  // credit amount --> paid , debit amount --> total amount
        $this->data['payable'] = $this->Home_model->getPayableValues(); 
        // ------- sales and purchase graph --------//
        $saleAmountArr = array();
        $monthArr = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
        // ---------- sales
        $salesValue = $this->Home_model->getSalesValueMonth();
        for ($i = 0; $i < 12; $i++) {
            $saleAmount = 0;
            foreach ($salesValue as $sales) {
                if ($monthArr[$i] == $sales['month']) {
                    $saleAmount = floatval($sales['amount']);
                    break;
                }
            }
            $saleAmountArr[] = $saleAmount;
        }

        $arr['name'] = 'Sales';
        $arr['data'] = $saleAmountArr;
        $jsonArr[] = $arr;
        //------- purchase
        $purchaseValue = $this->Home_model->getPurchaseValueMonth();
        for ($i = 0; $i < 12; $i++) {
            $purchaseAmount = 0;
            foreach ($purchaseValue as $purchase) {
                if ($monthArr[$i] == $purchase['month']) {
                    $purchaseAmount = floatval($purchase['amount']);
                    break;
                }
            }
            $purchaseAmountArr[] = $purchaseAmount;
        }
        $arr['name'] = 'Purchase';
        $arr['data'] = $purchaseAmountArr;
        $jsonArr[] = $arr;

        $this->data['salePurchaseGraphValue'] = json_encode($jsonArr);
//        echo "<pre>";
//        print_r($this->data['receivable']);
//        exit();

        $this->data['pageUrl'] = 'home/homeView';
        $this->loadView($this->data);
    }

}
