<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Purchase_model');
    }

    public function index() {
        redirect('Home');
    }

    public function bill() {
        $this->userRoleAuthentication(BILL_PAGE);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = BILL_PAGE;
        $this->data['pageHeading'] = 'Bill';
        $this->data['pageUrl'] = 'purchase/purchaseListView';
        $this->loadView($this->data);
    }

    public function getBillList() {
        $this->userRoleAuthentication(BILL_PAGE);
        $arr['status'] = ($this->input->get('status', true)) ? $this->input->get('status') : NULL;
        $arr['vendor'] = ($this->input->get('vendor', true)) ? $this->input->get('vendor') : NULL;

        $results = $this->Purchase_model->getBillDetails($arr);
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
                $result['bill_date'],
                '<span class="template-green"><b>' . $result['bill_code'] . '</b></span>',
                $result['display_reference_no'],
                '<span class="td-f-l">' . $result['contact_name'] . '<br><small><b>' . $result['vendor'] . '</b></small></span>',
                $status,
                $result['due_date'],
                $result['total'],
                $balanceDue,
                $result['bill_code']
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }

    public function newBill() {
        $this->userRoleAuthentication(BILL_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "The bill has been created";
            $this->data['msgFlag'] = "success";
        }
        $this->data['currentPageCode'] = BILL_PAGE;
        $this->data['pageHeading'] = 'New Bill';
        $this->data['pageUrl'] = 'purchase/newBillView';
        $this->loadView($this->data);
    }

    public function getVendor() {
        $this->userRoleAuthentication(NULL, array(BILL_PAGE));
        $results = $this->Purchase_model->getVendor();
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
        $this->userRoleAuthentication(BILL_PAGE);
        $results = $this->Purchase_model->getPurchaseItem();
        $response = array();
        foreach ($results as $result) {
            $x = array(
                '<span class="td-f-l"><i class="fa fa-tag"></i> <b class="template-green">' . $result['title'] . '</b><br><i class="fa fa-money"></i> <small>BDT ' . $result['purchase_rate'] . ' Per ' . $result['unit_name'] . '</small>',
                $result['item_code'],
                $result['title'],
                $result['unit_name'],
                $result['purchase_rate'],
                $result['purchase_tax'],
                $result['tax_title'],
                $result['tax_rate']
            );
            $response[] = $x;
        }
        echo json_encode(array('data' => $response));
    }

    public function checkBillPaymentWhenCreate() {
//        $this->load->model('Contacts_model');
        $paymentAmount = (float) trim($this->input->post('billAmount', true));
        $vendor = trim($this->input->post('vendor', true));
        if ($paymentAmount > 0) {
            $arr['vendorId'] = $vendor;
            $vendorInfo = $this->Purchase_model->getVendor($arr);
            $newUsedBalance = $vendorInfo[0]['used_balance'] + $paymentAmount;
            $excessAmount = 0;
            if ($vendorInfo[0]['total_balance'] < $newUsedBalance) {
                $excessAmount = $newUsedBalance - $vendorInfo[0]['total_balance'];
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

    public function addNewBill() {
        $this->userRoleAuthentication(BILL_PAGE);
        $billFlag = (int) $this->input->post('billFlag', true);
        if ($billFlag == 1) {
            $this->addNewOnlyBill();
        } elseif ($billFlag == 2) {
            $this->addNewBillAndPayment();
        } else {
            redirect('Purchase/newBill');
        }
    }

    public function addNewOnlyBill() {
        $this->userRoleAuthentication(BILL_PAGE);
        $purchaseBill['vendor'] = $this->input->post('vendorCode', true);
        $purchaseBill['bill_date'] = $this->input->post('billDate', true);
        $purchaseBill['due_date'] = $this->input->post('dueDate', true);
        if ($purchaseBill['vendor'] && $purchaseBill['bill_date'] && $purchaseBill['due_date']) {
            $purchaseBill['display_reference_no'] = ($this->input->post('referenceNo', true)) ? $this->input->post('referenceNo', true) : NULL;
            $purchaseBill['vendor_notes'] = ($this->input->post('vendorNotes', true)) ? $this->input->post('vendorNotes', true) : NULL;
            $purchaseBill['terms_condition'] = ($this->input->post('termsCondition', true)) ? $this->input->post('termsCondition', true) : NULL;
            $purchaseBill['adjustment'] = ($this->input->post('adjust', true)) ? (float) $this->input->post('adjust', true) : NULL;
            $purchaseBill['bill_code'] = BILL_CODE . getCode(BILL_CODE);
            $purchaseBill['bill_tran_ref'] = reference_no();
            $itemCount = (int) $this->input->post('applyItemCount', true);
            $itemArr = array();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $itemArr[] = $itemCode;
                }
            }

            if ($itemArr) {
                $itemPurchaseAccountsInfo = array();
                $itemPurchaseAccounts = $this->Purchase_model->getPurchaseItemAccount($itemArr);
                foreach ($itemPurchaseAccounts as $itemPurchaseAccount) {
                    $itemPurchaseAccountsInfo[] = $itemPurchaseAccount['purchase_account'];
                }
            } else {
                redirect('Purchase/newBill');
            }
            $purchaseBillItemInsertArr = array();
            $transactionInsertArr = array();
            $purchaseAccountCount = 0;
            $total = 0;
            $transactionGroupId = reference_no();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $amount = 0;
                    //-------------  purchase bill per item ------------//
                    $purchaseBillItem['bill_code'] = $purchaseBill['bill_code'];
                    $purchaseBillItem['reference_no'] = reference_no();
                    $purchaseBillItem['item'] = $itemCode;
                    $purchaseBillItem['quantity'] = (float) $this->input->post('itemQuantity' . $i, true);
                    $purchaseBillItem['rate'] = (float) $this->input->post('itemRate' . $i, true);
                    $purchaseBillItem['unit'] = trim($this->input->post('itemUnitName' . $i, true));
                    $purchaseBillItem['tax_rate'] = (float) $this->input->post('itemTaxRate' . $i, true);
                    $purchaseBillItem['tax_code'] = $this->input->post('itemTaxCode' . $i, true);
                    if ($purchaseBillItem['quantity'] <= 0 || $purchaseBillItem['rate'] <= 0) {
                        redirect('Purchase/newBill');
                    }

                    $amount = $purchaseBillItem['rate'] * $purchaseBillItem['quantity'];
                    if ($purchaseBillItem['tax_rate'] > 0) {
                        $amount = $amount + (($amount * $purchaseBillItem['tax_rate']) / 100);
                    }

                    $purchaseBillItem['amount'] = $amount;
                    $purchaseBillItem['purchase_account'] = $itemPurchaseAccountsInfo[$purchaseAccountCount];
                    $purchaseBillItem['created_by'] = $this->user;
                    $purchaseBillItem['created_dt_tm'] = $this->dateTime;
                    $purchaseBillItem['updated_by'] = $this->user;
                    $purchaseBillItem['updated_dt_tm'] = $this->dateTime;
                    $purchaseAccountCount++;
                    $purchaseBillItemInsertArr[] = $purchaseBillItem;

                    //------------ per item transaction ------------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['bill'] = $purchaseBill['bill_code'];
                    $transaction['bill_item_ref_no'] = $purchaseBillItem['reference_no'];
                    $transaction['reference_no'] = $purchaseBill['bill_tran_ref'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $purchaseBill['vendor'];
                    $transaction['contact_type'] = VENDOR;
                    $transaction['account'] = $purchaseBillItem['purchase_account'];  // for purchase it will be purchase account and it will be debit
                    $transaction['credit_amount'] = '0.00';
                    $transaction['debit_amount'] = $amount;
                    $transaction['transaction_type'] = DEBIT;
                    $transaction['transaction_for'] = BILL_CREATE_FOR;
                    $transaction['tarn_dt_tm'] = $purchaseBill['bill_date'] . ' 00:00:00';
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactionInsertArr[] = $transaction;
                    $total = $total + $amount;
                }
            }
            $purchaseBill['sub_total'] = $total;
            $purchaseBill['total'] = (float) $total;
            if ($purchaseBill['adjustment'] != NULL) {
                $purchaseBill['total'] = (float) ($total + $purchaseBill['adjustment']);
            }
            if ($purchaseBill['total'] < 0) {
                redirect('Purchase/newBill');
            }
            $purchaseBill['status'] = UNPAID;
            $purchaseBill['created_by'] = $this->user;
            $purchaseBill['created_dt_tm'] = $this->dateTime;
            $purchaseBill['updated_by'] = $this->user;
            $purchaseBill['updated_dt_tm'] = $this->dateTime;

            //------------ aacount payable Credit of total amount -------------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['bill'] = $purchaseBill['bill_code'];
            $transaction['bill_item_ref_no'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = NULL;
            $transaction['contact_type'] = NULL;
            $transaction['account'] = ACCOUNT_PAYABLE;  // for purchase it will be ACCOUNT PAYABLE and CREDIT
            $transaction['credit_amount'] = $purchaseBill['total'];
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = BILL_CREATE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $purchaseBill['bill_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactionInsertArr[] = $transaction;

            if ($purchaseBill && $purchaseBillItemInsertArr && $transactionInsertArr) {
                $this->Purchase_model->addNewBill($purchaseBill, $purchaseBillItemInsertArr, $transactionInsertArr);
                redirect('Purchase/newBill?response=1');
            } else {
                redirect('Purchase/newBill');
            }
        } else {
            redirect('Purchase/newBill');
        }
    }

    public function addNewBillAndPayment() {
        $this->userRoleAuthentication(BILL_PAGE);
        $purchaseBill['vendor'] = $this->input->post('vendorCode', true);
        $purchaseBill['bill_date'] = $this->input->post('billDate', true);
        $purchaseBill['due_date'] = $this->input->post('dueDate', true);
        if ($purchaseBill['vendor'] && $purchaseBill['bill_date'] && $purchaseBill['due_date']) {
            $purchaseBill['display_reference_no'] = ($this->input->post('referenceNo', true)) ? $this->input->post('referenceNo', true) : NULL;
            $purchaseBill['vendor_notes'] = ($this->input->post('vendorNotes', true)) ? $this->input->post('vendorNotes', true) : NULL;
            $purchaseBill['terms_condition'] = ($this->input->post('termsCondition', true)) ? $this->input->post('termsCondition', true) : NULL;
            $purchaseBill['adjustment'] = ($this->input->post('adjust', true)) ? (float) $this->input->post('adjust', true) : NULL;
            $purchaseBill['bill_code'] = BILL_CODE . getCode(BILL_CODE);
            $purchaseBill['bill_tran_ref'] = reference_no();
            $itemCount = (int) $this->input->post('applyItemCount', true);
            $itemArr = array();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $itemArr[] = $itemCode;
                }
            }

            if ($itemArr) {
                $itemPurchaseAccountsInfo = array();
                $itemPurchaseAccounts = $this->Purchase_model->getPurchaseItemAccount($itemArr);
                foreach ($itemPurchaseAccounts as $itemPurchaseAccount) {
                    $itemPurchaseAccountsInfo[] = $itemPurchaseAccount['purchase_account'];
                }
            } else {
                redirect('Purchase/newBill');
            }
            $purchaseBillItemInsertArr = array();
            $transactionInsertArr = array();
            $purchaseAccountCount = 0;
            $total = 0;
            $transactionGroupId = reference_no();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $amount = 0;
                    //-------------  purchase bill per item ------------//
                    $purchaseBillItem['bill_code'] = $purchaseBill['bill_code'];
                    $purchaseBillItem['reference_no'] = reference_no();
                    $purchaseBillItem['item'] = $itemCode;
                    $purchaseBillItem['quantity'] = (float) $this->input->post('itemQuantity' . $i, true);
                    $purchaseBillItem['rate'] = (float) $this->input->post('itemRate' . $i, true);
                    $purchaseBillItem['unit'] = trim($this->input->post('itemUnitName' . $i, true));
                    $purchaseBillItem['tax_rate'] = (float) $this->input->post('itemTaxRate' . $i, true);
                    $purchaseBillItem['tax_code'] = $this->input->post('itemTaxCode' . $i, true);
                    if ($purchaseBillItem['quantity'] <= 0 || $purchaseBillItem['rate'] <= 0) {
                        redirect('Purchase/newBill');
                    }

                    $amount = $purchaseBillItem['rate'] * $purchaseBillItem['quantity'];
                    if ($purchaseBillItem['tax_rate'] > 0) {
                        $amount = $amount + (($amount * $purchaseBillItem['tax_rate']) / 100);
                    }

                    $purchaseBillItem['amount'] = $amount;
                    $purchaseBillItem['purchase_account'] = $itemPurchaseAccountsInfo[$purchaseAccountCount];
                    $purchaseBillItem['created_by'] = $this->user;
                    $purchaseBillItem['created_dt_tm'] = $this->dateTime;
                    $purchaseBillItem['updated_by'] = $this->user;
                    $purchaseBillItem['updated_dt_tm'] = $this->dateTime;
                    $purchaseAccountCount++;
                    $purchaseBillItemInsertArr[] = $purchaseBillItem;

                    //------------ per item transaction ------------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['bill'] = $purchaseBill['bill_code'];
                    $transaction['bill_item_ref_no'] = $purchaseBillItem['reference_no'];
                    $transaction['reference_no'] = $purchaseBill['bill_tran_ref'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $purchaseBill['vendor'];
                    $transaction['contact_type'] = VENDOR;
                    $transaction['account'] = $purchaseBillItem['purchase_account'];
                    $transaction['credit_amount'] = '0.00';
                    $transaction['debit_amount'] = $amount;
                    $transaction['transaction_type'] = DEBIT;  // for puchase it will be DEBIT
                    $transaction['transaction_for'] = BILL_CREATE_FOR;  // new correction
                    $transaction['tarn_dt_tm'] = $purchaseBill['bill_date'] . ' 00:00:00';
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactionInsertArr[] = $transaction;

                    $total = $total + $amount;
                }
            }
            $purchaseBill['sub_total'] = $total;
            $purchaseBill['total'] = (float) $total;
            if ($purchaseBill['adjustment'] != NULL) {
                $purchaseBill['total'] = (float) ($total + $purchaseBill['adjustment']);
            }
            if ($purchaseBill['total'] < 0) {
                redirect('Purchase/newBill');
            }
            $purchaseBill['status'] = UNPAID;
            $purchaseBill['created_by'] = $this->user;
            $purchaseBill['created_dt_tm'] = $this->dateTime;
            $purchaseBill['updated_by'] = $this->user;
            $purchaseBill['updated_dt_tm'] = $this->dateTime;

            //------------ account receivable Debit of total amount -------------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['bill'] = $purchaseBill['bill_code'];
            $transaction['bill_item_ref_no'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = NULL;
            $transaction['contact_type'] = NULL;
            $transaction['account'] = ACCOUNT_PAYABLE;  // for purchase it will be ACCOUNT PAYABLE and CREDIT
            $transaction['credit_amount'] = $purchaseBill['total'];
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = BILL_CREATE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $purchaseBill['bill_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactionInsertArr[] = $transaction;

            if ($purchaseBill && $purchaseBillItemInsertArr && $transactionInsertArr) {
                $this->Purchase_model->addNewBill($purchaseBill, $purchaseBillItemInsertArr, $transactionInsertArr);
                //--------------------------- payment ----------------------------------//
                $transactonArr = array();
                $billCode = $purchaseBill['bill_code'];
                $paymentAmount = (float) $purchaseBill['total'];
                $arr['billCode'] = $billCode;
                $billInfo = $this->Purchase_model->getBillDetailsForPayment($arr);

                $dbPaymentTranRef = $billInfo[0]['payment_tran_ref'];
                //--------- bill table update -----------------//
                $billAmount = $billInfo[0]['total'];  // total means Bill total amount
                $status = PARTIALLY_PAID;
                if ($paymentAmount > $billAmount) {  // sum of previous paid amonut and current paid amount can not be grater than total amount of bill
                    redirect('Purchase/showBillPaymentDetails?bill=' . $billCode);
                } else if ($paymentAmount == $billAmount) {
                    $status = PAID;
                }
                $purchaseBillArr['payment_tran_ref'] = reference_no();
                $purchaseBillArr['paid_amount'] = $paymentAmount;
                $purchaseBillArr['status'] = $status;
                $purchaseBillArr['updated_by'] = $this->user;
                $purchaseBillArr['updated_dt_tm'] = $this->dateTime;

                //--------- contact table update (used_balanced and total balance) -------//
                $newTotalBalance = $billInfo[0]['total_balance'] - $billInfo[0]['payment_made_amount'];  // minus for previous payment 
                $newUsedBalance = $billInfo[0]['used_balance'] - $billInfo[0]['paid_amount'] + $paymentAmount;
                $excessAmount = 0;
                if ($newTotalBalance < $newUsedBalance) {
                    $excessAmount = $newUsedBalance - $newTotalBalance;
                }

                $contactArr['total_balance'] = $excessAmount + $newTotalBalance;
                $contactArr['used_balance'] = $newUsedBalance;
                $contactArr['updated_by'] = $this->user;
                $contactArr['updated_dt_tm'] = $this->dateTime;

                // --- if bill date is greater than today, then Payment Made and Bill Payment will be that bill date
                $todayDate = date_create(date('Y-m-d'));
                $newBillDate = date_create($purchaseBill['bill_date']);
                $interval = date_diff($todayDate, $newBillDate);
                $datesCount = (int) $interval->format('%R%a');
                $transactionDate = $this->dateTime;
                if ($datesCount > 0) {
                    $transactionDate = $purchaseBill['bill_date'] . ' 00:00:00';
                }
                //---------------//
                $paymentMade = array();
                if ($excessAmount > 0) {
                    $paymentMadeTranDtTm = $transactionDate;
                    if ($billInfo[0]['payment_date']) {
                        $paymentMadeTranDtTm = $billInfo[0]['payment_date'] . ' 00:00:00';
                    }
                    // due to excessAmount is greater than 0, this amount has made a Payment Made
                    $paymentMade['vendor'] = $billInfo[0]['vendor'];
                    $paymentMade['amount'] = $excessAmount;
                    $paymentMade['payment_date'] = $purchaseBill['bill_date'];
                    $paymentMade['payment_mode'] = 'cash_pay_mode';
                    $paymentMade['paid_through'] = UNDEPOSITED_FUNDS;
                    $paymentMade['bill'] = $billCode;
                    $paymentMade['payment_made_code'] = PAYMENT_MADE_CODE . getCode(PAYMENT_MADE_CODE);
                    $paymentMade['created_by'] = $this->user;
                    $paymentMade['created_dt_tm'] = $this->dateTime;
                    $paymentMade['updated_by'] = $this->user;
                    $paymentMade['updated_dt_tm'] = $this->dateTime;

                    //--------- transaction table insert -------------------//
                    //  Undeposit Funds  debit  -------//
                    $transactionGroupId = reference_no();
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['bill'] = $billCode;
                    $transaction['payment_made'] = $paymentMade['payment_made_code'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $billInfo[0]['vendor'];
                    $transaction['contact_type'] = VENDOR;
                    $transaction['account'] = UNDEPOSITED_FUNDS;
                    $transaction['credit_amount'] = $excessAmount;
                    $transaction['debit_amount'] = '0.00';
                    $transaction['transaction_type'] = CREDIT;
                    $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
                    $transaction['reference_no'] = NULL;
                    $transaction['tarn_dt_tm'] = $paymentMadeTranDtTm;
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactonArr[] = $transaction;

                    // ----------- Unearned Revenue credit  -------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['bill'] = $billCode;
                    $transaction['payment_made'] = $paymentMade['payment_made_code'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $billInfo[0]['vendor'];
                    $transaction['contact_type'] = VENDOR;
                    $transaction['account'] = PREPAID_EXPENSE;
                    $transaction['credit_amount'] = '0.00';
                    $transaction['debit_amount'] = $excessAmount;
                    $transaction['transaction_type'] = DEBIT;
                    $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
                    $transaction['reference_no'] = NULL;
                    $transaction['tarn_dt_tm'] = $paymentMadeTranDtTm;
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactonArr[] = $transaction;
                }

                //----------- transaction for bill payment ----------//
                // Unearned Revenue Debit of paymentAmount
                $transactionGroupId = reference_no();
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['bill'] = $billCode;
                $transaction['payment_made'] = NULL;
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $billInfo[0]['vendor'];
                $transaction['contact_type'] = VENDOR;
                $transaction['account'] = PREPAID_EXPENSE;
                $transaction['credit_amount'] = $paymentAmount;
                $transaction['debit_amount'] = '0.00';
                $transaction['transaction_type'] = CREDIT;
                $transaction['transaction_for'] = BILL_PAYMENT_FOR;  // new correction
                $transaction['reference_no'] = $purchaseBillArr['payment_tran_ref'];
                $transaction['tarn_dt_tm'] = $transactionDate;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;

                // Account Paybale Debit of payment amount
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['bill'] = $billCode;
                $transaction['payment_made'] = NULL;
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $billInfo[0]['customer'];
                $transaction['contact_type'] = VENDOR;
                $transaction['account'] = ACCOUNT_PAYABLE;
                $transaction['credit_amount'] = '0.00';
                $transaction['debit_amount'] = $paymentAmount;
                $transaction['transaction_type'] = DEBIT;
                $transaction['transaction_for'] = BILL_PAYMENT_FOR;  // new correction
                $transaction['reference_no'] = $purchaseBillArr['payment_tran_ref'];
                $transaction['tarn_dt_tm'] = $transactionDate;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;

                $this->Purchase_model->makeBillPayment($purchaseBillArr, $contactArr, $paymentMade, $transactonArr, $billCode, $dbPaymentTranRef, $billInfo[0]['vendor'], $billInfo[0]['payment_made_code']);
                redirect('Purchase/newBill?response=1');
            } else {
                redirect('Purchase/newBill');
            }
        } else {
            redirect('Purchase/newBill');
        }
    }

    public function showBillDetails() {
        $this->userRoleAuthentication(BILL_PAGE);
        $billCode = $this->input->get('bill', true);
        if ($billCode) {
            $arr['billCode'] = $billCode;
            $this->data['billInfo'] = $this->Purchase_model->getBillDetails($arr);
            $this->data['billItemDetails'] = $this->Purchase_model->getBillItemDetails($arr);
            if ($this->data['billInfo'] && $this->data['billItemDetails']) {
                $this->data['currentPageCode'] = BILL_PAGE;
                $this->data['pageHeading'] = 'Bill';
                $this->data['pageUrl'] = 'purchase/billDetailsView';
                $this->loadView($this->data);
            } else {
                redirect('Purchase/bill');
            }
        } else {
            redirect('Purchase/bill');
        }
    }

    public function showEditBill() {
        $this->userRoleAuthentication(BILL_PAGE);
        $billCode = $this->input->get('bill', true);
        if ($billCode) {
            $arr['billCode'] = $billCode;
            $response = (int) $this->input->get('response', true);
            $this->data['msgFlag'] = "";
            if ($response == 1) {
                $this->data['msg'] = "Bill information has been updated";
                $this->data['msgFlag'] = "success";
            } else if ($response == 2) {
                $this->data['msg'] = "The payment entered is more than the total amount due for this bill";
                $this->data['msgFlag'] = "danger";
            }

            $this->data['billInfo'] = $this->Purchase_model->getBillDetails($arr);
            $this->data['billItemDetails'] = $this->Purchase_model->getBillItemDetails($arr);
            if ($this->data['billInfo'] && $this->data['billItemDetails']) {
                $this->data['currentPageCode'] = BILL_PAGE;
                $this->data['pageHeading'] = 'Edit Bill <small>(' . $billCode . ')</small>';
                $this->data['pageUrl'] = 'purchase/editBillView';
                $this->loadView($this->data);
            } else {
                redirect('Purchase/bill');
            }
        } else {
            redirect('Purchase/bill');
        }
    }

    public function editBill() {
        $this->userRoleAuthentication(BILL_PAGE);
        $billCode = $this->input->post('billCode', true);
        $purchaseBill['vendor'] = $this->input->post('vendorCode', true);
        $purchaseBill['bill_date'] = $this->input->post('billDate', true);
        $purchaseBill['due_date'] = $this->input->post('dueDate', true);
        $arr['billCode'] = $billCode;
        $billInfo = $this->Purchase_model->getBillDetails($arr);
        $billItemDetails = $this->Purchase_model->getBillItemDetails($arr);
        if ($purchaseBill['vendor'] && $purchaseBill['bill_date'] && $purchaseBill['due_date'] && $billInfo && $billItemDetails) {
            $purchaseBill['display_reference_no'] = ($this->input->post('referenceNo', true)) ? $this->input->post('referenceNo', true) : NULL;
            $purchaseBill['vendor_notes'] = ($this->input->post('vendorNotes', true)) ? $this->input->post('vendorNotes', true) : NULL;
            $purchaseBill['terms_condition'] = ($this->input->post('termsCondition', true)) ? $this->input->post('termsCondition', true) : NULL;
            $purchaseBill['adjustment'] = ($this->input->post('adjust', true)) ? (float) $this->input->post('adjust', true) : NULL;
            $purchaseBill['bill_code'] = $billCode;
            $itemCount = (int) $this->input->post('applyItemCount', true);
            $itemArr = array();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $itemArr[] = $itemCode;
                }
            }

            if ($itemArr) {
                $itemPurchaseAccountsInfo = array();
                $itemPurchaseAccounts = $this->Purchase_model->getPurchaseItemAccount($itemArr);
                foreach ($itemPurchaseAccounts as $itemPurchaseAccount) {
                    $itemPurchaseAccountsInfo[] = $itemPurchaseAccount['purchase_account'];
                }
            } else {
                redirect('Purchase/showBillDetails?bill=' . $billCode);
            }
            $purchaseBillItemInsertArr = array();
            $transactionInsertArr = array();
            $purchaseAccountCount = 0;
            $total = 0;
            $transactionGroupId = reference_no();
            for ($i = 1; $i <= $itemCount; $i++) {
                $itemCode = $this->input->post('itemCode' . $i, true);
                if ($itemCode) {
                    $amount = 0;
                    //-------------  purchase bill per item ------------//
                    $purchaseBillItem['bill_code'] = $purchaseBill['bill_code'];
                    $purchaseBillItem['reference_no'] = reference_no();
                    $purchaseBillItem['item'] = $itemCode;
                    $purchaseBillItem['quantity'] = (float) $this->input->post('itemQuantity' . $i, true);
                    $purchaseBillItem['rate'] = (float) $this->input->post('itemRate' . $i, true);
                    $purchaseBillItem['unit'] = trim($this->input->post('itemUnitName' . $i, true));
                    $purchaseBillItem['tax_rate'] = (float) $this->input->post('itemTaxRate' . $i, true);
                    $purchaseBillItem['tax_code'] = $this->input->post('itemTaxCode' . $i, true);
                    if ($purchaseBillItem['quantity'] <= 0 || $purchaseBillItem['rate'] <= 0) {
                        redirect('Purchase/showBillDetails?bill=' . $billCode);
                    }

                    $amount = $purchaseBillItem['rate'] * $purchaseBillItem['quantity'];
                    if ($purchaseBillItem['tax_rate'] > 0) {
                        $amount = $amount + (($amount * $purchaseBillItem['tax_rate']) / 100);
                    }

                    $purchaseBillItem['amount'] = $amount;
                    $purchaseBillItem['purchase_account'] = $itemPurchaseAccountsInfo[$purchaseAccountCount];
                    $purchaseBillItem['created_by'] = $this->user;
                    $purchaseBillItem['created_dt_tm'] = $this->dateTime;
                    $purchaseBillItem['updated_by'] = $this->user;
                    $purchaseBillItem['updated_dt_tm'] = $this->dateTime;
                    $purchaseAccountCount++;
                    $purchaseBillItemInsertArr[] = $purchaseBillItem;

                    //------------ per item transaction ------------//
                    $transaction['transaction_group_id'] = $transactionGroupId;
                    $transaction['bill'] = $purchaseBill['bill_code'];
                    $transaction['bill_item_ref_no'] = $purchaseBillItem['reference_no'];
                    $transaction['transaction_id'] = reference_no();
                    $transaction['contact_code'] = $purchaseBill['vendor'];
                    $transaction['contact_type'] = VENDOR;
                    $transaction['account'] = $purchaseBillItem['purchase_account'];
                    $transaction['credit_amount'] = '0.00';
                    $transaction['debit_amount'] = $amount;
                    $transaction['transaction_type'] = DEBIT;
                    $transaction['transaction_for'] = BILL_CREATE_FOR;  // new correction
                    $transaction['tarn_dt_tm'] = $purchaseBill['bill_date'] . ' 00:00:00';
                    $transaction['reference_no'] = $billInfo[0]['bill_tran_ref'];
                    $transaction['created_by'] = $this->user;
                    $transaction['created_dt_tm'] = $this->dateTime;
                    $transaction['updated_by'] = $this->user;
                    $transaction['updated_dt_tm'] = $this->dateTime;
                    $transactionInsertArr[] = $transaction;
                    $total = $total + $amount;
                }
            }
            $purchaseBill['sub_total'] = $total;
            $purchaseBill['total'] = (float) $total;
            if ($purchaseBill['adjustment'] != NULL) {
                $purchaseBill['total'] = (float) ($total + $purchaseBill['adjustment']);
            }

            if ($purchaseBill['total'] < 0) {
                redirect('Purchase/showEditBill?bill=' . $purchaseBill['bill_code']);
            }

            if ($billInfo[0]['paid_amount'] > $purchaseBill['total']) {
                redirect('Purchase/showEditBill?response=2&bill=' . $purchaseBill['bill_code']);
            }
            //$purchaseBill['status'] = UNPAID;
            $purchaseBill['updated_by'] = $this->user;
            $purchaseBill['updated_dt_tm'] = $this->dateTime;

            //------------ aacount payable Credit of total amount -------------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['bill'] = $purchaseBill['bill_code'];
            $transaction['bill_item_ref_no'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = NULL;
            $transaction['contact_type'] = NULL;
            $transaction['account'] = ACCOUNT_PAYABLE;
            $transaction['credit_amount'] = $purchaseBill['total'];
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = BILL_CREATE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $purchaseBill['bill_date'] . ' 00:00:00';
            $transaction['reference_no'] = $billInfo[0]['bill_tran_ref'];
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactionInsertArr[] = $transaction;

            if ($purchaseBill && $purchaseBillItemInsertArr && $transactionInsertArr) {
                $this->Purchase_model->editBill($purchaseBill, $purchaseBillItemInsertArr, $transactionInsertArr, $billInfo[0]['bill_tran_ref']);
                redirect('Purchase/showEditBill?response=1&bill=' . $purchaseBill['bill_code']);
            } else {
                redirect('Purchase/showBillDetails?bill=' . $billCode);
            }
        } else {
            redirect('Purchase/bill');
        }
    }

    public function paymentMade() {
        $this->userRoleAuthentication(PAYMENT_MADE_PAGE);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = PAYMENT_MADE_PAGE;
        $this->data['pageHeading'] = 'All Made Payments';
        $this->data['pageUrl'] = 'purchase/paymentMadeListView';
        $this->loadView($this->data);
    }

    public function getPaymentList() {
        $this->userRoleAuthentication(PAYMENT_MADE_PAGE);
        $results = $this->Purchase_model->getPaymentDetails();
        $response = array();
        $i = 1;
        foreach ($results as $result) {
            $x = array($i,
                $result['payment_date'],
                '<span class="template-green"><b>' . $result['payment_made_code'] . '</b></span>',
                $result['dis_reference_no'],
                '<span class="td-f-l">' . $result['contact_name'] . '<br><small><b>' . $result['vendor'] . '</b></small></span>',
                $result['payment_mode_title'],
                $result['amount'],
                $result['payment_made_code']
            );
            $response[] = $x;
            $i++;
        }
        echo json_encode(array('data' => $response));
    }

    public function newPaymentMade() {
        $this->userRoleAuthentication(PAYMENT_MADE_PAGE);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "Payment has been created";
            $this->data['msgFlag'] = "success";
        }

        $this->data['paymentModes'] = $this->Purchase_model->getPaymentMode();
        $this->data['currentPageCode'] = PAYMENT_MADE_PAGE;
        $this->data['pageHeading'] = 'New Payment';
        $this->data['pageUrl'] = 'purchase/newPaymentMadeView';
        $this->loadView($this->data);
    }

    public function addNewPaymentMade() {
        $this->userRoleAuthentication(PAYMENT_MADE_PAGE);
        $paymentMade['vendor'] = $this->input->post('vendorCode', true);
        $paymentMade['amount'] = (float) $this->input->post('amount', true);
        $paymentMade['payment_date'] = trim($this->input->post('paymentDate', true));
        $paymentMade['payment_mode'] = trim($this->input->post('paymentMode', true));
        $paymentMade['paid_through'] = trim($this->input->post('paidThrough', true));
        $paymentMade['dis_reference_no'] = ($this->input->post('referenceNo', true)) ? trim($this->input->post('referenceNo', true)) : NULL;

        if ($paymentMade['vendor'] && $paymentMade['payment_date'] && $paymentMade['payment_mode'] && $paymentMade['paid_through']) {
            // -----------validation -------------//
            if ($paymentMade['amount'] <= 0) {
                redirect('Purchase/newPaymentMade');
            }
            //when excess amount payment made it will be UNDEPOSITED_FUNDS
            if (!in_array($paymentMade['paid_through'], array(PETTY_CASH, UNDEPOSITED_FUNDS))) {
                redirect('Purchase/newPaymentMade');
            }
            $paymentModes = $this->Purchase_model->getPaymentMode();
            $dbPaymentModeArr = array();
            foreach ($paymentModes as $paymentMode) {
                $dbPaymentModeArr[] = $paymentMode['payment_mode_code'];
            }
            if (!in_array($paymentMade['payment_mode'], $dbPaymentModeArr)) {
                redirect('Purchase/newPaymentMade');
            }
            //------- payment_made data insert ------------//
            $paymentMade['payment_made_code'] = PAYMENT_MADE_CODE . getCode(PAYMENT_MADE_CODE);
            $paymentMade['created_by'] = $this->user;
            $paymentMade['created_dt_tm'] = $this->dateTime;
            $paymentMade['updated_by'] = $this->user;
            $paymentMade['updated_dt_tm'] = $this->dateTime;

            //--------- transaction data insert ----------------//
            //  Undeposit Funds / petty cash --> $paymentMade['deposit_to'] debit  -------//
            $transactionGroupId = reference_no();
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_made'] = $paymentMade['payment_made_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentMade['vendor'];
            $transaction['contact_type'] = VENDOR;
            $transaction['account'] = $paymentMade['paid_through']; //when excess amount it will be UNDEPOSITED_FUNDS
            $transaction['credit_amount'] = $paymentMade['amount'];
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT; //transACTION account = UNDEPOSITED_FUNDS = credit
            $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentMade['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            // ----------- Prepaid expense debit  -------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_made'] = $paymentMade['payment_made_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentMade['vendor'];
            $transaction['contact_type'] = VENDOR;
            $transaction['account'] = PREPAID_EXPENSE; //prepaid expense will be debit
            $transaction['credit_amount'] = '0.00'; //alter
            $transaction['debit_amount'] = $paymentMade['amount']; //alter
            $transaction['transaction_type'] = DEBIT; //alter
            $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentMade['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            $this->Purchase_model->addPaymentMade($paymentMade, $transactonArr);
            redirect('Purchase/newPaymentMade?response=1');
        } else {
            redirect('Purchase/newPaymentMade');
        }
    }

    public function showPaymentDetails() {
        $this->userRoleAuthentication(PAYMENT_MADE_PAGE);
        $paymentCode = $this->input->get('paymentCode', true);
        if ($paymentCode) {
            $arr['paymentCode'] = $paymentCode;
            $this->data['paymentDetails'] = $this->Purchase_model->getPaymentDetails($arr);
            if ($this->data['paymentDetails']) {
                $this->data['currentPageCode'] = PAYMENT_MADE_PAGE;
                $this->data['pageHeading'] = 'Payment Made';
                $this->data['pageUrl'] = 'purchase/paymentDetailsView';
                $this->loadView($this->data);
            } else {
                redirect('Purchase/paymentMade');
            }
        } else {
            redirect('Purchase/paymentMade');
        }
    }

    public function showEditPayment() {
        $this->userRoleAuthentication(PAYMENT_MADE_PAGE);
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
            $this->data['paymentModes'] = $this->Purchase_model->getPaymentMode();
            $this->data['paymentDetails'] = $this->Purchase_model->getPaymentDetails($arr);
            if ($this->data['paymentDetails']) {
                $this->data['currentPageCode'] = PAYMENT_MADE_PAGE;
                $this->data['pageHeading'] = 'Edit Payment Made <small>(' . $paymentCode . ')</small>';
                $this->data['pageUrl'] = 'purchase/editPaymentMadeView';
                $this->loadView($this->data);
            } else {
                redirect('Purchase/paymentMade');
            }
        } else {
            redirect('Purchase/paymentMade');
        }
    }

    public function editPaymentMade() {
        $this->userRoleAuthentication(PAYMENT_MADE_PAGE);
        $paymentCode = $this->input->post('paymentMadeCode', true);
        $paymentMade['amount'] = (float) $this->input->post('amount', true);
        $paymentMade['payment_date'] = trim($this->input->post('paymentDate', true));
        $paymentMade['payment_mode'] = trim($this->input->post('paymentMode', true));
        $paymentMade['paid_through'] = trim($this->input->post('paidThrough', true));
        $paymentMade['dis_reference_no'] = ($this->input->post('referenceNo', true)) ? trim($this->input->post('referenceNo', true)) : NULL;

        $arr['paymentCode'] = $paymentCode;
        $paymentDetails = $this->Purchase_model->getPaymentDetails($arr);

        if ($paymentDetails && $paymentMade['payment_date'] && $paymentMade['payment_mode'] && $paymentMade['paid_through']) {
            // -----------validation -------------//
            if ($paymentMade['amount'] <= 0) {
                redirect('Purchase/showEditPayment?paymentCode=' . $paymentCode);
            }

            $vendorUpdateAmount = $paymentMade['amount'] - $paymentDetails[0]['amount'];

            if (($paymentDetails[0]['total_balance'] + $vendorUpdateAmount) < $paymentDetails[0]['used_balance']) {
                redirect('Purchase/showEditPayment?response=2&paymentCode=' . $paymentCode);
            }

            if (!in_array($paymentMade['paid_through'], array(PETTY_CASH, UNDEPOSITED_FUNDS))) {
                redirect('Purchase/showEditPayment?paymentCode=' . $paymentCode);
            }
            $paymentModes = $this->Purchase_model->getPaymentMode();
            $dbPaymentModeArr = array();
            foreach ($paymentModes as $paymentMode) {
                $dbPaymentModeArr[] = $paymentMode['payment_mode_code'];
            }
            if (!in_array($paymentMade['payment_mode'], $dbPaymentModeArr)) {
                redirect('Purchase/showEditPayment?paymentCode=' . $paymentCode);
            }
            //------- payment_Made data insert ------------//
            $paymentMade['payment_made_code'] = $paymentCode;
            $paymentMade['updated_by'] = $this->user;
            $paymentMade['updated_dt_tm'] = $this->dateTime;

            //--------- transaction data insert ----------------//
            $transactionGroupId = reference_no();
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_made'] = $paymentMade['payment_made_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentDetails[0]['vendor'];
            $transaction['contact_type'] = VENDOR;
            $transaction['account'] = $paymentMade['paid_through'];
            $transaction['credit_amount'] = $paymentMade['amount'];
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentMade['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            // ----------- Unearned Revenue credit  -------//
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['payment_made'] = $paymentMade['payment_made_code'];
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $paymentDetails[0]['vendor'];
            $transaction['contact_type'] = VENDOR;
            $transaction['account'] = PREPAID_EXPENSE;
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $paymentMade['amount'];
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
            $transaction['tarn_dt_tm'] = $paymentMade['payment_date'] . ' 00:00:00';
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            $this->Purchase_model->editPaymentMade($paymentMade, $transactonArr, $paymentDetails[0]['vendor'], $vendorUpdateAmount);
            redirect('Purchase/showEditPayment?response=1&paymentCode=' . $paymentCode);
        } else {
            redirect('Purchase/paymentMade');
        }
    }

    public function billPayment() {
        $this->userRoleAuthentication(BILL_PAYMENT_PAGE);
        $this->data['status'] = (int) $this->input->post('status', true);
        $this->data['currentPageCode'] = BILL_PAYMENT_PAGE;
        $this->data['pageHeading'] = 'Bill Payment';
        $this->data['pageUrl'] = 'purchase/billPaymentListView';
        $this->loadView($this->data);
    }

    public function showBillPaymentDetails() {
        $this->userRoleAuthentication(BILL_PAYMENT_PAGE);
        $billCode = $this->input->get('bill', true);
        if ($billCode) {
            $response = (int) $this->input->get('response', true);
            $this->data['msgFlag'] = "";
            if ($response == 1) {
                $this->data['msg'] = "Payment has been done of this bill";
                $this->data['msgFlag'] = "success";
            }

            $arr['billCode'] = $billCode;
            $this->data['billInfo'] = $this->Purchase_model->getBillDetails($arr);
            $this->data['billItemDetails'] = $this->Purchase_model->getBillItemDetails($arr);
            if ($this->data['billInfo'] && $this->data['billItemDetails']) {
                $this->data['currentPageCode'] = BILL_PAYMENT_PAGE;
                $this->data['pageHeading'] = 'Bill Payment <small>(' . $billCode . ')</small>';
                $this->data['pageUrl'] = 'purchase/billPaymentDetailsView';
                $this->loadView($this->data);
            } else {
                redirect('Purchase/billPayment');
            }
        } else {
            redirect('Purchase/billPayment');
        }
    }

    public function getVendorBillList() {
        $this->userRoleAuthentication(BILL_PAYMENT_PAGE);
        $arr['status'] = ($this->input->get('status', true)) ? $this->input->get('status') : NULL;
        $arr['vendor'] = ($this->input->get('vendor', true)) ? $this->input->get('vendor') : NULL;

        $results = $this->Purchase_model->getBillDetails($arr);
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
                '<span class="td-f-l"><b class="template-green">' . $result['bill_code'] . '</b><br><i>' . $result['bill_date'] . '</i><br>' . $status . '</span>' .
                '<span class="td-f-r"><b>BDT' . $result['total'] . '</b><br><small class="text-danger">Due BDT ' . $balanceDue . '</small></span>',
                $result['bill_code'] . ' ' . $result['bill_date'] . ' ' . $result['total'] . ' ' . $balanceDue
            );
            $response[] = $x;
        }
        echo json_encode(array('data' => $response));
    }

    public function makePayment() {
        $this->userRoleAuthentication(BILL_PAYMENT_PAGE);
        $billCode = $this->input->post('bill', true);
        $paymentAmount = (float) trim($this->input->post('paymentAmount', true));
        $arr['billCode'] = $billCode;
        $billInfo = $this->Purchase_model->getBillDetailsForPayment($arr);
        if ($billInfo && $paymentAmount > 0) {
            $dbPaymentTranRef = $billInfo[0]['payment_tran_ref'];
            //--------- bill table update -----------------//
            $billAmount = $billInfo[0]['total'];  // total means Bill total amount
            $status = PARTIALLY_PAID;
            if ($paymentAmount > $billAmount) {  // sum of previous paid amonut and current paid amount can not be grater than total amount of bill
                redirect('Purchase/showBillPaymentDetails?bill=' . $billCode);
            } else if ($paymentAmount == $billAmount) {
                $status = PAID;
            }
            $purchaseBillArr['payment_tran_ref'] = reference_no();
            $purchaseBillArr['paid_amount'] = $paymentAmount;
            $purchaseBillArr['status'] = $status;
            $purchaseBillArr['updated_by'] = $this->user;
            $purchaseBillArr['updated_dt_tm'] = $this->dateTime;

            //--------- contact table update (used_balanced and total balance) -------//
            $newTotalBalance = $billInfo[0]['total_balance'] - $billInfo[0]['payment_made_amount'];  // minus for previous payment 
            $newUsedBalance = $billInfo[0]['used_balance'] - $billInfo[0]['paid_amount'] + $paymentAmount;
            $excessAmount = 0;
            if ($newTotalBalance < $newUsedBalance) {
                $excessAmount = $newUsedBalance - $newTotalBalance;
            }

            $contactArr['total_balance'] = $excessAmount + $newTotalBalance;
            $contactArr['used_balance'] = $newUsedBalance;
            $contactArr['updated_by'] = $this->user;
            $contactArr['updated_dt_tm'] = $this->dateTime;

            // --- if bill date is greater than today, then Payment Made and Bill Payment will be that bill date
            $todayDate = date_create(date('Y-m-d'));
            $newBillDate = date_create($billInfo[0]['bill_date']);
            $interval = date_diff($todayDate, $newBillDate);
            $datesCount = (int) $interval->format('%R%a');
            $transactionDate = $this->dateTime;
            $payementMadeDt = date('Y-m-d');
            if ($datesCount > 0) {
                $transactionDate = $billInfo[0]['bill_date'] . ' 00:00:00';
                $payementMadeDt = $billInfo[0]['bill_date'];
            }
            //---------------//
            $paymentMade = array();
            if ($excessAmount > 0) {
                $paymentMadeTranDtTm = $transactionDate;
                $payementDt = $payementMadeDt;
                if ($billInfo[0]['payment_date']) {
                    $paymentMadeTranDtTm = $billInfo[0]['payment_date'] . ' 00:00:00';
                    $payementDt = $billInfo[0]['payment_date'];
                }

                // due to excessAmount is greater than 0, this amount has made a Payment Made
                $paymentMade['vendor'] = $billInfo[0]['vendor'];
                $paymentMade['amount'] = $excessAmount;
                $paymentMade['payment_date'] = $payementDt;
                $paymentMade['payment_mode'] = 'cash_pay_mode';
                $paymentMade['paid_through'] = UNDEPOSITED_FUNDS;
                $paymentMade['bill'] = $billCode;
                $paymentMade['payment_made_code'] = PAYMENT_MADE_CODE . getCode(PAYMENT_MADE_CODE);
                $paymentMade['created_by'] = $this->user;
                $paymentMade['created_dt_tm'] = $this->dateTime;
                $paymentMade['updated_by'] = $this->user;
                $paymentMade['updated_dt_tm'] = $this->dateTime;

                //--------- transaction table insert -------------------//
                //  Undeposit Funds  debit  -------//
                $transactionGroupId = reference_no();
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['bill'] = $billCode;
                $transaction['payment_made'] = $paymentMade['payment_made_code'];
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $billInfo[0]['vendor'];
                $transaction['contact_type'] = VENDOR;
                $transaction['account'] = UNDEPOSITED_FUNDS; ///////////////UNDEPOSITED_FUNDS = credit
                $transaction['credit_amount'] = $excessAmount;
                $transaction['debit_amount'] = '0.00';
                $transaction['transaction_type'] = CREDIT;
                $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
                $transaction['reference_no'] = NULL;
                $transaction['tarn_dt_tm'] = $paymentMadeTranDtTm;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;

                // ----------- Unearned Revenue credit  -------//
                $transaction['transaction_group_id'] = $transactionGroupId;
                $transaction['bill'] = $billCode;
                $transaction['payment_made'] = $paymentMade['payment_made_code'];
                $transaction['transaction_id'] = reference_no();
                $transaction['contact_code'] = $billInfo[0]['vendor'];
                $transaction['contact_type'] = VENDOR;
                $transaction['account'] = PREPAID_EXPENSE; ////////////////////////PREPAID EXPENSE WILL BE DEBIT
                $transaction['credit_amount'] = '0.00';
                $transaction['debit_amount'] = $excessAmount;
                $transaction['transaction_type'] = DEBIT;
                $transaction['transaction_for'] = PAYMENT_MADE_FOR;  // new correction
                $transaction['reference_no'] = NULL;
                $transaction['tarn_dt_tm'] = $paymentMadeTranDtTm;
                $transaction['created_by'] = $this->user;
                $transaction['created_dt_tm'] = $this->dateTime;
                $transaction['updated_by'] = $this->user;
                $transaction['updated_dt_tm'] = $this->dateTime;
                $transactonArr[] = $transaction;
            }

            //----------- transaction for bill payment ----------//
            // Unearned Revenue Debit of paymentAmount
            $transactionGroupId = reference_no();
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['bill'] = $billCode;
            $transaction['payment_made'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $billInfo[0]['vendor'];
            $transaction['contact_type'] = VENDOR;
            $transaction['account'] = PREPAID_EXPENSE;
            $transaction['credit_amount'] = $paymentAmount;
            $transaction['debit_amount'] = '0.00';
            $transaction['transaction_type'] = CREDIT;
            $transaction['transaction_for'] = BILL_PAYMENT_FOR;  // new correction
            $transaction['reference_no'] = $purchaseBillArr['payment_tran_ref'];
            $transaction['tarn_dt_tm'] = $transactionDate;
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            ////////////////// Account paybale debit of payment amount
            $transaction['transaction_group_id'] = $transactionGroupId;
            $transaction['bill'] = $billCode;
            $transaction['payment_made'] = NULL;
            $transaction['transaction_id'] = reference_no();
            $transaction['contact_code'] = $billInfo[0]['vendor'];
            $transaction['contact_type'] = VENDOR;
            $transaction['account'] = ACCOUNT_PAYABLE;
            $transaction['credit_amount'] = '0.00';
            $transaction['debit_amount'] = $paymentAmount;
            $transaction['transaction_type'] = DEBIT;
            $transaction['transaction_for'] = BILL_PAYMENT_FOR;  // new correction
            $transaction['reference_no'] = $purchaseBillArr['payment_tran_ref'];
            $transaction['tarn_dt_tm'] = $transactionDate;
            $transaction['created_by'] = $this->user;
            $transaction['created_dt_tm'] = $this->dateTime;
            $transaction['updated_by'] = $this->user;
            $transaction['updated_dt_tm'] = $this->dateTime;
            $transactonArr[] = $transaction;

            $this->Purchase_model->makeBillPayment($purchaseBillArr, $contactArr, $paymentMade, $transactonArr, $billCode, $dbPaymentTranRef, $billInfo[0]['vendor'], $billInfo[0]['payment_made_code']);
            redirect('Purchase/showBillPaymentDetails?response=1&bill=' . $billCode);
        } else {
            redirect('Purchase/billPayment');
        }
    }

   public function checkBillPayment() {
        $billCode = $this->input->post('billCode', true);
        $paymentAmount = (float) trim($this->input->post('paymentAmount', true));
        $arr['billCode'] = $billCode;
        $billInfo = $this->Purchase_model->getBillDetails($arr);
        if ($billInfo && $paymentAmount > 0) {
            $billAmount = $billInfo[0]['total'];
            if ($paymentAmount > $billAmount) {
                echo 3;
                exit();
            }
            $newUsedBalance = $billInfo[0]['used_balance'] - $billInfo[0]['paid_amount'] + $paymentAmount;
            $excessAmount = 0;
            if ($billInfo[0]['total_balance'] < $newUsedBalance) {
                $excessAmount = $newUsedBalance - $billInfo[0]['total_balance'];
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
        $datetime2 = date_create('2019-05-08');
        $interval = date_diff($datetime1, $datetime2);
        echo (int) $interval->format('%R%a');

        //        $x = '-5';
        //        echo (float) ($x);
        //$this->load->view('testView');
    }

}
