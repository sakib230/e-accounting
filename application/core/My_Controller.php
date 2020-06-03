<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class My_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));
        $this->loginSessionCheck();
        $this->user = $this->session->userdata('userId');
        $this->dateTime = date('Y-m-d H:i:s');
    }

    private function loginSessionCheck() {
        if (!$this->session->userdata('validated')) {
            redirect('Login');
        }
        $this->load->database();

        $this->db->select('user_login.user_role,user_login.is_active,user_role.permitted_page_code');
        $this->db->from('user_login');
        $this->db->join('user_role', 'user_role.role_code = user_login.user_role');
        $this->db->where('user_login.user_id', $this->session->userdata('userId'));
        $this->db->where('user_login.is_active', 1);
        $query = $this->db->get();
        if ($query->num_rows() == 0) {
            $this->session->sess_destroy();
            redirect('Login');
        }

        $row = $query->row();
        $userRoleDb = $row->user_role;
        $permittedPageCodeDb = $row->permitted_page_code;

        if ($userRoleDb != $this->session->userdata('userRole') || $permittedPageCodeDb != $this->session->userdata('permittedPageCode')) {
            $newRole['userRole'] = $userRoleDb;
            $newRole['permittedPageCode'] = $permittedPageCodeDb;
            $this->session->set_userdata($newRole);
        }
    }

    public function userRoleAuthentication($pageCode = NULL, $pageCodeArr = array()) {
        $permittedPageCodeArr = explode(',', $this->session->userdata('permittedPageCode'));
        if ($pageCode) {
            if (!in_array($pageCode, $permittedPageCodeArr)) {
                redirect('Home');
            }
        }
        if ($pageCodeArr) {
            $flag = 0;
            for ($i = 0; $i < count($pageCodeArr); $i++) {
                if (in_array($pageCodeArr[$i], $permittedPageCodeArr)) {
                    $flag = 1;
                    break;
                }
            }
            if ($flag == 0) {
                redirect('Home');
            }
        }
    }

    public function loadView($pageInfoArr) {
        if (!isset($pageInfoArr['msgFlag'])) {
            $pageInfoArr['msgFlag'] = "";
            $pageInfoArr['msg'] = "";
        }
        $this->load->view('layout/layout', $pageInfoArr);
    }

    public function getInputValue($inputName, $formMethod = 'POST', $inputType = 'string', $maxLength = NULL, $requiredFlag = 0) {
        $inputValue = ($formMethod == 'POST') ? trim($this->input->post($inputName, true)) : trim($this->input->get($inputName, true));
        if ($requiredFlag == 1 && $inputValue == "") {
            echo 1 . " -> " . $inputName;
            exit();
        } else if ($requiredFlag == 0 && $inputValue == "") {
            $inputValue = NULL;
        } else {
            if ($inputType == 'string') {
                if ($maxLength != NULL) {
                    if ($maxLength < strlen($inputValue)) {
                        echo 2;
                        exit();
                    }
                }
            } else if ($inputType == 'int') {
                $inputValue = (int) $inputValue;
            } else if ($inputType == 'float') {
                $inputValue = (float) $inputValue;
            } else if ($inputType == 'date' && !$this->validateDateTime($inputValue, 'Y-m-d')) {
                echo 3;
                exit();
            } else if ($inputType == 'time' && !$this->validateDateTime($inputValue, 'H:i:s')) {
                echo 4;
                exit();
            } else if ($inputType == 'dateTime' && !$this->validateDateTime($inputValue, 'Y-m-d H:i:s')) {
                echo 5;
                exit();
            } else if ($inputType == 'mobileNo' && !$this->validateMobileNo($inputValue)) {
                echo 6;
                exit();
            } else if ($inputType == 'email' && !$this->validateEmail($inputValue)) {
                echo 7;
                exit();
            }
        }

        return $inputValue;
    }

    public function validateDateTime($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    public function validateMobileNo($mobileNo = NULL) {
        if (strlen($mobileNo) == 11) {
            if (preg_match('/^01[3-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9][0-9]/', $mobileNo)) {
                return 1;
            }
        }
        return 0;
    }

    public function validateEmail($email = NULL) {
//        if (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email)) {
//            return 1;
//        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 1;
        }
        return 0;
    }

}
