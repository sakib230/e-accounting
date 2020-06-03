<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Items_model extends CI_Model {

    function getItem($arr = array()) {
        $this->db->select('item.*, sale_chart_of_account.account_title as sale_account_name, purchase_chart_of_account.account_title as purchase_account_name, '
                . 'sale_tax.title as sale_tax_name, purchase_tax.title as purchase_tax_name');
        $this->db->from('item');
        $this->db->join('chart_of_account as sale_chart_of_account', 'sale_chart_of_account.account_code = item.sale_account', 'left');
        $this->db->join('chart_of_account as purchase_chart_of_account', 'purchase_chart_of_account.account_code = item.purchase_account', 'left');
        $this->db->join('tax as sale_tax', 'sale_tax.tax_code = item.sale_tax', 'left');
        $this->db->join('tax as purchase_tax', 'purchase_tax.tax_code = item.purchase_tax', 'left');
        if ($arr['itemCode']) {
            $this->db->where('item.item_code', $arr['itemCode']);
        }
        if ($arr['isActive']) {
            $this->db->where('item.is_active', $arr['isActive']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function itemDuplicateCheck($arr) {
        if ($arr['addEditFlag'] == 'edit') {
            $this->db->where_not_in('item_code', $arr['itemCode']);
        }
        $this->db->where('title', $arr['itemName']);
        $this->db->where('item_type', $arr['itemType']);
        $query = $this->db->get('item');
        if ($query->num_rows() > 0) {
            return 2;
        }
        return 1;
    }
    
    function addItem($itemInfo) {
        $arr['itemName'] = $itemInfo['itemName'];
        $arr['itemType'] = $itemInfo['itemType'];
        $arr['addEditFlag'] = 'add';
        $duplicateFlag = $this->itemDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->insert('item', $itemInfo);
        return 1;
    }
    
    function editItem($itemInfo) {
        $arr['itemName'] = $itemInfo['itemName'];
        $arr['itemType'] = $itemInfo['itemType'];
        $arr['addEditFlag'] = 'edit';
        $duplicateFlag = $this->itemDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->where('item_code', $itemInfo['item_code']);
        $this->db->update('item', $itemInfo);
        return 1;
    }
}
