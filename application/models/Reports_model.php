<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports_model extends CI_Model {

    function getSalesByItem($arr) {
        $this->db->select('SUM(sale_invoice_item.quantity * sale_invoice_item.rate) as amount,SUM(sale_invoice_item.quantity) as quantity_sold,item.item_type,item.title as item_title,item.sale_rate,item.unit_name as sale_unit');
        $this->db->from('sale_invoice_item');
        $this->db->join('item', 'item.item_code = sale_invoice_item.item');
        $this->db->join('sale_invoice', 'sale_invoice.invoice_code = sale_invoice_item.invoice_code');
        if ($arr['item']) {
            $this->db->where_in('sale_invoice_item.item', $arr['item']);
        }
        if ($arr['customer']) {
            $this->db->where_in('sale_invoice.customer', $arr['customer']);
        }
        $this->db->where('sale_invoice.invoice_date >=', $arr['fromDate']);
        $this->db->where('sale_invoice.invoice_date <=', $arr['toDate']);
        $this->db->group_by('sale_invoice_item.item');
        $this->db->order_by('item.title', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getSalesByCustomer($arr) {
        $this->db->select('SUM(sale_invoice.total) as sales_amount,COUNT(sale_invoice.id) as invoice_count,contact.contact_name as customer_name,contact.mobile_no, sale_invoice.customer as customer_code');
        $this->db->from('sale_invoice');
        $this->db->join('contact', 'contact.contact_code = sale_invoice.customer');
        if ($arr['customer']) {
            $this->db->where_in('sale_invoice.customer', $arr['customer']);
        }
        $this->db->where('sale_invoice.invoice_date >=', $arr['fromDate']);
        $this->db->where('sale_invoice.invoice_date <=', $arr['toDate']);
        $this->db->group_by('sale_invoice.customer');
        $this->db->order_by('contact.contact_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getInvoiceDetails($arr) {
        $this->db->select('sale_invoice.invoice_code,sale_invoice.customer as customer_code,sale_invoice.display_reference_no,sale_invoice.invoice_date,sale_invoice.due_date,sale_invoice.total as invoice_amount,sale_invoice.paid_amount,sale_invoice.status,contact.contact_name as customer_name');
        $this->db->from('sale_invoice');
        $this->db->join('contact', 'contact.contact_code = sale_invoice.customer');
        $this->db->where('sale_invoice.invoice_date >=', $arr['fromDate']);
        $this->db->where('sale_invoice.invoice_date <=', $arr['toDate']);

        if ($arr['customer']) {
            $this->db->where_in('sale_invoice.customer', $arr['customer']);
        }
        if ($arr['invoiceDate']) {
            $this->db->where_in('sale_invoice.invoice_date', $arr['invoiceDate']);
        }
        if ($arr['dueDate']) {
            $this->db->where_in('sale_invoice.due_date', $arr['dueDate']);
        }
        if ($arr['invoiceCode']) {
            $this->db->where_in('sale_invoice.invoice_code', $arr['invoiceCode']);
        }
        if ($arr['status']) {
            $this->db->where_in('sale_invoice.status', $arr['status']);
        }
        $this->db->order_by('sale_invoice.invoice_date');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getGeneralLedgerDetails($arr) {
        $transactionTempTable = 'transaction_' . reference_no();

        $this->db->select('transaction.account,SUM(transaction.credit_amount) as credit,SUM(transaction.debit_amount) as debit');
        $this->db->where('transaction.tarn_dt_tm >=', $arr['fromDate'] . ' 00:00:00');
        $this->db->where('transaction.tarn_dt_tm <=', $arr['toDate'] . ' 23:59:59');
        $this->db->group_by('transaction.account');
        $sqlTransaction = $this->db->get_compiled_select('transaction');
        $this->db->query("CREATE TEMPORARY TABLE " . $transactionTempTable . " " . $sqlTransaction);

        $this->db->select('chart_of_account.account_title,' . $transactionTempTable . '.*');
        $this->db->from('chart_of_account');
        $this->db->join($transactionTempTable, $transactionTempTable . '.account = chart_of_account.account_code', 'left');
        $this->db->order_by('chart_of_account.account_title', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getDistinctTranGroups($arr) {
        $this->db->select('transaction_group_id');
        $this->db->where('transaction.tarn_dt_tm >=', $arr['fromDate'] . ' 00:00:00');
        $this->db->where('transaction.tarn_dt_tm <=', $arr['toDate'] . ' 23:59:59');
        $this->db->from('transaction');
        $this->db->distinct();
        $this->db->order_by('tarn_dt_tm','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getJournalDetails($arr) {
        $this->db->select('transaction.*,SUM(transaction.debit_amount) as debit,SUM(transaction.credit_amount) as credit,chart_of_account.account_title');
        $this->db->from('transaction');
        $this->db->join('chart_of_account', 'chart_of_account.account_code = transaction.account');
        $this->db->where('transaction.tarn_dt_tm >=', $arr['fromDate'] . ' 00:00:00');
        $this->db->where('transaction.tarn_dt_tm <=', $arr['toDate'] . ' 23:59:59');
        $this->db->group_by('transaction.transaction_group_id');
        $this->db->group_by('transaction.account');
        $this->db->order_by('transaction.tarn_dt_tm','ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getPurchaseByItem($arr) {
        $this->db->select('SUM(purchase_bill_item.quantity * purchase_bill_item.rate) as amount, SUM(purchase_bill_item.quantity) as quantity_bought, item.item_type, item.title as item_title, item.purchase_rate, item.unit_name as purchase_unit');
        $this->db->from('purchase_bill_item');
        $this->db->join('item', 'item.item_code = purchase_bill_item.item');
        $this->db->join('purchase_bill', 'purchase_bill.bill_code = purchase_bill_item.bill_code');
        if ($arr['item']) {
            $this->db->where_in('purchase_bill_item.item', $arr['item']);
        }
        if ($arr['vendor']) {
            $this->db->where_in('purchase_bill.vendor', $arr['vendor']);
        }
        $this->db->where('purchase_bill.bill_date >=', $arr['fromDate']);
        $this->db->where('purchase_bill.bill_date <=', $arr['toDate']);
        $this->db->group_by('purchase_bill_item.item');
        $this->db->order_by('item.title', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getPurchaseByVendor($arr) {
        $this->db->select('SUM(purchase_bill.total) as purchase_amount,COUNT(purchase_bill.id) as bill_count, contact.contact_name as vendor_name, contact.mobile_no, purchase_bill.vendor as vendor_code');
        $this->db->from('purchase_bill');
        $this->db->join('contact', 'contact.contact_code = purchase_bill.vendor');
        if ($arr['vendor']) {
            $this->db->where_in('purchase_bill.vendor', $arr['vendor']);
        }
        $this->db->where('purchase_bill.bill_date >=', $arr['fromDate']);
        $this->db->where('purchase_bill.bill_date <=', $arr['toDate']);
        $this->db->group_by('purchase_bill.vendor');
        $this->db->order_by('contact.contact_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getBillDetails($arr) {
        $this->db->select('purchase_bill.bill_code, purchase_bill.vendor as vendor_code, purchase_bill.display_reference_no, purchase_bill.bill_date, purchase_bill.due_date, purchase_bill.total as bill_amount, purchase_bill.paid_amount, purchase_bill.status, contact.contact_name as vendor_name');
        $this->db->from('purchase_bill');
        $this->db->join('contact', 'contact.contact_code = purchase_bill.vendor');
        $this->db->where('purchase_bill.bill_date >=', $arr['fromDate']);
        $this->db->where('purchase_bill.bill_date <=', $arr['toDate']);

        if ($arr['vendor']) {
            $this->db->where_in('purchase_bill.vendor', $arr['vendor']);
        }
        if ($arr['billDate']) {
            $this->db->where_in('purchase_bill.bill_date', $arr['billDate']);
        }
        if ($arr['dueDate']) {
            $this->db->where_in('purchase_bill.due_date', $arr['dueDate']);
        }
        if ($arr['billCode']) {
            $this->db->where_in('purchase_bill.bill_code', $arr['billCode']);
        }
        if ($arr['status']) {
            $this->db->where_in('purchase_bill.status', $arr['status']);
        }
        $this->db->order_by('purchase_bill.bill_date');
        $query = $this->db->get();
        return $query->result_array();
    }
}
