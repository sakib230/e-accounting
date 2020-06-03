<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Reports_model');
    }

    public function index() {
        redirect('Home');
    }

    public function salesByItem() {
        $this->userRoleAuthentication(REPORT_SALE_BY_ITEM_PAGE);
        $this->data['currentPageCode'] = REPORT_SALE_BY_ITEM_PAGE;
        $this->data['pageHeading'] = 'Sales By Item';

        $advanceFilterCount = (int) $this->input->post('advanceFilterCount', true);
        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $itemArr = array();
        $customerArr = array();
        for ($i = 0; $i < $advanceFilterCount; $i++) {
            $itemCode = $this->input->post('itemCode' . $i, true);
            $customerCode = $this->input->post('customerCode' . $i, true);
            if ($itemCode) {
                $itemArr[] = $itemCode;
            }
            if ($customerCode) {
                $customerArr[] = $customerCode;
            }
        }
        $arr['item'] = $itemArr;
        $arr['customer'] = $customerArr;
        $this->data['saleItemDetails'] = $this->Reports_model->getSalesByItem($arr);

        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/salesByItemReportView';
        $this->loadView($this->data);
    }

    public function getItem() {
        $this->userRoleAuthentication(REPORT_SALE_BY_ITEM_PAGE);
        $this->load->model('Sales_model');
        $results = $this->Sales_model->getSaleItem();
        $response = array();

        foreach ($results as $result) {
            $itemType = 'Product';
            if ($result['item_type'] == SERVICE) {
                $itemType = 'Service';
            }

            $x = array(
                '<span class="td-f-l"><i class="fa fa-tag"></i> <b class="template-green">' . $result['title'] . '</b><br><i class="fa fa-link"></i> <small> ' . $itemType . '</small>',
                $result['item_code'],
                $result['title'] . " " . $itemType
            );
            $response[] = $x;
        }
        echo json_encode(array('data' => $response));
    }

    public function salesByCustomer() {
        $this->userRoleAuthentication(REPORT_SALE_BY_CUSTOMER_PAGE);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = REPORT_SALE_BY_CUSTOMER_PAGE;
        $this->data['pageHeading'] = 'Sales By Customer';

        $advanceFilterCount = (int) $this->input->post('advanceFilterCount', true);
        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $customerArr = array();
        for ($i = 0; $i < $advanceFilterCount; $i++) {
            $customerCode = $this->input->post('customerCode' . $i, true);
            if ($customerCode) {
                $customerArr[] = $customerCode;
            }
        }
        $arr['customer'] = $customerArr;
        $this->data['saleCustomerDetails'] = $this->Reports_model->getSalesByCustomer($arr);

        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/salesByCustomerReportView';
        $this->loadView($this->data);
    }

    public function invoiceDetails() {
        $this->userRoleAuthentication(REPORT_INVOICE_DETAILS_PAGE);
        $this->data['currentPageCode'] = REPORT_INVOICE_DETAILS_PAGE;
        $this->data['pageHeading'] = 'Invoice Details';

        $statusInputArr = $this->input->post('status', true);
        $statusArr = array();
        $statusViewArr = array();
        if ($statusInputArr) {
            foreach ($statusInputArr as $x => $status) {
                $statusViewArr[] = $status;
                if ($status == 'all') {
                    $statusArr[] = PAID;
                    $statusArr[] = UNPAID;
                    $statusArr[] = PARTIALLY_PAID;
                } else if ($status == 'paid') {
                    $statusArr[] = PAID;
                } else if ($status == 'unpaid') {
                    $statusArr[] = UNPAID;
                } else if ($status == 'partially_paid') {
                    $statusArr[] = PARTIALLY_PAID;
                } else if ($status == 'due' || $status == 'overdue') {
                    $statusArr[] = UNPAID;
                    $statusArr[] = PARTIALLY_PAID;
                }
            }
        }
//        print_r($statusArr);
//        exit();
        $advanceFilterCount = (int) $this->input->post('advanceFilterCount', true);
        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $customerArr = array();
        $invoiceDateArr = array();
        $dueDateArr = array();
        $invoiceNoArr = array();
        for ($i = 0; $i < $advanceFilterCount; $i++) {
            $customerCode = $this->input->post('customerCode' . $i, true);
            $invoiceDate = $this->input->post('invoiceDate' . $i, true);
            $dueDate = $this->input->post('dueDate' . $i, true);
            $invoiceNo = $this->input->post('invoiceNo' . $i, true);
            if ($customerCode) {
                $customerArr[] = $customerCode;
            }
            if ($invoiceDate) {
                $invoiceDateArr[] = $invoiceDate;
            }
            if ($dueDate) {
                $dueDateArr[] = $dueDate;
            }
            if ($invoiceNo) {
                $invoiceNoArr[] = $invoiceNo;
            }
        }
        $arr['customer'] = $customerArr;
        $arr['invoiceDate'] = $invoiceDateArr;
        $arr['dueDate'] = $dueDateArr;
        $arr['invoiceCode'] = $invoiceNoArr;
        $arr['status'] = $statusArr;
        $this->data['invoiceDetails'] = $this->Reports_model->getInvoiceDetails($arr);
        $this->data['statusArr'] = $statusViewArr;
        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/invoiceDetailsReportView';
        $this->loadView($this->data);
    }

    public function generalLedger() {
        $this->userRoleAuthentication(REPORT_GENERAL_LEDGER_PAGE);
        $this->data['currentPageCode'] = REPORT_GENERAL_LEDGER_PAGE;
        $this->data['pageHeading'] = 'General Ledger';

        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $this->data['generalLedgers'] = $this->Reports_model->getGeneralLedgerDetails($arr);

        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/generalLedgerReportView';
        $this->loadView($this->data);
    }

    public function journal() {
        $this->userRoleAuthentication(REPORT_JOURNAL_PAGE);
        $this->data['currentPageCode'] = REPORT_JOURNAL_PAGE;
        $this->data['pageHeading'] = 'Journal Report';

        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $this->data['distinctTranGroups'] = $this->Reports_model->getDistinctTranGroups($arr);
        $this->data['journalDetails'] = $this->Reports_model->getJournalDetails($arr);

//        echo "<pre>";
//        print_r($this->data['journalDetails']);
//        exit();

        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/journalReportView';
        $this->loadView($this->data);
    }

    ///////////////////////////////////////////////////////////////////////////////
    public function purchaseByItem() {
        $this->userRoleAuthentication(REPORT_PURCHASE_BY_ITEM_PAGE);
        $this->data['currentPageCode'] = REPORT_PURCHASE_BY_ITEM_PAGE;
        $this->data['pageHeading'] = 'Purchase By Item';

        $advanceFilterCount = (int) $this->input->post('advanceFilterCount', true);
        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $itemArr = array();
        $vendorArr = array();
        for ($i = 0; $i < $advanceFilterCount; $i++) {
            $itemCode = $this->input->post('itemCode' . $i, true);
            $vendorCode = $this->input->post('vendorCode' . $i, true);
            if ($itemCode) {
                $itemArr[] = $itemCode;
            }
            if ($vendorCode) {
                $vendorArr[] = $vendorCode;
            }
        }
        $arr['item'] = $itemArr;
        $arr['vendor'] = $vendorArr;
        $this->data['purchaseItemDetails'] = $this->Reports_model->getPurchaseByItem($arr);

        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/purchaseByItemReportView';
        $this->loadView($this->data);
    }

    public function getPurchaseItem() {
        $this->userRoleAuthentication(REPORT_PURCHASE_BY_ITEM_PAGE);
        $this->load->model('Purchase_model');
        $results = $this->Purchase_model->getPurchaseItem();
        $response = array();

        foreach ($results as $result) {
            $itemType = 'Product';
            if ($result['item_type'] == SERVICE) {
                $itemType = 'Service';
            }

            $x = array(
                '<span class="td-f-l"><i class="fa fa-tag"></i> <b class="template-green">' . $result['title'] . '</b><br><i class="fa fa-link"></i> <small> ' . $itemType . '</small>',
                $result['item_code'],
                $result['title'] . " " . $itemType
            );
            $response[] = $x;
        }
        echo json_encode(array('data' => $response));
    }

    public function purchaseByVendor() {
        $this->userRoleAuthentication(REPORT_PURCHASE_BY_VENDOR_PAGE);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = REPORT_PURCHASE_BY_VENDOR_PAGE;
        $this->data['pageHeading'] = 'Purchase By Vendor';

        $advanceFilterCount = (int) $this->input->post('advanceFilterCount', true);
        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $vendorArr = array();
        for ($i = 0; $i < $advanceFilterCount; $i++) {
            $vendorCode = $this->input->post('vendorCode' . $i, true);
            if ($vendorCode) {
                $vendorArr[] = $vendorCode;
            }
        }
        $arr['vendor'] = $vendorArr;
        $this->data['purchaseVendorDetails'] = $this->Reports_model->getPurchaseByVendor($arr);
//        print_r($this->data['purchaseVendorDetails']);
//        exit();
        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/purchaseByVendorReportView';
        $this->loadView($this->data);
    }

    public function billDetails() {
        $this->userRoleAuthentication(REPORT_BILL_DETAILS_PAGE);
        $this->data['currentPageCode'] = REPORT_BILL_DETAILS_PAGE;
        $this->data['pageHeading'] = 'Bill Details';

        $statusInputArr = $this->input->post('status', true);
        $statusArr = array();
        $statusViewArr = array();
        if ($statusInputArr) {
            foreach ($statusInputArr as $x => $status) {
                $statusViewArr[] = $status;
                if ($status == 'all') {
                    $statusArr[] = PAID;
                    $statusArr[] = UNPAID;
                    $statusArr[] = PARTIALLY_PAID;
                } else if ($status == 'paid') {
                    $statusArr[] = PAID;
                } else if ($status == 'unpaid') {
                    $statusArr[] = UNPAID;
                } else if ($status == 'partially_paid') {
                    $statusArr[] = PARTIALLY_PAID;
                } else if ($status == 'due' || $status == 'overdue') {
                    $statusArr[] = UNPAID;
                    $statusArr[] = PARTIALLY_PAID;
                }
            }
        }

        $advanceFilterCount = (int) $this->input->post('advanceFilterCount', true);
        $arr['fromDate'] = $this->input->post('fromDate', true);
        $arr['toDate'] = $this->input->post('toDate', true);
        if ($arr['fromDate'] == "" || $arr['toDate'] == "") {
            $arr['fromDate'] = date("Y-m-01", strtotime(date('Y-m-d')));
            $arr['toDate'] = date("Y-m-t", strtotime(date('Y-m-d')));
            $this->data['reportDateRange'] = date("F 1, Y", strtotime(date('Y-m-d'))) . ' - ' . date("F t, Y", strtotime(date('Y-m-d'))); // current month
        } else {
            $this->data['reportDateRange'] = date("F d, Y", strtotime($arr['fromDate'])) . ' - ' . date("F d, Y", strtotime($arr['toDate']));
        }

        $vendorArr = array();
        $billDateArr = array();
        $dueDateArr = array();
        $billNoArr = array();
        for ($i = 0; $i < $advanceFilterCount; $i++) {
            $vendorCode = $this->input->post('vendorCode' . $i, true);
            $billDate = $this->input->post('billDate' . $i, true);
            $dueDate = $this->input->post('dueDate' . $i, true);
            $billNo = $this->input->post('billNo' . $i, true);
            if ($vendorCode) {
                $vendorArr[] = $vendorCode;
            }
            if ($billDate) {
                $billDateArr[] = $billDate;
            }
            if ($dueDate) {
                $dueDateArr[] = $dueDate;
            }
            if ($billNo) {
                $billNoArr[] = $billNo;
            }
        }
        $arr['vendor'] = $vendorArr;
        $arr['billDate'] = $billDateArr;
        $arr['dueDate'] = $dueDateArr;
        $arr['billCode'] = $billNoArr;
        $arr['status'] = $statusArr;
        $this->data['billDetails'] = $this->Reports_model->getBillDetails($arr);
        $this->data['statusArr'] = $statusViewArr;
        $this->data['reportFromDate'] = $arr['fromDate'];
        $this->data['reportToDate'] = $arr['toDate'];
        $this->data['pageUrl'] = 'reports/billDetailsReportView';
        $this->loadView($this->data);
    }

}
