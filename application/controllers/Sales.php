<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sales extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Sales_model');
    }

    public function index() {
        redirect('Home');
    }

    public function invoice() {
        $this->userRoleAuthentication(INVOICE);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = INVOICE;
        $this->data['pageHeading'] = 'Invoice';
        $this->data['pageUrl'] = 'sales/invoiceListView';
        $this->loadView($this->data);
    }

    public function getInvoiceList() {
        $this->userRoleAuthentication(INVOICE);
        $arr['status'] = ($this->input->get('status', true)) ? $this->input->get('status') : NULL;
        $arr['customer'] = ($this->input->get('customer', true)) ? $this->input->get('customer') : NULL;

        $results = $this->Sales_model->getInvoiceDetails($arr);
        $response = array();
        $i = 1;
        foreach ($results as $result) {
            $status = "";
            $balanceDue = number_format(($result['total'] - $result['paid_amount']), 2);
            if ($result['total'] == $result['paid_amount']) {
                $status = '<span class="template-green">Paid</span>';
            } else {
                $todayDate = date_create(date('Y-m-d'));
                $dueDate = date_create($result['due_date']);
                $interval = date_diff($todayDate, $dueDate);
                $dueDatesCount = (int) $interval->format('%R%a');

                if ($dueDatesCount < 0) {
                    $status = '<small class="text-danger">Overdue By ' . (-1) * $dueDatesCount . ' Day(s)</small>';
                } else {
                    $status = '<small class="text-info">Due in ' . $dueDatesCount . ' Day(s)</small>';
                }
            }

            $x = array($i,
                $result['invoice_date'],
                '<span class="template-green"><b>' . $result['invoice_code'] . '</b></span>',
                $result['display_reference_no'],
                '<span class="td-f-l">' . $result['contact_name'] . '<br><small><b>' . $result['customer'] . '</b></small></span>',
                $status,
                $result['due_date'],
                $result['total'],
                $balanceDue,
                $result['invoice_code']
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }

    public function newInvoice() {
        $this->userRoleAuthentication(INVOICE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "The invoice has been created";
            $this->data['msgFlag'] = "success";
        }
        $this->data['currentPageCode'] = INVOICE;
        $this->data['pageHeading'] = 'New Invoice';
        $this->data['pageUrl'] = 'sales/newInvoiceView';
        $this->loadView($this->data);
    }

    public function getCustomer() {
        $this->userRoleAuthentication(NULL, array(INVOICE, PAYMENT_RECEIVED, REPORT_SALE_BY_ITEM_PAGE));
        $results = $this->Sales_model->getCustomer();
        $response = array();

        foreach ($results as $result) {

            $x = array(
                '<span class="td-f-l"><i class="fa fa-link"></i> <b class="template-green">' . $result['contact_code'] . '</b><br><i class="fa fa-user"></i> ' . $result['contact_name'] . '<br><i class="fa fa-phone"></i> <i><small>' . $result['mobile_no'] . '</small></i></span>',
                $result['contact_code'],
                $result['contact_name'],
                $result['mobile_no']
            );
            $response[] = $x;
        }
        echo json_encode(array('data' => $response));
    }

    public function getItem() {
        $this->userRoleAuthentication(INVOICE);
        $results = $this->Sales_model->getSaleItem();
        $response = array();

        foreach ($results as $result) {
            $x = array(
                '<span class="td-f-l"><i class="fa fa-tag"></i> <b class="template-green">' . $result['title'] . '</b><br><i class="fa fa-money"></i> <small>BDT ' . $result['sale_rate'] . ' Per ' . $result['unit_name'] . '</small>',
                $result['item_code'],
                $result['title'],
                $result['unit_name'],
                $result['sale_rate'],
                $result['sale_tax'],
                $result['tax_title'],
                $result['tax_rate']
            );
            $response[] = $x;
        }
        echo json_encode(array('data' => $response));
    }

    public function addNewInvoice() {
        $this->userRoleAuthentication(INVOICE);
        $invoiceFlag = (int) $this->input->post('invoiceFlag', true);
        if ($invoiceFlag == 1) {
            $this->addNewOnlyInvoice();
        } elseif ($invoiceFlag == 2) {
            $this->addNewInvoiceAndPayment();
        } else {
            redirect('Sales/newInvoice');
        }
    }

    public function addNewOnlyInvoice() {
        $this->userRoleAuthentication(INVOICE);
        $saleInvoice['customer'] = $this->input->post('customerCode', true);
        $saleInvoice['invoice_date'] = $this->input->post('invoiceDate', true);
        $saleInvoice['due_date'] = $this->input->post('dueDate', true);
        if ($saleInvoice['customer'] && $saleInvoice['invoice_date'] && $saleInvoice['due_date']) {
            $saleInvoice['display_reference_no'] = ($this->input->post('referenceNo', true)) ? $this->input->post('referenceNo', true) : NULL;
            $saleInvoice['customer_notes'] = ($this->input->post('customerNotes', true)) ? $this->input->post('customerNotes', true) : NULL;
            $saleInvoice['terms_condition'] = ($this->input->post('termsCondition', true)) ? $this->input->post('termsCondition', true) : NULL;
            $saleInvoice['adjustment'] = ($this->input->post('adjust', true)) ? (float) $this->input->post('adjust', true) : NULL;
            $saleInvoice['invoice_code'] = INVOICE_CODE . getCode(INVOICE_CODE);
            $saleInvoice['invoice_tran_ref'] = reference_no();
            $itemCount = (int) $this->input->post('applyItemCount', true);
            $itemArr = array();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $itemArr[] = $itemCode;
                }
            }

            if ($itemArr) {
                $itemSaleAccountsInfo = array();
                $itemSaleAccounts = $this->Sales_model->getSaleItemAccount($itemArr);
                foreach ($itemSaleAccounts as $itemSaleAccount) {
                    $itemSaleAccountsInfo[] = $itemSaleAccount['sale_account'];
                }
            } else {
                redirect('Sales/newInvoice');
            }
            $saleInvoiceItemInsertArr = array();
            $transactionInsertArr = array();
            $saleAccountCount = 0;
            $total = 0;
            $transactionGroupId = reference_no();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $amount = 0;
                    //-------------  sale invoice per item ------------//
                    $saleInvoiceItem['invoice_code'] = $saleInvoice['invoice_code'];
                    $saleInvoiceItem['reference_no'] = reference_no();
                    $saleInvoiceItem['item'] = $itemCode;
                    $saleInvoiceItem['quantity'] = (float) $this->input->post('itemQuantity' . $i, true);
                    $saleInvoiceItem['rate'] = (float) $this->input->post('itemRate' . $i, true);
                    $saleInvoiceItem['unit'] = trim($this->input->post('itemUnitName' . $i, true));
                    $saleInvoiceItem['tax_rate'] = (float) $this->input->post('itemTaxRate' . $i, true);
                    $saleInvoiceItem['tax_code'] = $this->input->post('itemTaxCode' . $i, true);
                    if ($saleInvoiceItem['quantity'] <= 0 || $saleInvoiceItem['rate'] <= 0) {
                        redirect('Sales/newInvoice');
                    }

                    $amount = $saleInvoiceItem['rate'] * $saleInvoiceItem['quantity'];
                    if ($saleInvoiceItem['tax_rate'] > 0) {
                        $amount = $amount + (($amount * $saleInvoiceItem['tax_rate']) / 100);
                    }

                    $saleInvoiceItem['amount'] = $amount;
                    $saleInvoiceItem['sale_account'] = $itemSaleAccountsInfo[$saleAccountCount];
                    $saleInvoiceItem['created_by'] = $this->user;
                    $saleInvoiceItem['created_dt_tm'] = $this->dateTime;
                    $saleInvoiceItem['updated_by'] = $this->user;
                    $saleInvoiceItem['updated_dt_tm'] = $this->dateTime;
                    $saleAccountCount++;
                    $saleInvoiceItemInsertArr[] = $saleInvoiceItem;

                    //------------ per item transaction ------------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['invoice'] = $saleInvoice['invoice_code'];
                    $transaction['invoice_item_ref_no'] = $saleInvoiceItem['reference_no'];
                    $transaction['reference_no'] = $saleInvoice['invoice_tran_ref'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $saleInvoice['customer'];
                    $transaction['contact_type'] = CUSTOMER;
                    $transaction['account'] = $saleInvoiceItem['sale_account'];  // for purchase it will be purchase account and it will be debit
                    $transaction['credit_amount'] = $amount;
                    $transaction['debit_amount'] = '0.00';
                    $transaction['transaction_type'] = CREDIT;
                    $transaction['transaction_for'] = INVOICE_CREATE_FOR;  // new correction
                    $transaction['tarn_dt_tm'] = $saleInvoice['invoice_date'] . ' 00:00:00';
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactionInsertArr[] = $transaction;

                    $total = $total + $amount;
                }
            }
            $saleInvoice['sub_total'] = $total;
            $saleInvoice['total'] = (float) $total;
            if ($saleInvoice['adjustment'] != NULL) {
                $saleInvoice['total'] = (float) ($total + $saleInvoice['adjustment']);
            }
            if ($saleInvoice['total'] < 0) {
                redirect('Sales/newInvoice');
            }
            $saleInvoice['status'] = UNPAID;
            $saleInvoice['created_by'] = $this->user;
            $saleInvoice['created_dt_tm'] = $this->dateTime;
            $saleInvoice['updated_by'] = $this->user;
            $saleInvoice['updated_dt_tm'] = $this->dateTime;

            //------------ aacount receivable Debit of total amount -------------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['invoice'] = $saleInvoice['invoice_code'];
            $transaction['invoice_item_ref_no'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = NULL;
            $transaction['contact_type'] = NULL;
            $transaction['account'] = ACCOUNT_RECEIVABLE;  // for purchase it will be ACCOUNT PAYABLE and CREDI
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $saleInvoice['total'];
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = INVOICE_CREATE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $saleInvoice['invoice_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactionInsertArr[] = $transaction;

            if ($saleInvoice && $saleInvoiceItemInsertArr && $transactionInsertArr) {
                $this->Sales_model->addNewInvoice($saleInvoice, $saleInvoiceItemInsertArr, $transactionInsertArr);
                redirect('Sales/newInvoice?response=1');
            } else {
                redirect('Sales/newInvoice');
            }
        } else {
            redirect('Sales/newInvoice');
        }
    }

    public function addNewInvoiceAndPayment() {   // not optimize... should change in future.....
        $this->userRoleAuthentication(INVOICE);
        $saleInvoice['customer'] = $this->input->post('customerCode', true);
        $saleInvoice['invoice_date'] = $this->input->post('invoiceDate', true);
        $saleInvoice['due_date'] = $this->input->post('dueDate', true);
        if ($saleInvoice['customer'] && $saleInvoice['invoice_date'] && $saleInvoice['due_date']) {
            $saleInvoice['display_reference_no'] = ($this->input->post('referenceNo', true)) ? $this->input->post('referenceNo', true) : NULL;
            $saleInvoice['customer_notes'] = ($this->input->post('customerNotes', true)) ? $this->input->post('customerNotes', true) : NULL;
            $saleInvoice['terms_condition'] = ($this->input->post('termsCondition', true)) ? $this->input->post('termsCondition', true) : NULL;
            $saleInvoice['adjustment'] = ($this->input->post('adjust', true)) ? (float) $this->input->post('adjust', true) : NULL;
            $saleInvoice['invoice_code'] = INVOICE_CODE . getCode(INVOICE_CODE);
            $saleInvoice['invoice_tran_ref'] = reference_no();
            $itemCount = (int) $this->input->post('applyItemCount', true);
            $itemArr = array();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $itemArr[] = $itemCode;
                }
            }

            if ($itemArr) {
                $itemSaleAccountsInfo = array();
                $itemSaleAccounts = $this->Sales_model->getSaleItemAccount($itemArr);
                foreach ($itemSaleAccounts as $itemSaleAccount) {
                    $itemSaleAccountsInfo[] = $itemSaleAccount['sale_account'];
                }
            } else {
                redirect('Sales/newInvoice');
            }
            $saleInvoiceItemInsertArr = array();
            $transactionInsertArr = array();
            $saleAccountCount = 0;
            $total = 0;
            $transactionGroupId = reference_no();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $amount = 0;
                    //-------------  sale invoice per item ------------//
                    $saleInvoiceItem['invoice_code'] = $saleInvoice['invoice_code'];
                    $saleInvoiceItem['reference_no'] = reference_no();
                    $saleInvoiceItem['item'] = $itemCode;
                    $saleInvoiceItem['quantity'] = (float) $this->input->post('itemQuantity' . $i, true);
                    $saleInvoiceItem['rate'] = (float) $this->input->post('itemRate' . $i, true);
                    $saleInvoiceItem['unit'] = trim($this->input->post('itemUnitName' . $i, true));
                    $saleInvoiceItem['tax_rate'] = (float) $this->input->post('itemTaxRate' . $i, true);
                    $saleInvoiceItem['tax_code'] = $this->input->post('itemTaxCode' . $i, true);
                    if ($saleInvoiceItem['quantity'] <= 0 || $saleInvoiceItem['rate'] <= 0) {
                        redirect('Sales/newInvoice');
                    }

                    $amount = $saleInvoiceItem['rate'] * $saleInvoiceItem['quantity'];
                    if ($saleInvoiceItem['tax_rate'] > 0) {
                        $amount = $amount + (($amount * $saleInvoiceItem['tax_rate']) / 100);
                    }

                    $saleInvoiceItem['amount'] = $amount;
                    $saleInvoiceItem['sale_account'] = $itemSaleAccountsInfo[$saleAccountCount];
                    $saleInvoiceItem['created_by'] = $this->user;
                    $saleInvoiceItem['created_dt_tm'] = $this->dateTime;
                    $saleInvoiceItem['updated_by'] = $this->user;
                    $saleInvoiceItem['updated_dt_tm'] = $this->dateTime;
                    $saleAccountCount++;
                    $saleInvoiceItemInsertArr[] = $saleInvoiceItem;

                    //------------ per item transaction ------------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['invoice'] = $saleInvoice['invoice_code'];
                    $transaction['invoice_item_ref_no'] = $saleInvoiceItem['reference_no'];
                    $transaction['reference_no'] = $saleInvoice['invoice_tran_ref'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $saleInvoice['customer'];
                    $transaction['contact_type'] = CUSTOMER;
                    $transaction['account'] = $saleInvoiceItem['sale_account'];
                    $transaction['credit_amount'] = $amount;
                    $transaction['debit_amount'] = '0.00';
                    $transaction['transaction_type'] = CREDIT;  // for puchase it will be DEBIT
                    $transaction['transaction_for'] = INVOICE_CREATE_FOR;  // new correction
                    $transaction['tarn_dt_tm'] = $saleInvoice['invoice_date'] . ' 00:00:00';
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactionInsertArr[] = $transaction;

                    $total = $total + $amount;
                }
            }
            $saleInvoice['sub_total'] = $total;
            $saleInvoice['total'] = (float) $total;
            if ($saleInvoice['adjustment'] != NULL) {
                $saleInvoice['total'] = (float) ($total + $saleInvoice['adjustment']);
            }
            if ($saleInvoice['total'] < 0) {
                redirect('Sales/newInvoice');
            }
            $saleInvoice['status'] = UNPAID;
            $saleInvoice['created_by'] = $this->user;
            $saleInvoice['created_dt_tm'] = $this->dateTime;
            $saleInvoice['updated_by'] = $this->user;
            $saleInvoice['updated_dt_tm'] = $this->dateTime;

            //------------ account receivable Debit of total amount -------------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['invoice'] = $saleInvoice['invoice_code'];
            $transaction['invoice_item_ref_no'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = NULL;
            $transaction['contact_type'] = NULL;
            $transaction['account'] = ACCOUNT_RECEIVABLE;  // for purchase it will be ACCOUNT PAYABLE and CREDI
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $saleInvoice['total'];
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = INVOICE_CREATE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $saleInvoice['invoice_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactionInsertArr[] = $transaction;

            if ($saleInvoice && $saleInvoiceItemInsertArr && $transactionInsertArr) {
                $this->Sales_model->addNewInvoice($saleInvoice, $saleInvoiceItemInsertArr, $transactionInsertArr);

                //--------------------------- payment ----------------------------------//
                $transactonArr = array();
                $invoiceCode = $saleInvoice['invoice_code'];
                $paymentAmount = (float) $saleInvoice['total'];
                $arr['invoiceCode'] = $invoiceCode;
                $invoiceInfo = $this->Sales_model->getInvoiceDetailsForPayment($arr);

                $dbPaymentTranRef = $invoiceInfo[0]['payment_tran_ref'];
                //--------- invoice table update -----------------//
                $invoiceAmount = $invoiceInfo[0]['total'];  // total means Invoice total amount
                $status = PARTIALLY_PAID;
                if ($paymentAmount > $invoiceAmount) {  // sum of previous paid amonut and current paid amount can not be grater than total amount of invoice
                    redirect('Sales/showInvoicePaymentDetails?invoice=' . $invoiceCode);
                } else if ($paymentAmount == $invoiceAmount) {
                    $status = PAID;
                }
                $saleInvoiceArr['payment_tran_ref'] = reference_no();
                $saleInvoiceArr['paid_amount'] = $paymentAmount;
                $saleInvoiceArr['status'] = $status;
                $saleInvoiceArr['updated_by'] = $this->user;
                $saleInvoiceArr['updated_dt_tm'] = $this->dateTime;

                //--------- contact table update (used_balanced and total balance) -------//
                $newTotalBalance = $invoiceInfo[0]['total_balance'] - $invoiceInfo[0]['payment_receive_amount'];  // minus for previous payment 
                $newUsedBalance = $invoiceInfo[0]['used_balance'] - $invoiceInfo[0]['paid_amount'] + $paymentAmount;
                $excessAmount = 0;
                if ($newTotalBalance < $newUsedBalance) {
                    $excessAmount = $newUsedBalance - $newTotalBalance;
                }

                $contactArr['total_balance'] = $excessAmount + $newTotalBalance;
                $contactArr['used_balance'] = $newUsedBalance;
                $contactArr['updated_by'] = $this->user;
                $contactArr['updated_dt_tm'] = $this->dateTime;

                // --- if invoice date is greater than today, then Payment Receive and Invoice Payment will be that invoice date
                $todayDate = date_create(date('Y-m-d'));
                $newInvoiceDate = date_create($saleInvoice['invoice_date']);
                $interval = date_diff($todayDate, $newInvoiceDate);
                $datesCount = (int) $interval->format('%R%a');
                $transactionDate = $this->dateTime;
                if ($datesCount > 0) {
                    $transactionDate = $saleInvoice['invoice_date'] . ' 00:00:00';
                }
                //---------------//

                $paymentReceive = array();
                if ($excessAmount > 0) {
                    $paymentReceiveTranDtTm = $transactionDate;
                    if ($invoiceInfo[0]['payment_date']) {
                        $paymentReceiveTranDtTm = $invoiceInfo[0]['payment_date'] . ' 00:00:00';
                    }
                    // due to excessAmount is greater than 0, this amount has made a Payment Receive
                    $paymentReceive['customer'] = $invoiceInfo[0]['customer'];
                    $paymentReceive['amount'] = $excessAmount;
                    $paymentReceive['payment_date'] = $saleInvoice['invoice_date'];
                    $paymentReceive['payment_mode'] = 'cash_pay_mode';
                    $paymentReceive['deposit_to'] = UNDEPOSITED_FUNDS;
                    $paymentReceive['invoice'] = $invoiceCode;
                    $paymentReceive['payment_receive_code'] = PAYMENT_RECEIVED_CODE . getCode(PAYMENT_RECEIVED_CODE);
                    $paymentReceive['created_by'] = $this->user;
                    $paymentReceive['created_dt_tm'] = $this->dateTime;
                    $paymentReceive['updated_by'] = $this->user;
                    $paymentReceive['updated_dt_tm'] = $this->dateTime;

                    //--------- transaction table insert -------------------//
                    //  Undeposit Funds  debit  -------//

                    $transactionGroupId = reference_no();
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['invoice'] = $invoiceCode;
                    $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $invoiceInfo[0]['customer'];
                    $transaction['contact_type'] = CUSTOMER;
                    $transaction['account'] = UNDEPOSITED_FUNDS;
                    $transaction['credit_amount'] = '0.00';
                    $transaction['debit_amount'] = $excessAmount;
                    $transaction['transaction_type'] = DEBIT;
                    $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
                    $transaction['reference_no'] = NULL;
                    $transaction['tarn_dt_tm'] = $paymentReceiveTranDtTm;
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactonArr[] = $transaction;

                    // ----------- Unearned Revenue credit  -------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['invoice'] = $invoiceCode;
                    $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $invoiceInfo[0]['customer'];
                    $transaction['contact_type'] = CUSTOMER;
                    $transaction['account'] = UNEARNED_REVENUE;
                    $transaction['credit_amount'] = $excessAmount;
                    $transaction['debit_amount'] = '0.00';
                    $transaction['transaction_type'] = CREDIT;
                    $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
                    $transaction['reference_no'] = NULL;
                    $transaction['tarn_dt_tm'] = $paymentReceiveTranDtTm;
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactonArr[] = $transaction;
                }

                //----------- transaction for invoice payment ----------//
                // Unearned Revenue Debit of paymentAmount
                $transactionGroupId = reference_no();
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['invoice'] = $invoiceCode;
                $transaction['payment_receive'] = NULL;
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $invoiceInfo[0]['customer'];
                $transaction['contact_type'] = CUSTOMER;
                $transaction['account'] = UNEARNED_REVENUE;
                $transaction['credit_amount'] = '0.00';
                $transaction['debit_amount'] = $paymentAmount;
                $transaction['transaction_type'] = DEBIT;
                $transaction['transaction_for'] = INVOICE_PAYMENT_FOR;  // new correction
                $transaction['reference_no'] = $saleInvoiceArr['payment_tran_ref'];
                $transaction['tarn_dt_tm'] = $transactionDate;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;

                // Account Receivable Credit of payment amount
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['invoice'] = $invoiceCode;
                $transaction['payment_receive'] = NULL;
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $invoiceInfo[0]['customer'];
                $transaction['contact_type'] = CUSTOMER;
                $transaction['account'] = ACCOUNT_RECEIVABLE;
                $transaction['credit_amount'] = $paymentAmount;
                $transaction['debit_amount'] = '0.00';
                $transaction['transaction_type'] = CREDIT;
                $transaction['transaction_for'] = INVOICE_PAYMENT_FOR;  // new correction
                $transaction['reference_no'] = $saleInvoiceArr['payment_tran_ref'];
                $transaction['tarn_dt_tm'] = $transactionDate;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;

                $this->Sales_model->makeInvoicePayment($saleInvoiceArr, $contactArr, $paymentReceive, $transactonArr, $invoiceCode, $dbPaymentTranRef, $invoiceInfo[0]['customer'], $invoiceInfo[0]['payment_receive_code']);
                redirect('Sales/newInvoice?response=1');
            } else {
                redirect('Sales/newInvoice');
            }
        } else {
            redirect('Sales/newInvoice');
        }
    }

    public function checkInvoicePaymentWhenCreate() {
        $this->load->model('Contacts_model');

        $paymentAmount = (float) trim($this->input->post('invoiceAmount', true));
        $customer = trim($this->input->post('customer', true));
        if ($paymentAmount > 0) {
            $arr['customerId'] = $customer;
            $customerInfo = $this->Contacts_model->getCustomer($arr);
            $newUsedBalance = $customerInfo[0]['used_balance'] + $paymentAmount;
            $excessAmount = 0;
            if ($customerInfo[0]['total_balance'] < $newUsedBalance) {
                $excessAmount = $newUsedBalance - $customerInfo[0]['total_balance'];
            }
            if ($excessAmount) {
                echo '2|' . number_format($excessAmount, 2);
            } else {
                echo 1;
            }
        } else {
            echo 3;
        }
    }

    public function showInvoiceDetails() {
        $this->userRoleAuthentication(INVOICE);
        $invoiceCode = $this->input->get('invoice', true);
        if ($invoiceCode) {
            $arr['invoiceCode'] = $invoiceCode;
            $this->data['invoiceInfo'] = $this->Sales_model->getInvoiceDetails($arr);
            $this->data['invoiceItemDetails'] = $this->Sales_model->getInvoiceItemDetails($arr);
            if ($this->data['invoiceInfo'] && $this->data['invoiceItemDetails']) {
                $this->data['currentPageCode'] = INVOICE;
                $this->data['pageHeading'] = 'Invoice';
                $this->data['pageUrl'] = 'sales/invoiceDetailsView';
                $this->loadView($this->data);
            } else {
                redirect('Sales/invoice');
            }
        } else {
            redirect('Sales/invoice');
        }
    }

    public function showEditInvoice() {
        $this->userRoleAuthentication(INVOICE);
        $invoiceCode = $this->input->get('invoice', true);
        if ($invoiceCode) {
            $arr['invoiceCode'] = $invoiceCode;


            $response = (int) $this->input->get('response', true);
            $this->data['msgFlag'] = "";
            if ($response == 1) {
                $this->data['msg'] = "Invoice information has been updated";
                $this->data['msgFlag'] = "success";
            } else if ($response == 2) {
                $this->data['msg'] = "The payment entered is more than the total amount due for this invoice";
                $this->data['msgFlag'] = "danger";
            }

            $this->data['invoiceInfo'] = $this->Sales_model->getInvoiceDetails($arr);
            $this->data['invoiceItemDetails'] = $this->Sales_model->getInvoiceItemDetails($arr);
            if ($this->data['invoiceInfo'] && $this->data['invoiceItemDetails']) {
                $this->data['currentPageCode'] = INVOICE;
                $this->data['pageHeading'] = 'Edit Invoice <small>(' . $invoiceCode . ')</small>';
                $this->data['pageUrl'] = 'sales/editInvoiceView';

                $this->loadView($this->data);
            } else {
                redirect('Sales/invoice');
            }
        } else {
            redirect('Sales/invoice');
        }
    }

    public function editInvoice() {
        $this->userRoleAuthentication(INVOICE);
        $invoiceCode = $this->input->post('invoiceCode', true);
        $saleInvoice['customer'] = $this->input->post('customerCode', true);
        $saleInvoice['invoice_date'] = $this->input->post('invoiceDate', true);
        $saleInvoice['due_date'] = $this->input->post('dueDate', true);
        $arr['invoiceCode'] = $invoiceCode;
        $invoiceInfo = $this->Sales_model->getInvoiceDetails($arr);
        $invoiceItemDetails = $this->Sales_model->getInvoiceItemDetails($arr);
        if ($saleInvoice['customer'] && $saleInvoice['invoice_date'] && $saleInvoice['due_date'] && $invoiceInfo && $invoiceItemDetails) {
            $saleInvoice['display_reference_no'] = ($this->input->post('referenceNo', true)) ? $this->input->post('referenceNo', true) : NULL;
            $saleInvoice['customer_notes'] = ($this->input->post('customerNotes', true)) ? $this->input->post('customerNotes', true) : NULL;
            $saleInvoice['terms_condition'] = ($this->input->post('termsCondition', true)) ? $this->input->post('termsCondition', true) : NULL;
            $saleInvoice['adjustment'] = ($this->input->post('adjust', true)) ? (float) $this->input->post('adjust', true) : NULL;
            $saleInvoice['invoice_code'] = $invoiceCode;
            $itemCount = (int) $this->input->post('applyItemCount', true);
            $itemArr = array();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $itemArr[] = $itemCode;
                }
            }

            if ($itemArr) {
                $itemSaleAccountsInfo = array();
                $itemSaleAccounts = $this->Sales_model->getSaleItemAccount($itemArr);
                foreach ($itemSaleAccounts as $itemSaleAccount) {
                    $itemSaleAccountsInfo[] = $itemSaleAccount['sale_account'];
                }
            } else {
                redirect('Sales/showInvoiceDetails?invoice=' . $invoiceCode);
            }
            $saleInvoiceItemInsertArr = array();
            $transactionInsertArr = array();
            $saleAccountCount = 0;
            $total = 0;
            $transactionGroupId = reference_no();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $amount = 0;
                    //-------------  sale invoice per item ------------//
                    $saleInvoiceItem['invoice_code'] = $saleInvoice['invoice_code'];
                    $saleInvoiceItem['reference_no'] = reference_no();
                    $saleInvoiceItem['item'] = $itemCode;
                    $saleInvoiceItem['quantity'] = (float) $this->input->post('itemQuantity' . $i, true);
                    $saleInvoiceItem['rate'] = (float) $this->input->post('itemRate' . $i, true);
                    $saleInvoiceItem['unit'] = trim($this->input->post('itemUnitName' . $i, true));
                    $saleInvoiceItem['tax_rate'] = (float) $this->input->post('itemTaxRate' . $i, true);
                    $saleInvoiceItem['tax_code'] = $this->input->post('itemTaxCode' . $i, true);
                    if ($saleInvoiceItem['quantity'] <= 0 || $saleInvoiceItem['rate'] <= 0) {
                        redirect('Sales/showInvoiceDetails?invoice=' . $invoiceCode);
                    }

                    $amount = $saleInvoiceItem['rate'] * $saleInvoiceItem['quantity'];
                    if ($saleInvoiceItem['tax_rate'] > 0) {
                        $amount = $amount + (($amount * $saleInvoiceItem['tax_rate']) / 100);
                    }

                    $saleInvoiceItem['amount'] = $amount;
                    $saleInvoiceItem['sale_account'] = $itemSaleAccountsInfo[$saleAccountCount];
                    $saleInvoiceItem['created_by'] = $this->user;
                    $saleInvoiceItem['created_dt_tm'] = $this->dateTime;
                    $saleInvoiceItem['updated_by'] = $this->user;
                    $saleInvoiceItem['updated_dt_tm'] = $this->dateTime;
                    $saleAccountCount++;
                    $saleInvoiceItemInsertArr[] = $saleInvoiceItem;

                    //------------ per item transaction ------------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['invoice'] = $saleInvoice['invoice_code'];
                    $transaction['invoice_item_ref_no'] = $saleInvoiceItem['reference_no'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $saleInvoice['customer'];
                    $transaction['contact_type'] = CUSTOMER;
                    $transaction['account'] = $saleInvoiceItem['sale_account'];
                    $transaction['credit_amount'] = $amount;
                    $transaction['debit_amount'] = '0.00';
                    $transaction['transaction_type'] = CREDIT;
                    $transaction['transaction_for'] = INVOICE_CREATE_FOR;  // new correction
                    $transaction['tarn_dt_tm'] = $saleInvoice['invoice_date'] . ' 00:00:00';
                    $transaction['reference_no'] = $invoiceInfo[0]['invoice_tran_ref'];
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactionInsertArr[] = $transaction;

                    $total = $total + $amount;
                }
            }
            $saleInvoice['sub_total'] = $total;
            $saleInvoice['total'] = (float) $total;
            if ($saleInvoice['adjustment'] != NULL) {
                $saleInvoice['total'] = (float) ($total + $saleInvoice['adjustment']);
            }

            if ($saleInvoice['total'] < 0) {
                redirect('Sales/showEditInvoice?invoice=' . $saleInvoice['invoice_code']);
            }

            if ($invoiceInfo[0]['paid_amount'] > $saleInvoice['total']) {
                redirect('Sales/showEditInvoice?response=2&invoice=' . $saleInvoice['invoice_code']);
            }
            //$saleInvoice['status'] = UNPAID;
            $saleInvoice['updated_by'] = $this->user;
            $saleInvoice['updated_dt_tm'] = $this->dateTime;

            //------------ aacount receivable Debit of total amount -------------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['invoice'] = $saleInvoice['invoice_code'];
            $transaction['invoice_item_ref_no'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = NULL;
            $transaction['contact_type'] = NULL;
            $transaction['account'] = ACCOUNT_RECEIVABLE;
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $saleInvoice['total'];
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = INVOICE_CREATE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $saleInvoice['invoice_date'] . ' 00:00:00';
            $transaction['reference_no'] = $invoiceInfo[0]['invoice_tran_ref'];
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactionInsertArr[] = $transaction;

            if ($saleInvoice && $saleInvoiceItemInsertArr && $transactionInsertArr) {
                $this->Sales_model->editInvoice($saleInvoice, $saleInvoiceItemInsertArr, $transactionInsertArr, $invoiceInfo[0]['invoice_tran_ref']);
                redirect('Sales/showEditInvoice?response=1&invoice=' . $saleInvoice['invoice_code']);
            } else {
                redirect('Sales/showInvoiceDetails?invoice=' . $invoiceCode);
            }
        } else {
            redirect('Sales/invoice');
        }
    }

    public function paymentReceived() {
        $this->userRoleAuthentication(PAYMENT_RECEIVED);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = PAYMENT_RECEIVED;
        $this->data['pageHeading'] = 'All Reveived Payments';
        $this->data['pageUrl'] = 'sales/paymentReceivedListView';
        $this->loadView($this->data);
    }

    public function newPaymentReceived() {
        $this->userRoleAuthentication(PAYMENT_RECEIVED);

        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "Payment has been created";
            $this->data['msgFlag'] = "success";
        }

        $this->data['paymentModes'] = $this->Sales_model->getPaymentMode();
        $this->data['currentPageCode'] = PAYMENT_RECEIVED;
        $this->data['pageHeading'] = 'New Payment';
        $this->data['pageUrl'] = 'sales/newPaymentReceivedView';
        $this->loadView($this->data);
    }

    public function addNewPaymentReceived() {
        $this->userRoleAuthentication(PAYMENT_RECEIVED);
        $paymentReceive['customer'] = $this->input->post('customerCode', true);
        $paymentReceive['amount'] = (float) $this->input->post('amount', true);
        $paymentReceive['payment_date'] = trim($this->input->post('paymentDate', true));
        $paymentReceive['payment_mode'] = trim($this->input->post('paymentMode', true));
        $paymentReceive['deposit_to'] = trim($this->input->post('depositTo', true));
        $paymentReceive['dis_reference_no'] = ($this->input->post('referenceNo', true)) ? trim($this->input->post('referenceNo', true)) : NULL;

        if ($paymentReceive['customer'] && $paymentReceive['payment_date'] && $paymentReceive['payment_mode'] && $paymentReceive['deposit_to']) {
            // -----------validation -------------//
            if ($paymentReceive['amount'] <= 0) {
                redirect('Sales/newPaymentReceived');
            }

            if (!in_array($paymentReceive['deposit_to'], array(PETTY_CASH, UNDEPOSITED_FUNDS))) {
                redirect('Sales/newPaymentReceived');
            }
            $paymentModes = $this->Sales_model->getPaymentMode();
            $dbPaymentModeArr = array();
            foreach ($paymentModes as $paymentMode) {
                $dbPaymentModeArr[] = $paymentMode['payment_mode_code'];
            }
            if (!in_array($paymentReceive['payment_mode'], $dbPaymentModeArr)) {
                redirect('Sales/newPaymentReceived');
            }
            //------- payment_receive data insert ------------//

            $paymentReceive['payment_receive_code'] = PAYMENT_RECEIVED_CODE . getCode(PAYMENT_RECEIVED_CODE);
            $paymentReceive['created_by'] = $this->user;
            $paymentReceive['created_dt_tm'] = $this->dateTime;
            $paymentReceive['updated_by'] = $this->user;
            $paymentReceive['updated_dt_tm'] = $this->dateTime;

            //--------- transaction data insert ----------------//
            //  Undeposit Funds / petty cash --> $paymentReceive['deposit_to'] debit  -------//
            $transactionGroupId = reference_no();
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentReceive['customer'];
            $transaction['contact_type'] = CUSTOMER;
            $transaction['account'] = $paymentReceive['deposit_to'];  // for payment made purchase-->  credit
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $paymentReceive['amount'];
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentReceive['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            // ----------- Unearned Revenue credit  -------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentReceive['customer'];
            $transaction['contact_type'] = CUSTOMER;
            $transaction['account'] = UNEARNED_REVENUE; // for payment made purchase-->   PREPAID_EXPENSE , debit
            $transaction['credit_amount'] = $paymentReceive['amount'];
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentReceive['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            $this->Sales_model->addPaymentReceived($paymentReceive, $transactonArr);

            redirect('Sales/newPaymentReceived?response=1');
        } else {
            redirect('Sales/newPaymentReceived');
        }
    }

    public function getPaymentList() {
        $this->userRoleAuthentication(PAYMENT_RECEIVED);
        $results = $this->Sales_model->getPaymentDetails();
        $response = array();
        $i = 1;
        foreach ($results as $result) {
            $x = array($i,
                $result['payment_date'],
                '<span class="template-green"><b>' . $result['payment_receive_code'] . '</b></span>',
                $result['dis_reference_no'],
                '<span class="td-f-l">' . $result['contact_name'] . '<br><small><b>' . $result['customer'] . '</b></small></span>',
                $result['payment_mode_title'],
                $result['amount'],
                $result['payment_receive_code']
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }

    public function showPaymentDetails() {
        $this->userRoleAuthentication(PAYMENT_RECEIVED);
        $paymentCode = $this->input->get('paymentCode', true);
        if ($paymentCode) {
            $arr['paymentCode'] = $paymentCode;
            $this->data['paymentDetails'] = $this->Sales_model->getPaymentDetails($arr);
            if ($this->data['paymentDetails']) {
                $this->data['currentPageCode'] = PAYMENT_RECEIVED;
                $this->data['pageHeading'] = 'Payment Receipt';
                $this->data['pageUrl'] = 'sales/paymentDetailsView';
                $this->loadView($this->data);
            } else {
                redirect('Sales/paymentReceived');
            }
        } else {
            redirect('Sales/paymentReceived');
        }
    }

    public function showEditPayment() {
        $this->userRoleAuthentication(PAYMENT_RECEIVED);
        $paymentCode = $this->input->get('paymentCode', true);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "Payment information has been updated";
            $this->data['msgFlag'] = "success";
        } else if ($response == 2) {
            $this->data['msg'] = "You have already used more credits than this payment amount";
            $this->data['msgFlag'] = "danger";
        }
        if ($paymentCode) {
            $arr['paymentCode'] = $paymentCode;
            $this->data['paymentDetails'] = $this->Sales_model->getPaymentDetails($arr);
            if ($this->data['paymentDetails']) {
                $this->data['currentPageCode'] = PAYMENT_RECEIVED;
                $this->data['pageHeading'] = 'Edit Payment Receipt <small>(' . $paymentCode . ')</small>';
                $this->data['pageUrl'] = 'sales/editPaymentReceivedView';
                $this->loadView($this->data);
            } else {
                redirect('Sales/paymentReceived');
            }
        } else {
            redirect('Sales/paymentReceived');
        }
    }

    public function editPaymentReceived() {
        $this->userRoleAuthentication(PAYMENT_RECEIVED);
        $paymentCode = $this->input->post('paymentReceiveCode', true);
        $paymentReceive['amount'] = (float) $this->input->post('amount', true);
        $paymentReceive['payment_date'] = trim($this->input->post('paymentDate', true));
        $paymentReceive['payment_mode'] = trim($this->input->post('paymentMode', true));
        $paymentReceive['deposit_to'] = trim($this->input->post('depositTo', true));
        $paymentReceive['dis_reference_no'] = ($this->input->post('referenceNo', true)) ? trim($this->input->post('referenceNo', true)) : NULL;

        $arr['paymentCode'] = $paymentCode;
        $paymentDetails = $this->Sales_model->getPaymentDetails($arr);

        if ($paymentDetails && $paymentReceive['payment_date'] && $paymentReceive['payment_mode'] && $paymentReceive['deposit_to']) {
            // -----------validation -------------//

            if ($paymentReceive['amount'] <= 0) {
                redirect('Sales/showEditPayment?paymentCode=' . $paymentCode);
            }

            $customerUpdateAmount = $paymentReceive['amount'] - $paymentDetails[0]['amount'];

            if (($paymentDetails[0]['total_balance'] + $customerUpdateAmount) < $paymentDetails[0]['used_balance']) {
                redirect('Sales/showEditPayment?response=2&paymentCode=' . $paymentCode);
            }

            if (!in_array($paymentReceive['deposit_to'], array(PETTY_CASH, UNDEPOSITED_FUNDS))) {
                redirect('Sales/showEditPayment?paymentCode=' . $paymentCode);
            }
            $paymentModes = $this->Sales_model->getPaymentMode();
            $dbPaymentModeArr = array();
            foreach ($paymentModes as $paymentMode) {
                $dbPaymentModeArr[] = $paymentMode['payment_mode_code'];
            }
            if (!in_array($paymentReceive['payment_mode'], $dbPaymentModeArr)) {
                redirect('Sales/showEditPayment?paymentCode=' . $paymentCode);
            }
            //------- payment_receive data insert ------------//

            $paymentReceive['payment_receive_code'] = $paymentCode;
            $paymentReceive['updated_by'] = $this->user;
            $paymentReceive['updated_dt_tm'] = $this->dateTime;

            //--------- transaction data insert ----------------//
            //  Undeposit Funds / petty cash --> $paymentReceive['deposit_to'] debit  -------//
            $transactionGroupId = reference_no();
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentDetails[0]['customer'];
            $transaction['contact_type'] = CUSTOMER;
            $transaction['account'] = $paymentReceive['deposit_to'];
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $paymentReceive['amount'];
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentReceive['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            // ----------- Unearned Revenue credit  -------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentDetails[0]['customer'];
            $transaction['contact_type'] = CUSTOMER;
            $transaction['account'] = UNEARNED_REVENUE;
            $transaction['credit_amount'] = $paymentReceive['amount'];
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentReceive['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;


            $this->Sales_model->editPaymentReceived($paymentReceive, $transactonArr, $paymentDetails[0]['customer'], $customerUpdateAmount);

            redirect('Sales/showEditPayment?response=1&paymentCode=' . $paymentCode);
        } else {
            redirect('Sales/paymentReceived');
        }
    }

    public function invoicePayment() {
        $this->userRoleAuthentication(INVOICE_PAYMENT);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = INVOICE_PAYMENT;
        $this->data['pageHeading'] = 'Invoice Payment';
        $this->data['pageUrl'] = 'sales/invoicePaymentListView';
        $this->loadView($this->data);
    }

    public function showInvoicePaymentDetails() {
        $this->userRoleAuthentication(INVOICE_PAYMENT);
        $invoiceCode = $this->input->get('invoice', true);
        if ($invoiceCode) {
            $response = (int) $this->input->get('response', true);
            $this->data['msgFlag'] = "";
            if ($response == 1) {
                $this->data['msg'] = "Payment has been done of this invoice";
                $this->data['msgFlag'] = "success";
            }

            $arr['invoiceCode'] = $invoiceCode;
            $this->data['invoiceInfo'] = $this->Sales_model->getInvoiceDetails($arr);
            $this->data['invoiceItemDetails'] = $this->Sales_model->getInvoiceItemDetails($arr);
            if ($this->data['invoiceInfo'] && $this->data['invoiceItemDetails']) {
                $this->data['currentPageCode'] = INVOICE_PAYMENT;
                $this->data['pageHeading'] = 'Invoice Payment <small>(' . $invoiceCode . ')</small>';
                $this->data['pageUrl'] = 'sales/invoicePaymentDetailsView';
                $this->loadView($this->data);
            } else {
                redirect('Sales/invoicePayment');
            }
        } else {
            redirect('Sales/invoicePayment');
        }
    }

    public function getCustomerInvoiceList() {
        $this->userRoleAuthentication(INVOICE_PAYMENT);
        $arr['status'] = ($this->input->get('status', true)) ? $this->input->get('status') : NULL;
        $arr['customer'] = ($this->input->get('customer', true)) ? $this->input->get('customer') : NULL;

        $results = $this->Sales_model->getInvoiceDetails($arr);
        $response = array();

        foreach ($results as $result) {
            $status = "";
            $balanceDue = number_format(($result['total'] - $result['paid_amount']), 2);
            if ($result['total'] == $result['paid_amount']) {
                $status = '<small class="template-green">Paid</small>';
            } else {
                $todayDate = date_create(date('Y-m-d'));
                $dueDate = date_create($result['due_date']);
                $interval = date_diff($todayDate, $dueDate);
                $dueDatesCount = (int) $interval->format('%R%a');

                if ($dueDatesCount < 0) {
                    $status = '<small class="text-danger">Overdue By ' . (-1) * $dueDatesCount . ' Day(s)</small>';
                } else {
                    $status = '<small class="text-info">Due in ' . $dueDatesCount . ' Day(s)</small>';
                }
            }

            $x = array(
                '<span class="td-f-l"><b class="template-green">' . $result['invoice_code'] . '</b><br><i>' . $result['invoice_date'] . '</i><br>' . $status . '</span>' .
                '<span class="td-f-r"><b>BDT' . $result['total'] . '</b><br><small class="text-danger">Due BDT ' . $balanceDue . '</small></span>',
                $result['invoice_code'] . ' ' . $result['invoice_date'] . ' ' . $result['total'] . ' ' . $balanceDue
            );
            $response[] = $x;
        }
        echo json_encode(array('data' => $response));
    }

    public function makePayment() {
        $this->userRoleAuthentication(INVOICE_PAYMENT);
        $invoiceCode = $this->input->post('invoice', true);
        $paymentAmount = (float) trim($this->input->post('paymentAmount', true));
        $arr['invoiceCode'] = $invoiceCode;
        $invoiceInfo = $this->Sales_model->getInvoiceDetailsForPayment($arr);
        if ($invoiceInfo && $paymentAmount > 0) {
            $dbPaymentTranRef = $invoiceInfo[0]['payment_tran_ref'];
            //--------- invoice table update -----------------//
            $invoiceAmount = $invoiceInfo[0]['total'];  // total means Invoice total amount
            $status = PARTIALLY_PAID;
            if ($paymentAmount > $invoiceAmount) {  // sum of previous paid amonut and current paid amount can not be grater than total amount of invoice
                redirect('Sales/showInvoicePaymentDetails?invoice=' . $invoiceCode);
            } else if ($paymentAmount == $invoiceAmount) {
                $status = PAID;
            }
            $saleInvoiceArr['payment_tran_ref'] = reference_no();
            $saleInvoiceArr['paid_amount'] = $paymentAmount;
            $saleInvoiceArr['status'] = $status;
            $saleInvoiceArr['updated_by'] = $this->user;
            $saleInvoiceArr['updated_dt_tm'] = $this->dateTime;

            //--------- contact table update (used_balanced and total balance) -------//
            $newTotalBalance = $invoiceInfo[0]['total_balance'] - $invoiceInfo[0]['payment_receive_amount'];  // minus for previous payment 
            $newUsedBalance = $invoiceInfo[0]['used_balance'] - $invoiceInfo[0]['paid_amount'] + $paymentAmount;
            $excessAmount = 0;
            if ($newTotalBalance < $newUsedBalance) {
                $excessAmount = $newUsedBalance - $newTotalBalance;
            }

            $contactArr['total_balance'] = $excessAmount + $newTotalBalance;
            $contactArr['used_balance'] = $newUsedBalance;
            $contactArr['updated_by'] = $this->user;
            $contactArr['updated_dt_tm'] = $this->dateTime;

            // --- if invoice date is greater than today, then Payment Receive and Invoice Payment will be that invoice date
            $todayDate = date_create(date('Y-m-d'));
            $newInvoiceDate = date_create($invoiceInfo[0]['invoice_date']);
            $interval = date_diff($todayDate, $newInvoiceDate);
            $datesCount = (int) $interval->format('%R%a');
            $transactionDate = $this->dateTime;
            $payementReceiveDt = date('Y-m-d');
            if ($datesCount > 0) {
                $transactionDate = $invoiceInfo[0]['invoice_date'] . ' 00:00:00';
                $payementReceiveDt = $invoiceInfo[0]['invoice_date'];
            }
            //---------------//
            $paymentReceive = array();
            if ($excessAmount > 0) {
                $paymentReceiveTranDtTm = $transactionDate;
                $payementDt = $payementReceiveDt;
                if ($invoiceInfo[0]['payment_date']) {
                    $paymentReceiveTranDtTm = $invoiceInfo[0]['payment_date'] . ' 00:00:00';
                    $payementDt = $invoiceInfo[0]['payment_date'];
                }

                // due to excessAmount is greater than 0, this amount has made a Payment Receive
                $paymentReceive['customer'] = $invoiceInfo[0]['customer'];
                $paymentReceive['amount'] = $excessAmount;
                $paymentReceive['payment_date'] = $payementDt;
                $paymentReceive['payment_mode'] = 'cash_pay_mode';
                $paymentReceive['deposit_to'] = UNDEPOSITED_FUNDS;
                $paymentReceive['invoice'] = $invoiceCode;
                $paymentReceive['payment_receive_code'] = PAYMENT_RECEIVED_CODE . getCode(PAYMENT_RECEIVED_CODE);
                $paymentReceive['created_by'] = $this->user;
                $paymentReceive['created_dt_tm'] = $this->dateTime;
                $paymentReceive['updated_by'] = $this->user;
                $paymentReceive['updated_dt_tm'] = $this->dateTime;

                //--------- transaction table insert -------------------//
                //  Undeposit Funds  debit  -------//
                $transactionGroupId = reference_no();
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['invoice'] = $invoiceCode;
                $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $invoiceInfo[0]['customer'];
                $transaction['contact_type'] = CUSTOMER;
                $transaction['account'] = UNDEPOSITED_FUNDS;
                $transaction['credit_amount'] = '0.00';
                $transaction['debit_amount'] = $excessAmount;
                $transaction['transaction_type'] = DEBIT;
                $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
                $transaction['reference_no'] = NULL;
                $transaction['tarn_dt_tm'] = $paymentReceiveTranDtTm;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;

                // ----------- Unearned Revenue credit  -------//
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['invoice'] = $invoiceCode;
                $transaction['payment_receive'] = $paymentReceive['payment_receive_code'];
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $invoiceInfo[0]['customer'];
                $transaction['contact_type'] = CUSTOMER;
                $transaction['account'] = UNEARNED_REVENUE;
                $transaction['credit_amount'] = $excessAmount;
                $transaction['debit_amount'] = '0.00';
                $transaction['transaction_type'] = CREDIT;
                $transaction['transaction_for'] = PAYMENT_RECEIVE_FOR;  // new correction
                $transaction['reference_no'] = NULL;
                $transaction['tarn_dt_tm'] = $paymentReceiveTranDtTm;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;
            }

            //----------- transaction for invoice payment ----------//
            // Unearned Revenue Debit of paymentAmount
            $transactionGroupId = reference_no();
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['invoice'] = $invoiceCode;
            $transaction['payment_receive'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $invoiceInfo[0]['customer'];
            $transaction['contact_type'] = CUSTOMER;
            $transaction['account'] = UNEARNED_REVENUE;
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $paymentAmount;
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = INVOICE_PAYMENT_FOR;  // new correction
            $transaction['reference_no'] = $saleInvoiceArr['payment_tran_ref'];
            $transaction['tarn_dt_tm'] = $transactionDate;
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            // Account Receivable Credit of payment amount
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['invoice'] = $invoiceCode;
            $transaction['payment_receive'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $invoiceInfo[0]['customer'];
            $transaction['contact_type'] = CUSTOMER;
            $transaction['account'] = ACCOUNT_RECEIVABLE;
            $transaction['credit_amount'] = $paymentAmount;
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = INVOICE_PAYMENT_FOR;  // new correction
            $transaction['reference_no'] = $saleInvoiceArr['payment_tran_ref'];
            $transaction['tarn_dt_tm'] = $transactionDate;
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            $this->Sales_model->makeInvoicePayment($saleInvoiceArr, $contactArr, $paymentReceive, $transactonArr, $invoiceCode, $dbPaymentTranRef, $invoiceInfo[0]['customer'], $invoiceInfo[0]['payment_receive_code']);
            redirect('Sales/showInvoicePaymentDetails?response=1&invoice=' . $invoiceCode);
        } else {
            redirect('Sales/invoicePayment');
        }
    }

    public function checkInvoicePayment() {
        $invoiceCode = $this->input->post('invoiceCode', true);
        $paymentAmount = (float) trim($this->input->post('paymentAmount', true));
        $arr['invoiceCode'] = $invoiceCode;
        $invoiceInfo = $this->Sales_model->getInvoiceDetails($arr);
        if ($invoiceInfo && $paymentAmount > 0) {
            $invoiceAmount = $invoiceInfo[0]['total'];
            if ($paymentAmount > $invoiceAmount) {
                echo 3;
                exit();
            }
            $newUsedBalance = $invoiceInfo[0]['used_balance'] - $invoiceInfo[0]['paid_amount'] + $paymentAmount;
            $excessAmount = 0;
            if ($invoiceInfo[0]['total_balance'] < $newUsedBalance) {
                $excessAmount = $newUsedBalance - $invoiceInfo[0]['total_balance'];
            }
            if ($excessAmount) {
                echo '2|' . number_format($excessAmount, 2);
            } else {
                echo 1;
            }
        } else {
            echo 3;
        }
    }

    public function test() {
        $datetime1 = date_create(date('Y-m-d'));
        $datetime2 = date_create('2019-05-27');
        $interval = date_diff($datetime1, $datetime2);
        echo (int) $interval->format('%R%a');
    }

}
