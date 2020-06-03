<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UserManagement_model extends CI_Model {

    function getUser($arr = array()) {
        $this->db->select('user_login.user_id,user_login.username,user_login.user_role,user_login.is_active,
                user_role.role_title,user_info.full_name,user_info.email,user_info.mobile_no,user_info.address,user_info.profile_image');
        $this->db->from('user_login');
        $this->db->join('user_role', 'user_role.role_code = user_login.user_role', 'left');
        $this->db->join('user_info', 'user_info.user_id = user_login.user_id');
        if ($arr['userId']) {
            $this->db->where('user_login.user_id', $arr['userId']);
        }
        if ($arr['isActive']) {
            $this->db->where('user_login.is_active', $arr['isActive']);
        }
        $this->db->order_by('user_login.created_dt_tm', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function getUserRole($arr) {
        $this->db->select('role_code,role_title,permitted_page_code');
        if ($arr['isActive']) {
            $this->db->where('is_active', $arr['isActive']);
        }
        $query = $this->db->get('user_role');
        return $query->result_array();
    }

    function userDuplicateCheck($arr) {
        if ($arr['addEditFlag'] == 'edit') {
            $this->db->where_not_in('user_id', $arr['userId']);
        }
        $this->db->where('full_name', $arr['fullName']);
        $this->db->where('mobile_no', $arr['mobileNo']);
        $query = $this->db->get('user_info');
        if ($query->num_rows() > 0) {
            return 2;
        }
        return 1;
    }

    function addUser($userInfo, $userLogin) {
        $arr['fullName'] = $userInfo['full_name'];
        $arr['mobileNo'] = $userInfo['mobile_no'];
        $duplicateFlag = $this->userDuplicateCheck($arr);
        if ($duplicateFlag == 2) {
            return 3;
        }
        $this->db->insert('user_info', $userInfo);
        $this->db->insert('user_login', $userLogin);
        
        return 1;
    }

}
