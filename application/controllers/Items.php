<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Items_model');
    }

    public function index() {
        redirect('Home');
    }

    public function item() {
        $this->userRoleAuthentication(ITEM_PAGE);
        $this->data['currentPageCode'] = ITEM_PAGE;
        $this->data['pageHeading'] = 'Item';
        $this->data['pageUrl'] = 'Items/itemView';
        $this->loadView($this->data);
    }

    public function getItemList() {
        $this->userRoleAuthentication(ITEM_PAGE);
        //$arr['isActive'] = '';
        $results = $this->Items_model->getItem();
        $response = array();
        $i = 1;
        foreach ($results as $result) {
            $status = "";
            if ($result['is_active'] == 1) {
                $status = 'Active';
            } else if ($result['is_active'] == 0) {
                $status = 'Inactive';
            }
            $x = array($i,
                $result['item_code'],
                '<span class="td-f-l">' . $result['title'] . '</span>',
                $status
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }

    public function newItem() {
        $this->userRoleAuthentication(ITEM_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully created an item";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate item";
            $this->data['msgFlag'] = "danger";
        }

        $arr['isActive'] = 1;
        $this->load->model('Accounts_model');
        $this->data['taxes'] = $this->Accounts_model->getTax();
//        var_dump($this->data['taxes']);
//        die();
        $this->data['currentPageCode'] = ITEM_PAGE;
        $this->data['pageHeading'] = 'New Item';
        $this->data['pageUrl'] = 'items/addItemView';
        $this->loadView($this->data);
    }

    public function itemDuplicateCheck() {
        $this->userRoleAuthentication(ITEM_PAGE);
        $arr['itemCode'] = $this->getInputValue('itemCode', 'POST', 'string', NULL, 0);
        $arr['itemName'] = $this->getInputValue('itemName', 'POST', 'string', 200, 1);
        $arr['itemType'] = $this->getInputValue('itemType', 'POST', 'string', 30, 1);
        $arr['addEditFlag'] = $this->getInputValue('addEditFlag', 'POST', 'string', NULL, 1);
        $result = $this->Items_model->itemDuplicateCheck($arr);
        echo $result;
    }

    public function addItem() {
        $this->userRoleAuthentication(ITEM_PAGE);
        $itemInfo['item_code'] = ITEM_CODE . getCode(ITEM_CODE);
        $itemInfo['item_type'] = $this->getInputValue('itemType', 'POST', 'string', 10, 1);
        $itemInfo['title'] = $this->getInputValue('itemName', 'POST', 'string', 200, 1);
        $itemInfo['unit_name'] = $this->getInputValue('unitName', 'POST', 'string', 30, 1);
        $itemInfo['sale_rate'] = $this->getInputValue('saleRate', 'POST', 'float', NULL, 0);
        $itemInfo['sale_account'] = $this->getInputValue('saleAccount', 'POST', 'string', 30, 0);
        $itemInfo['sale_description'] = $this->getInputValue('saleDescription', 'POST', 'string', NULL, 0);
        $itemInfo['sale_tax'] = $this->getInputValue('saleTax', 'POST', 'string', 30, 0);
        $itemInfo['purchase_rate'] = $this->getInputValue('purchaseRate', 'POST', 'float', NULL, 0);
        $itemInfo['purchase_account'] = $this->getInputValue('purchaseAccount', 'POST', 'string', 30, 0);
        $itemInfo['purchase_description'] = $this->getInputValue('purchaseDescription', 'POST', 'string', NULL, 0);
        $itemInfo['purchase_tax'] = $this->getInputValue('purchaseTax', 'POST', 'string', 30, 0);
        $itemInfo['created_by'] = $this->user;
        $itemInfo['created_dt_tm'] = $this->dateTime;
        $itemInfo['updated_by'] = $this->user;
        $itemInfo['updated_dt_tm'] = $this->dateTime;
        $result = $this->Items_model->addItem($itemInfo);
        redirect('Items/newItem?response=' . $result);
    }

    public function showItemDetails() {
        $this->userRoleAuthentication(ITEM_PAGE);
        $itemCode = $this->getInputValue('itemCode', 'GET', 'string', NULL, 1);
        $this->data['itemDetail'] = $this->Items_model->getItem(array('itemCode' => $itemCode));
        if ($this->data['itemDetail']) {
            $this->data['currentPageCode'] = ITEM_PAGE;
            $this->data['pageHeading'] = 'Item Details';
            $this->data['pageUrl'] = 'items/itemDetailView';
            $this->loadView($this->data);
        } else {
            redirect('Contacts/customer');
        }
    }

    public function showEditItem() {
        $this->userRoleAuthentication(ITEM_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully edited an item";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate item";
            $this->data['msgFlag'] = "danger";
        }
        $itemCode = $this->getInputValue('itemCode', 'GET', 'string', NULL, 1);
        $this->data['itemDetail'] = $this->Items_model->getItem(array('itemCode' => $itemCode));
        if ($this->data['itemDetail']) {
            $this->load->model('Accounts_model');
            $this->data['taxes'] = $this->Accounts_model->getTax();
            $this->data['currentPageCode'] = ITEM_PAGE;
            $this->data['pageHeading'] = 'Edit Item';
            $this->data['pageUrl'] = 'items/editItemView';
            $this->loadView($this->data);
        } else {
            redirect('Items/item');
        }
    }

    public function editItem() {
        $this->userRoleAuthentication(ITEM_PAGE);
        $itemInfo['item_code'] = $this->getInputValue('itemCode', 'POST', 'string', NULL, 1);
        $itemInfo['item_type'] = $this->getInputValue('itemType', 'POST', 'string', 10, 1);
        $itemInfo['title'] = $this->getInputValue('itemName', 'POST', 'string', 200, 1);
        $itemInfo['unit_name'] = $this->getInputValue('unitName', 'POST', 'string', 30, 1);
        $itemInfo['sale_rate'] = $this->getInputValue('saleRate', 'POST', 'float', NULL, 0);
        $itemInfo['sale_account'] = $this->getInputValue('saleAccount', 'POST', 'string', 30, 0);
        $itemInfo['sale_description'] = $this->getInputValue('saleDescription', 'POST', 'string', NULL, 0);
        $itemInfo['sale_tax'] = $this->getInputValue('saleTax', 'POST', 'string', 30, 0);
        $itemInfo['purchase_rate'] = $this->getInputValue('purchaseRate', 'POST', 'float', NULL, 0);
        $itemInfo['purchase_account'] = $this->getInputValue('purchaseAccount', 'POST', 'string', 30, 0);
        $itemInfo['purchase_description'] = $this->getInputValue('purchaseDescription', 'POST', 'string', NULL, 0);
        $itemInfo['purchase_tax'] = $this->getInputValue('purchaseTax', 'POST', 'string', 30, 0);
        $itemInfo['updated_by'] = $this->user;
        $itemInfo['updated_dt_tm'] = $this->dateTime;
//        echo "<pre>";
//        print_r($itemInfo);
//        die();
        $itemDetail = $this->Items_model->getItem(array('itemCode' => $itemInfo['item_code']));
        if ($itemDetail) {
            $result = $this->Items_model->editItem($itemInfo);
            redirect('Items/showEditItem?itemCode=' . $itemInfo['item_code'] . '&response=' . $result);
        } else {
            redirect('Items/item');
        }
    }

}
