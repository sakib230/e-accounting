<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Purchase_model extends CI_Model {

    function getBillDetails($arr) {
        $this->db->select('purchase_bill.*,contact.contact_name,contact.mobile_no,contact.email,contact.address,contact.company_name,contact.total_balance,contact.used_balance');
        $this->db->from('purchase_bill');
        $this->db->join('contact', 'contact.contact_code = purchase_bill.vendor');
        if ($arr['status']) {
            $this->db->where('purchase_bill.status', $arr['status']);
        }
        if ($arr['billCode']) {
            $this->db->where('purchase_bill.bill_code', $arr['billCode']);
        }
        if ($arr['vendor']) {
            $this->db->where('purchase_bill.vendor', $arr['vendor']);
        }
        $this->db->order_by('created_dt_tm', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getVendor() {
        $this->db->where('contact_type', VENDOR);
        $this->db->where('is_active', 1);
        $this->db->order_by('contact_name', 'ASC');
        $query = $this->db->get('contact');
        return $query->result_array();
    }
    
    function getPurchaseItem() {
        $this->db->select('item.*,tax.title as tax_title,tax.rate as tax_rate');
        $this->db->from('item');
        $this->db->join('tax', 'tax.tax_code = item.purchase_tax', 'left');
        $this->db->where('item.is_active', 1);
        $this->db->order_by('item.title', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getPurchaseItemAccount($itemArr) {
        $this->db->select('purchase_account');
        $this->db->where_in('item_code', $itemArr);
        $query = $this->db->get('item');
        return $query->result_array();
    }

    function addNewBill($purchaseBill, $purchaseBillItemInsertArr, $transactionInsertArr) {
        if ($purchaseBill) {
            $this->db->insert('purchase_bill', $purchaseBill);
        }
        if ($purchaseBillItemInsertArr) {
            $this->db->insert_batch('purchase_bill_item', $purchaseBillItemInsertArr);
        }
        if ($transactionInsertArr) {
            $this->db->insert_batch('transaction', $transactionInsertArr);
        }
    }
    
    function getBillItemDetails($arr) {
        $this->db->select('purchase_bill_item.*,item.item_type,item.title as item_title,tax.title as tax_title');
        $this->db->from('purchase_bill_item');
        $this->db->join('item', 'item.item_code = purchase_bill_item.item');
        $this->db->join('tax', 'tax.tax_code = purchase_bill_item.tax_code', 'left');
        $this->db->where('purchase_bill_item.bill_code', $arr['billCode']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function editBill($purchaseBill, $purchaseBillItemInsertArr, $transactionInsertArr, $tranRefNo) {
        if ($purchaseBill) {
            $this->db->where('bill_code', $purchaseBill['bill_code']);
            $this->db->update('purchase_bill', $purchaseBill);
        }

        $this->db->where('bill_code', $purchaseBill['bill_code']);
        $this->db->delete('purchase_bill_item');

        $this->db->where('bill', $purchaseBill['bill_code']);
        $this->db->where('reference_no', $tranRefNo);
        $this->db->delete('transaction');

        if ($purchaseBillItemInsertArr) {
            $this->db->insert_batch('purchase_bill_item', $purchaseBillItemInsertArr);
        }
        if ($transactionInsertArr) {
            $this->db->insert_batch('transaction', $transactionInsertArr);
        }
    }
    
    function getPaymentDetails($arr) {
        $this->db->select('payment_made.*,contact.contact_name,contact.company_name,contact.address,contact.mobile_no,contact.email, contact.total_balance,contact.used_balance,dictionary_table.title as payment_mode_title,chart_of_account.account_title as paid_through_title');
        $this->db->from('payment_made');
        $this->db->join('contact', 'contact.contact_code = payment_made.vendor');
        $this->db->join('dictionary_table', 'dictionary_table.title_code = payment_made.payment_mode');
        $this->db->join('chart_of_account', 'chart_of_account.account_code = payment_made.paid_through');
        if ($arr['paymentCode']) {
            $this->db->where('payment_made.payment_made_code', $arr['paymentCode']);
        }
        $this->db->order_by('created_dt_tm', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getPaymentMode() {
        $this->db->select('title as payment_mode,title_code as payment_mode_code');
        $this->db->where('title_type', 'payment_mode');
        $this->db->where('is_active', 1);
        $this->db->order_by('title', 'ASC');
        $query = $this->db->get('dictionary_table');
        return $query->result_array();
    }

    function addPaymentMade($paymentMade, $transactonArr) {
        $this->db->insert('payment_made', $paymentMade);
        $this->db->insert_batch('transaction', $transactonArr);

        $totalBalanceUpdateQuery = "UPDATE `contact` SET `total_balance` =  `total_balance` +" . $paymentMade['amount'] . " , `updated_by` = '" . $this->user . "' , `updated_dt_tm` = '" . $this->dateTime . "' WHERE `contact_code` = " . $paymentMade['vendor'];
        $this->db->query($totalBalanceUpdateQuery);
    }

    function editPaymentMade($paymentMade, $transactonArr, $vendor, $vendorUpdateAmount) {
        $this->db->where('payment_made', $paymentMade['payment_made_code']);
        $this->db->delete('transaction');

        $this->db->where('payment_made_code', $paymentMade['payment_made_code']);
        $this->db->update('payment_made', $paymentMade);

        $this->db->insert_batch('transaction', $transactonArr);

        $totalBalanceUpdateQuery = "UPDATE `contact` SET `total_balance` = `total_balance` +" . $vendorUpdateAmount . " , `updated_by` = '" . $this->user . "' , `updated_dt_tm` = '" . $this->dateTime . "' WHERE `contact_code` = " . $vendor;
        $this->db->query($totalBalanceUpdateQuery);
    }

    function getBillDetailsForPayment($arr) {
        $this->db->select('purchase_bill.*,contact.contact_name,contact.mobile_no,contact.email,contact.address,contact.company_name,contact.total_balance,contact.used_balance,payment_made.amount as payment_made_amount,payment_made.payment_made_code');
        $this->db->from('purchase_bill');
        $this->db->join('contact', 'contact.contact_code = purchase_bill.vendor');
        $this->db->join('payment_made', 'payment_made.bill = purchase_bill.bill_code', 'left');
        $this->db->where('purchase_bill.bill_code', $arr['billCode']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function makeBillPayment($purchaseBillArr, $contactArr, $paymentMade, $transactonArr, $billCode, $dbPaymentTranRef, $vendor, $paymentMadeCode) {
        $this->db->where('bill_code', $billCode);
        $this->db->update('purchase_bill', $purchaseBillArr);

        $this->db->where('contact_code', $vendor);
        $this->db->update('contact', $contactArr);

        if ($paymentMadeCode) {
            $this->db->where('payment_made_code', $paymentMadeCode);
            $this->db->delete('payment_made');

            $this->db->where('payment_made', $paymentMadeCode);
            $this->db->delete('transaction');
        }
        
        if ($paymentMade) {
            $this->db->insert('payment_made', $paymentMade);
        }

        if ($dbPaymentTranRef) {
            $this->db->where('reference_no', $dbPaymentTranRef);
            $this->db->where('bill', $billCode);
            $this->db->delete('transaction');
        }

        if ($transactonArr) {
            $this->db->insert_batch('transaction', $transactonArr);
        }
    }
}
