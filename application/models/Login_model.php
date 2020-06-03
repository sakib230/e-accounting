<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function checkLogin($username, $password) {
        $this->db->select('user_login.user_id,user_login.user_role,user_login.is_active,user_info.full_name,user_role.permitted_page_code');
        $this->db->from('user_login');
        $this->db->join('user_info', 'user_info.user_id = user_login.user_id');
        $this->db->join('user_role', 'user_role.role_code = user_login.user_role');
        $this->db->where('user_login.username', $username);
        $this->db->where('user_login.password', md5($password));
        $this->db->where('user_login.is_active', 1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $row = $query->row();
            $data = array(
                'userId' => $row->user_id,
                'fullName' => $row->full_name,
                'userRole' => $row->user_role,
                'isActive' => $row->is_active,
                'permittedPageCode' => $row->permitted_page_code,
                'validated' => true
            );
            $this->session->set_userdata($data);
            return 1;
        }
        return 0;
    }

}
