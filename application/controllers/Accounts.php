<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Accounts_model');
    }

    public function index() {
        redirect('Home');
    }

    public function chartAccount() {
        $this->userRoleAuthentication(CHART_ACCOUNT_PAGE);
        $this->data['currentPageCode'] = CHART_ACCOUNT_PAGE;
        $this->data['pageHeading'] = 'Chart of Account';
        $this->data['pageUrl'] = 'accounts/chartAccountView';
        $this->loadView($this->data);
    }

    public function getChartAccountList() {
        $this->userRoleAuthentication(CHART_ACCOUNT_PAGE);
        //$arr['isActive'] = '';
        $results = $this->Accounts_model->getChartAccount();
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
                $result['account_code'],
                '<span class="td-f-l">' . $result['account_title'] . '</span>',
                $status
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }
    
    public function tax() {
        $this->userRoleAuthentication(TAX_PAGE);
        $this->data['currentPageCode'] = TAX_PAGE;
        $this->data['pageHeading'] = 'Tax';
        $this->data['pageUrl'] = 'accounts/taxView';
        $this->loadView($this->data);
    }
    
    public function getTax() {
        $this->userRoleAuthentication(TAX_PAGE);
        //$arr['isActive'] = '';
        $results = $this->Accounts_model->getTax();
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
                $result['tax_code'],
                '<span class="td-f-l">' . $result['title'] . '</span>',
                $result['rate'],
                $status
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }
    
    public function newTax() {
        $this->userRoleAuthentication(TAX_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully created a tax";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate tax";
            $this->data['msgFlag'] = "danger";
        }

        $arr['isActive'] = 1;
        $this->data['currentPageCode'] = TAX_PAGE;
        $this->data['pageHeading'] = 'New Tax';
        $this->data['pageUrl'] = 'accounts/addTaxView';
        $this->loadView($this->data);
    }
    
    public function taxDuplicateCheck() {
        $this->userRoleAuthentication(TAX_PAGE);
        $arr['taxCode'] = $this->getInputValue('taxCode', 'POST', 'string', NULL, 0);
        $arr['name'] = $this->getInputValue('name', 'POST', 'string', 200, 1);
        $arr['addEditFlag'] = $this->getInputValue('addEditFlag', 'POST', 'string', NULL, 1);
        $result = $this->Accounts_model->taxDuplicateCheck($arr);
        echo $result;
    }
    
    public function addTax() {
        $this->userRoleAuthentication(TAX_PAGE);
        $taxInfo['tax_code'] = TAX_CODE . getCode(TAX_CODE);
        $taxInfo['tax_account'] = TAX_PAYABLE;
        $taxInfo['title'] = $this->getInputValue('name', 'POST', 'string', 200, 1);
        $taxInfo['rate'] = $this->getInputValue('rate', 'POST', 'float', NULL, 1);
        $taxInfo['created_by'] = $this->user;
        $taxInfo['created_dt_tm'] = $this->dateTime;
        $taxInfo['updated_by'] = $this->user;
        $taxInfo['updated_dt_tm'] = $this->dateTime;

        $result = $this->Accounts_model->addTax($taxInfo);
        redirect('Accounts/newTax?response=' . $result);
    }
    
    public function showTaxDetails() {
        $this->userRoleAuthentication(TAX_PAGE);
        $taxCode = $this->getInputValue('taxCode', 'GET', 'string', NULL, 1);
        $this->data['taxDetail'] = $this->Accounts_model->getTax(array('taxCode' => $taxCode));
        if ($this->data['taxDetail']) {
            $this->data['currentPageCode'] = TAX_PAGE;
            $this->data['pageHeading'] = 'Tax Details';
            $this->data['pageUrl'] = 'accounts/taxDetailView';
            $this->loadView($this->data);
        } else {
            redirect('Accounts/tax');
        }
    }
    

    public function showEditTax() {
        $this->userRoleAuthentication(TAX_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully edited a tax";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate tax";
            $this->data['msgFlag'] = "danger";
        }
        $taxCode = $this->getInputValue('taxCode', 'GET', 'string', NULL, 1);
        $this->data['taxDetail'] = $this->Accounts_model->getTax(array('taxCode' => $taxCode));
        if ($this->data['taxDetail']) {
            $this->data['currentPageCode'] = TAX_PAGE;
            $this->data['pageHeading'] = 'Edit Tax';
            $this->data['pageUrl'] = 'accounts/editTaxView';
            $this->loadView($this->data);
        } else {
            redirect('Accounts/tax');
        }
    }
    
    public function editTax() {
        $this->userRoleAuthentication(TAX_PAGE);
        $taxInfo['tax_code'] = $this->getInputValue('taxCode', 'POST', 'string', NULL, 1);
        $taxInfo['tax_account'] = TAX_PAYABLE;
        $taxInfo['title'] = $this->getInputValue('name', 'POST', 'string', 200, 1);
        $taxInfo['rate'] = $this->getInputValue('rate', 'POST', 'float', NULL, 1);
        $taxInfo['updated_by'] = $this->user;
        $taxInfo['updated_dt_tm'] = $this->dateTime;
        
        $taxDetail = $this->Accounts_model->getTax(array('taxCode' => $taxInfo['tax_code']));
        if ($taxDetail) {
            $result = $this->Accounts_model->editTax($taxInfo);
            redirect('Accounts/showEditTax?taxCode=' . $taxInfo['tax_code'] . '&response=' . $result);
        } else {
            redirect('Accounts/tax');
        }
    } 
}
