<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts_model extends CI_Model {

    function getChartAccount($arr = array()) {
        $this->db->select('chart_of_account.*');
        $this->db->from('chart_of_account');
        if ($arr['isActive']) {
            $this->db->where('chart_of_account.is_active', $arr['isActive']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function getTax($arr = array()) {
        $this->db->select('tax.*');
        $this->db->from('tax');
        if ($arr['taxCode']) {
            $this->db->where('tax.tax_code', $arr['taxCode']);
        }
        if ($arr['isActive']) {
            $this->db->where('tax.is_active', $arr['isActive']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function taxDuplicateCheck($arr = array()) {
        if ($arr['addEditFlag'] == 'edit') {
            $this->db->where_not_in('tax_code', $arr['taxCode']);
        }
        $this->db->where('title', $arr['name']);
        $query = $this->db->get('tax');
        if ($query->num_rows() > 0) {
            return 2;
        }
        return 1;
    }
    
    function addTax($taxInfo = array()) {
        $arr['name'] = $taxInfo['name'];
        $arr['addEditFlag'] = 'add';
        $duplicateFlag = $this->taxDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->insert('tax', $taxInfo);
        
        return 1;
    }
    
    function editTax($taxInfo) {
        $arr['name'] = $taxInfo['name'];
        $arr['addEditFlag'] = 'edit';
        $duplicateFlag = $this->taxDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->where('tax_code', $taxInfo['tax_code']);
        $this->db->update('tax', $taxInfo);
        
        return 1;
    }
}
