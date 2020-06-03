<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contacts extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Contacts_model');
    }

    public function index() {
        redirect('Home');
    }

    public function customer() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        $this->data['currentPageCode'] = CUSTOMER_PAGE;
        $this->data['pageHeading'] = 'Customer';
        $this->data['pageUrl'] = 'contacts/customerView';
        $this->loadView($this->data);
    }

    public function getCustomerList() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        //$arr['isActive'] = '';
        $results = $this->Contacts_model->getCustomer();
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
                $result['contact_code'],
                '<span class="td-f-l">' . $result['contact_name'] . '</span>',
                $result['mobile_no'],
                ($result['email']) ? '<span class="td-f-l">' . $result['email'] . '</span>' : '<small><i>N/A</i></small>',
                ($result['company_name']) ? '<span class="td-f-l">' . $result['company_name'] . '</span>' : '<small><i>N/A</i></small>',
                $status
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }

    public function newCustomer() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully created a customer";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate customer";
            $this->data['msgFlag'] = "danger";
        }

        $arr['isActive'] = 1;
        $this->data['currentPageCode'] = CUSTOMER_PAGE;
        $this->data['pageHeading'] = 'New Customer';
        $this->data['pageUrl'] = 'contacts/addCustomerView';
        $this->loadView($this->data);
    }

    public function customerDuplicateCheck() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        $arr['customerId'] = $this->getInputValue('customerId', 'POST', 'string', NULL, 0);
        $arr['fullName'] = $this->getInputValue('fullName', 'POST', 'string', 200, 1); //trim($this->input->post('fullName', true));
        $arr['mobileNo'] = $this->getInputValue('mobileNo', 'POST', 'mobileNo', NULL, 1); //trim($this->input->post('mobileNo', true));
        $arr['addEditFlag'] = $this->getInputValue('addEditFlag', 'POST', 'string', NULL, 1); //trim($this->input->post('addEditFlag', true));
        $result = $this->Contacts_model->customerDuplicateCheck($arr);
        echo $result;
    }

    public function addCustomer() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        $contactInfo['contact_code'] = CONTACT_CODE . getCode(CONTACT_CODE);
        $contactInfo['contact_account'] = ACCOUNT_RECEIVABLE;
        $contactInfo['contact_name'] = $this->getInputValue('fullName', 'POST', 'string', 200, 1);
        $contactInfo['mobile_no'] = $this->getInputValue('mobile', 'POST', 'mobile', NULL, 1);
        $contactInfo['email'] = $this->getInputValue('email', 'POST', 'email', NULL, 0);
        $contactInfo['address'] = $this->getInputValue('address', 'POST', 'string', NULL, 0);
        $contactInfo['company_name'] = $this->getInputValue('companyName', 'POST', 'string', 200, 0);
        $contactInfo['opening_balance'] = $this->getInputValue('openingBalance', 'POST', 'float', NULL, 0);
        $contactInfo['contact_type'] = CUSTOMER;
        $contactInfo['created_by'] = $this->user;
        $contactInfo['created_dt_tm'] = $this->dateTime;
        $contactInfo['updated_by'] = $this->user;
        $contactInfo['updated_dt_tm'] = $this->dateTime;

        $result = $this->Contacts_model->addCustomer($contactInfo);
        redirect('Contacts/newCustomer?response=' . $result);
    }

    public function showCustomerDetails() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        $customerId = $this->getInputValue('customerId', 'GET', 'string', NULL, 1);
        $this->data['customerDetail'] = $this->Contacts_model->getCustomer(array('customerId' => $customerId));
        if ($this->data['customerDetail']) {
            $this->data['currentPageCode'] = CUSTOMER_PAGE;
            $this->data['pageHeading'] = 'Customer Details';
            $this->data['pageUrl'] = 'contacts/customerDetailView';
            $this->loadView($this->data);
        } else {
            redirect('Contacts/customer');
        }
    }

    public function showEditCustomer() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully edited a customer";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate customer";
            $this->data['msgFlag'] = "danger";
        }
        $customerId = $this->getInputValue('customerId', 'GET', 'string', NULL, 1);
        $this->data['customerDetail'] = $this->Contacts_model->getCustomer(array('customerId' => $customerId));
        if ($this->data['customerDetail']) {
            $this->data['currentPageCode'] = CUSTOMER_PAGE;
            $this->data['pageHeading'] = 'Edit Customer';
            $this->data['pageUrl'] = 'contacts/editCustomerView';
            $this->loadView($this->data);
        } else {
            redirect('Contacts/customer');
        }
    }

    public function editCustomer() {
        $this->userRoleAuthentication(CUSTOMER_PAGE);
        $contactInfo['contact_code'] = $this->getInputValue('customerId', 'POST', 'string', NULL, 1);
        $contactInfo['contact_account'] = ACCOUNT_RECEIVABLE;
        $contactInfo['contact_name'] = $this->getInputValue('fullName', 'POST', 'string', 200, 1);
        $contactInfo['mobile_no'] = $this->getInputValue('mobile', 'POST', 'mobile', NULL, 1);
        $contactInfo['email'] = $this->getInputValue('email', 'POST', 'email', NULL, 0);
        $contactInfo['address'] = $this->getInputValue('address', 'POST', 'string', NULL, 0);
        $contactInfo['company_name'] = $this->getInputValue('companyName', 'POST', 'string', 200, 0);
        $contactInfo['opening_balance'] = $this->getInputValue('openingBalance', 'POST', 'float', NULL, 0);
        $contactInfo['contact_type'] = CUSTOMER;
        $contactInfo['updated_by'] = $this->user;
        $contactInfo['updated_dt_tm'] = $this->dateTime;

        $customerDetail = $this->Contacts_model->getCustomer(array('customerId' => $contactInfo['contact_code']));
        if ($customerDetail) {
            $result = $this->Contacts_model->editCustomer($contactInfo);
            redirect('Contacts/showEditCustomer?customerId=' . $contactInfo['contact_code'] . '&response=' . $result);
        } else {
            redirect('Contacts/customer');
        }
    }
    
    public function vendor() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        $this->data['currentPageCode'] = VENDOR_PAGE;
        $this->data['pageHeading'] = 'Vendor';
        $this->data['pageUrl'] = 'contacts/vendorView';
        $this->loadView($this->data);
    }
    
    public function getVendorList() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        //$arr['isActive'] = '';
        $results = $this->Contacts_model->getVendor();
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
                $result['contact_code'],
                '<span class="td-f-l">' . $result['contact_name'] . '</span>',
                $result['mobile_no'],
                ($result['email']) ? '<span class="td-f-l">' . $result['email'] . '</span>' : '<small><i>N/A</i></small>',
                ($result['company_name']) ? '<span class="td-f-l">' . $result['company_name'] . '</span>' : '<small><i>N/A</i></small>',
                $status
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }
    
    public function newVendor() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully created a vendor";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate vendor";
            $this->data['msgFlag'] = "danger";
        }

        $arr['isActive'] = 1;
        $this->data['currentPageCode'] = VENDOR_PAGE;
        $this->data['pageHeading'] = 'New Vendor';
        $this->data['pageUrl'] = 'contacts/addVendorView';
        $this->loadView($this->data);
    }

    public function vendorDuplicateCheck() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        $arr['vendorId'] = $this->getInputValue('vendorId', 'POST', 'string', NULL, 0);
        $arr['fullName'] = $this->getInputValue('fullName', 'POST', 'string', 200, 1);
        $arr['mobileNo'] = $this->getInputValue('mobileNo', 'POST', 'mobileNo', NULL, 1);
        $arr['addEditFlag'] = $this->getInputValue('addEditFlag', 'POST', 'string', NULL, 1);
        $result = $this->Contacts_model->vendorDuplicateCheck($arr);
        echo $result;
    }

    public function addVendor() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        $contactInfo['contact_code'] = CONTACT_CODE . getCode(CONTACT_CODE);
        $contactInfo['contact_account'] = ACCOUNT_RECEIVABLE;
        $contactInfo['contact_name'] = $this->getInputValue('fullName', 'POST', 'string', 200, 1);
        $contactInfo['mobile_no'] = $this->getInputValue('mobile', 'POST', 'mobile', NULL, 1);
        $contactInfo['email'] = $this->getInputValue('email', 'POST', 'email', NULL, 0);
        $contactInfo['address'] = $this->getInputValue('address', 'POST', 'string', NULL, 0);
        $contactInfo['company_name'] = $this->getInputValue('companyName', 'POST', 'string', 200, 0);
        $contactInfo['opening_balance'] = $this->getInputValue('openingBalance', 'POST', 'float', NULL, 0);
        $contactInfo['contact_type'] = VENDOR;
        $contactInfo['created_by'] = $this->user;
        $contactInfo['created_dt_tm'] = $this->dateTime;
        $contactInfo['updated_by'] = $this->user;
        $contactInfo['updated_dt_tm'] = $this->dateTime;

        $result = $this->Contacts_model->addVendor($contactInfo);
        redirect('Contacts/newVendor?response=' . $result);
    }
    
    public function showVendorDetails() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        $vendorId = $this->getInputValue('vendorId', 'GET', 'string', NULL, 1);
        $this->data['vendorDetail'] = $this->Contacts_model->getVendor(array('vendorId' => $vendorId));
        if ($this->data['vendorDetail']) {
            $this->data['currentPageCode'] = VENDOR_PAGE;
            $this->data['pageHeading'] = 'Vendor Details';
            $this->data['pageUrl'] = 'contacts/vendorDetailView';
            $this->loadView($this->data);
        } else {
            redirect('Contacts/vendor');
        }
    }

    public function showEditVendor() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully edited a vendor";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate vendor";
            $this->data['msgFlag'] = "danger";
        }
        $vendorId = $this->getInputValue('vendorId', 'GET', 'string', NULL, 1);
        $this->data['vendorDetail'] = $this->Contacts_model->getVendor(array('vendorId' => $vendorId));
        if ($this->data['vendorDetail']) {
            $this->data['currentPageCode'] = VENDOR_PAGE;
            $this->data['pageHeading'] = 'Edit Vendor';
            $this->data['pageUrl'] = 'contacts/editVendorView';
            $this->loadView($this->data);
        } else {
            redirect('Contacts/vendor');
        }
    }

    public function editVendor() {
        $this->userRoleAuthentication(VENDOR_PAGE);
        $contactInfo['contact_code'] = $this->getInputValue('vendorId', 'POST', 'string', NULL, 1);
        $contactInfo['contact_account'] = ACCOUNT_RECEIVABLE;
        $contactInfo['contact_name'] = $this->getInputValue('fullName', 'POST', 'string', 200, 1);
        $contactInfo['mobile_no'] = $this->getInputValue('mobile', 'POST', 'mobile', NULL, 1);
        $contactInfo['email'] = $this->getInputValue('email', 'POST', 'email', NULL, 0);
        $contactInfo['address'] = $this->getInputValue('address', 'POST', 'string', NULL, 0);
        $contactInfo['company_name'] = $this->getInputValue('companyName', 'POST', 'string', 200, 0);
        $contactInfo['opening_balance'] = $this->getInputValue('openingBalance', 'POST', 'float', NULL, 0);
        $contactInfo['contact_type'] = VENDOR;
        $contactInfo['updated_by'] = $this->user;
        $contactInfo['updated_dt_tm'] = $this->dateTime;

        $vendorDetail = $this->Contacts_model->getVendor(array('vendorId' => $contactInfo['contact_code']));
        if ($vendorDetail) {
            $result = $this->Contacts_model->editVendor($contactInfo);
            redirect('Contacts/showEditVendor?vendorId=' . $contactInfo['contact_code'] . '&response=' . $result);
        } else {
            redirect('Contacts/vendor');
        }
    }
}
