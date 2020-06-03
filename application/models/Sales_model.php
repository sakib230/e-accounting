<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sales_model extends CI_Model {

    function getCustomer() {
        $this->db->where('contact_type', CUSTOMER);
        $this->db->where('is_active', 1);
        $this->db->order_by('contact_name', 'ASC');
        $query = $this->db->get('contact');
        return $query->result_array();
    }

    function getSaleItem() {
        $this->db->select('item.*,tax.title as tax_title,tax.rate as tax_rate');
        $this->db->from('item');
        $this->db->join('tax', 'tax.tax_code = item.sale_tax', 'left');
        $this->db->where('item.is_active', 1);
        $this->db->order_by('item.title', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getSaleItemAccount($itemArr) {
        $this->db->select('sale_account');
        $this->db->where_in('item_code', $itemArr);
        $query = $this->db->get('item');
        return $query->result_array();
    }

    function addNewInvoice($saleInvoice, $saleInvoiceItemInsertArr, $transactionInsertArr) {
        if ($saleInvoice) {
            $this->db->insert('sale_invoice', $saleInvoice);
        }
        if ($saleInvoiceItemInsertArr) {
            $this->db->insert_batch('sale_invoice_item', $saleInvoiceItemInsertArr);
        }
        if ($transactionInsertArr) {
            $this->db->insert_batch('transaction', $transactionInsertArr);
        }
    }

    function editInvoice($saleInvoice, $saleInvoiceItemInsertArr, $transactionInsertArr, $tranRefNo) {
        if ($saleInvoice) {
            $this->db->where('invoice_code', $saleInvoice['invoice_code']);
            $this->db->update('sale_invoice', $saleInvoice);
        }

        $this->db->where('invoice_code', $saleInvoice['invoice_code']);
        $this->db->delete('sale_invoice_item');

        $this->db->where('invoice', $saleInvoice['invoice_code']);
        $this->db->where('reference_no', $tranRefNo);
        $this->db->delete('transaction');

        if ($saleInvoiceItemInsertArr) {
            $this->db->insert_batch('sale_invoice_item', $saleInvoiceItemInsertArr);
        }
        if ($transactionInsertArr) {
            $this->db->insert_batch('transaction', $transactionInsertArr);
        }
    }

    function getInvoiceDetails($arr) {
        $this->db->select('sale_invoice.*,contact.contact_name,contact.mobile_no,contact.email,contact.address,contact.company_name,contact.total_balance,contact.used_balance');
        $this->db->from('sale_invoice');
        $this->db->join('contact', 'contact.contact_code = sale_invoice.customer');
        if ($arr['status']) {
            $this->db->where('sale_invoice.status', $arr['status']);
        }
        if ($arr['invoiceCode']) {
            $this->db->where('sale_invoice.invoice_code', $arr['invoiceCode']);
        }
        if ($arr['customer']) {
            $this->db->where('sale_invoice.customer', $arr['customer']);
        }
        $this->db->order_by('created_dt_tm', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getInvoiceItemDetails($arr) {
        $this->db->select('sale_invoice_item.*,item.item_type,item.title as item_title,tax.title as tax_title');
        $this->db->from('sale_invoice_item');
        $this->db->join('item', 'item.item_code = sale_invoice_item.item');
        $this->db->join('tax', 'tax.tax_code = sale_invoice_item.tax_code', 'left');
        $this->db->where('sale_invoice_item.invoice_code', $arr['invoiceCode']);
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

    function addPaymentReceived($paymentReceive, $transactonArr) {
        $this->db->insert('payment_receive', $paymentReceive);
        $this->db->insert_batch('transaction', $transactonArr);

        $totalBalanceUpdateQuery = "UPDATE `contact` SET `total_balance` =  `total_balance` +" . $paymentReceive['amount'] . " , `updated_by` = '" . $this->user . "' , `updated_dt_tm` = '" . $this->dateTime . "' WHERE `contact_code` = " . $paymentReceive['customer'];
        $this->db->query($totalBalanceUpdateQuery);
    }

    function editPaymentReceived($paymentReceive, $transactonArr, $customer, $customerUpdateAmount) {
        $this->db->where('payment_receive', $paymentReceive['payment_receive_code']);
        $this->db->delete('transaction');

        $this->db->where('payment_receive_code', $paymentReceive['payment_receive_code']);
        $this->db->update('payment_receive', $paymentReceive);

        $this->db->insert_batch('transaction', $transactonArr);

        $totalBalanceUpdateQuery = "UPDATE `contact` SET `total_balance` =  `total_balance` +" . $customerUpdateAmount . " , `updated_by` = '" . $this->user . "' , `updated_dt_tm` = '" . $this->dateTime . "' WHERE `contact_code` = " . $customer;
        $this->db->query($totalBalanceUpdateQuery);
    }

    function getPaymentDetails($arr = array()) {
        $this->db->select('payment_receive.*,contact.contact_name,contact.company_name,contact.address,contact.mobile_no,contact.email, contact.total_balance,contact.used_balance,dictionary_table.title as payment_mode_title,chart_of_account.account_title as deposit_to_title');
        $this->db->from('payment_receive');
        $this->db->join('contact', 'contact.contact_code = payment_receive.customer');
        $this->db->join('dictionary_table', 'dictionary_table.title_code = payment_receive.payment_mode');
        $this->db->join('chart_of_account', 'chart_of_account.account_code = payment_receive.deposit_to');
        if ($arr['paymentCode']) {
            $this->db->where('payment_receive.payment_receive_code', $arr['paymentCode']);
        }
        $this->db->order_by('created_dt_tm', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getInvoiceDetailsForPayment($arr) {
        $this->db->select('sale_invoice.*,contact.contact_name,contact.mobile_no,contact.email,contact.address,contact.company_name,contact.total_balance,contact.used_balance,payment_receive.amount as payment_receive_amount,payment_receive.payment_receive_code,payment_receive.payment_date');
        $this->db->from('sale_invoice');
        $this->db->join('contact', 'contact.contact_code = sale_invoice.customer');
        $this->db->join('payment_receive', 'payment_receive.invoice = sale_invoice.invoice_code', 'left');
        $this->db->where('sale_invoice.invoice_code', $arr['invoiceCode']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function makeInvoicePayment($saleInvoiceArr, $contactArr, $paymentReceive, $transactonArr, $invoiceCode, $dbPaymentTranRef, $customer, $paymentReceiveCode) {
        $this->db->where('invoice_code', $invoiceCode);
        $this->db->update('sale_invoice', $saleInvoiceArr);

        $this->db->where('contact_code', $customer);
        $this->db->update('contact', $contactArr);

        if ($paymentReceiveCode) {
            $this->db->where('payment_receive_code', $paymentReceiveCode);
            $this->db->delete('payment_receive');

            $this->db->where('payment_receive', $paymentReceiveCode);
            $this->db->delete('transaction');
        }

        if ($paymentReceive) {
            $this->db->insert('payment_receive', $paymentReceive);
        }

        if ($dbPaymentTranRef) {
            $this->db->where('reference_no', $dbPaymentTranRef);
            $this->db->where('invoice', $invoiceCode);
            $this->db->delete('transaction');
        }

        if ($transactonArr) {
            $this->db->insert_batch('transaction', $transactonArr);
        }
    }

}
