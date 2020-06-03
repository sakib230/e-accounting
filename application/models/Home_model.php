<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home_model extends CI_Model {

    function getDashboardCountValues() {
        $fromDate = date("Y-m-01", strtotime(date('Y-m-d')));
        $toDate = date("Y-m-t", strtotime(date('Y-m-d')));
        //---------- customer -----------------//
        $this->db->select('COUNT(id) as total_customer');
        $this->db->where('contact_type', CUSTOMER);
        $this->db->where('is_active', 1);
        $query = $this->db->get('contact');
        $responseArr['total_customer'] = $query->row()->total_customer;

        $this->db->select('COUNT(id) as total_this_month');
        $this->db->where('contact_type', CUSTOMER);
        $this->db->where('is_active', 1);
        $this->db->where('created_dt_tm >=', $fromDate . ' 00:00:00');
        $this->db->where('created_dt_tm <=', $toDate . ' 11:59:59');
        $query = $this->db->get('contact');
        $responseArr['total_this_month_customer'] = $query->row()->total_this_month;

        $responseArr['customer_percentage'] = ($responseArr['total_customer'] / $responseArr['total_this_month_customer']) * 100;

        //---------- vendor -----------------//
        $this->db->select('COUNT(id) as total_vendor');
        $this->db->where('contact_type', VENDOR);
        $this->db->where('is_active', 1);
        $query = $this->db->get('contact');
        $responseArr['total_vendor'] = $query->row()->total_vendor;

        $this->db->select('COUNT(id) as total_this_month');
        $this->db->where('contact_type', VENDOR);
        $this->db->where('is_active', 1);
        $this->db->where('created_dt_tm >=', $fromDate . ' 00:00:00');
        $this->db->where('created_dt_tm <=', $toDate . ' 11:59:59');
        $query = $this->db->get('contact');
        $responseArr['total_this_month_vendor'] = $query->row()->total_this_month;

        $responseArr['vendor_percentage'] = ($responseArr['total_vendor'] / $responseArr['total_this_month_vendor']) * 100;

        //-------------- item --------------//
        $this->db->select('COUNT(id) as product_count');
        $this->db->where('item_type', PRODUCT);
        $this->db->where('is_active', 1);
        $query = $this->db->get('item');
        $responseArr['total_product'] = $query->row()->product_count;

        $this->db->select('COUNT(id) as service_count');
        $this->db->where('item_type', SERVICE);
        $this->db->where('is_active', 1);
        $query = $this->db->get('item');
        $responseArr['total_service'] = $query->row()->service_count;

        //-------------- sale invoice ----------//
        $this->db->select('COUNT(id) as invoice_count');
        $this->db->where('created_dt_tm >=', $fromDate . ' 00:00:00');
        $this->db->where('created_dt_tm <=', $toDate . ' 11:59:59');
        $query = $this->db->get('sale_invoice');
        $responseArr['invoice_count'] = $query->row()->invoice_count;

        //-------------- purchase bill ----------//
        $this->db->select('COUNT(id) as bill_count');
        $this->db->where('created_dt_tm >=', $fromDate . ' 00:00:00');
        $this->db->where('created_dt_tm <=', $toDate . ' 11:59:59');
        $query = $this->db->get('purchase_bill');
        $responseArr['bill_count'] = $query->row()->bill_count;

        return $responseArr;
    }

    function getReceivableValues() {
        //---------- calculate overdue ---------//
        $this->db->select('SUM(total) as total_amount, SUM(paid_amount) as paid_amount');
        $this->db->where('due_date <', date('Y-m-d'));
        $query = $this->db->get('sale_invoice');
        $totalAmount = $query->row()->total_amount;
        $paidAmount = $query->row()->paid_amount;
        $response['overDue'] = $totalAmount - $paidAmount;

        //----------- calculate due -------------//
        $this->db->select('SUM(total) as total_amount, SUM(paid_amount) as paid_amount');
        $this->db->where('due_date >=', date('Y-m-d'));
        $query = $this->db->get('sale_invoice');
        $totalAmount = $query->row()->total_amount;
        $paidAmount = $query->row()->paid_amount;
        $response['due'] = $totalAmount - $paidAmount;
        return $response;
    }
    
    function getPayableValues() {
        //---------- calculate overdue ---------//
        $this->db->select('SUM(total) as total_amount, SUM(paid_amount) as paid_amount');
        $this->db->where('due_date <', date('Y-m-d'));
        $query = $this->db->get('purchase_bill');
        $totalAmount = $query->row()->total_amount;
        $paidAmount = $query->row()->paid_amount;
        $response['overDue'] = $totalAmount - $paidAmount;

        //----------- calculate due -------------//
        $this->db->select('SUM(total) as total_amount, SUM(paid_amount) as paid_amount');
        $this->db->where('due_date >=', date('Y-m-d'));
        $query = $this->db->get('purchase_bill');
        $totalAmount = $query->row()->total_amount;
        $paidAmount = $query->row()->paid_amount;
        $response['due'] = $totalAmount - $paidAmount;
        return $response;
    }

    function getSalesValueMonth() {
        $this->db->select('SUM(total) as amount,MONTH(invoice_date) as month');
        $this->db->group_by('MONTH(invoice_date)');
        $this->db->order_by('MONTH(invoice_date)', 'ASC');
        $query = $this->db->get('sale_invoice');
        return $query->result_array();
    }

    function getPurchaseValueMonth() {
        $this->db->select('SUM(total) as amount,MONTH(bill_date) as month');
        $this->db->group_by('MONTH(bill_date)');
        $this->db->order_by('MONTH(bill_date)', 'ASC');
        $query = $this->db->get('purchase_bill');
        return $query->result_array();
    }

}
