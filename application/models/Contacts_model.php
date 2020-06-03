<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contacts_model extends CI_Model {

    function getCustomer($arr = array()) {
        $this->db->select('contact.*');
        $this->db->from('contact');
        $this->db->where('contact_type', CUSTOMER);
        if ($arr['customerId']) {
            $this->db->where('contact.contact_code', $arr['customerId']);
        }
        if ($arr['isActive']) {
            $this->db->where('contact.is_active', $arr['isActive']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function customerDuplicateCheck($arr) {
        if ($arr['addEditFlag'] == 'edit') {
            $this->db->where_not_in('contact_code', $arr['customerId']);
        }
        $this->db->where('contact_name', $arr['fullName']);
        $this->db->where('mobile_no', $arr['mobileNo']);
        $this->db->where('contact_type', CUSTOMER);
        $query = $this->db->get('contact');
        if ($query->num_rows() > 0) {
            return 2;
        }
        return 1;
    }
    
    function addCustomer($contactInfo) {
        $arr['fullName'] = $contactInfo['full_name'];
        $arr['mobileNo'] = $contactInfo['mobile_no'];
        $arr['addEditFlag'] = 'add';
        $duplicateFlag = $this->customerDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->insert('contact', $contactInfo);
        
        return 1;
    }
    
    function editCustomer($contactInfo) {
        $arr['fullName'] = $contactInfo['full_name'];
        $arr['mobileNo'] = $contactInfo['mobile_no'];
        $arr['addEditFlag'] = 'edit';
        $duplicateFlag = $this->customerDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->where('contact_code', $contactInfo['contact_code']);
        $this->db->where('contact_type', CUSTOMER);
        $this->db->update('contact', $contactInfo);
        
        return 1;
    }
    
    function getVendor($arr = array()) {
        $this->db->select('contact.*');
        $this->db->from('contact');
        $this->db->where('contact_type', VENDOR);
        if ($arr['vendorId']) {
            $this->db->where('contact.contact_code', $arr['vendorId']);
        }
        if ($arr['isActive']) {
            $this->db->where('contact.is_active', $arr['isActive']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    
    function vendorDuplicateCheck($arr) {
        if ($arr['addEditFlag'] == 'edit') {
            $this->db->where_not_in('contact_code', $arr['vendorId']);
        }
        $this->db->where('contact_name', $arr['fullName']);
        $this->db->where('mobile_no', $arr['mobileNo']);
        $this->db->where('contact_type', VENDOR);
        $query = $this->db->get('contact');
        if ($query->num_rows() > 0) {
            return 2;
        }
        return 1;
    }
    
    function addVendor($contactInfo) {
        $arr['fullName'] = $contactInfo['full_name'];
        $arr['mobileNo'] = $contactInfo['mobile_no'];
        $arr['addEditFlag'] = 'add';
        $duplicateFlag = $this->vendorDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->insert('contact', $contactInfo);
        
        return 1;
    }
    
    function editVendor($contactInfo) {
        $arr['fullName'] = $contactInfo['full_name'];
        $arr['mobileNo'] = $contactInfo['mobile_no'];
        $arr['addEditFlag'] = 'edit';
        $duplicateFlag = $this->vendorDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->where('contact_code', $contactInfo['contact_code']);
        $this->db->where('contact_type', VENDOR);
        $this->db->update('contact', $contactInfo);
        
        return 1;
    }
}
