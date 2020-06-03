<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UserManagement extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('UserManagement_model');
    }

    public function index() {
        redirect('Home');
    }

    public function user() {
        $this->userRoleAuthentication(USER);
        $this->data['currentPageCode'] = USER;
        $this->data['pageHeading'] = 'User';
        $this->data['pageUrl'] = 'userManagement/userView';
        //$this->data['pageUrl'] = 'userManagement/userDataTableView';
        $this->loadView($this->data);
    }

    public function getDataTableData() {
        $draw = $_POST['draw'];
        $row = $_POST['start'];
        $rowperpage = $_POST['length']; // Rows display per page
        $columnIndex = $_POST['order'][0]['column']; // Column index
        $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
        $searchValue = $_POST['search']['value']; // Search value
        ## Total number of records without filtering
        $this->db->select('COUNT(id) as allcount');
        $query = $this->db->get('membership_card');
        $totalRecords = $query->row()->allcount;

        ## Total number of record with filtering

        $this->db->select('COUNT(id) as allcount');
        if ($searchValue != '') {
            $this->db->like('card_id', $searchValue);
            $this->db->or_like('card_number', $searchValue);
        }
        $query = $this->db->get('membership_card');
        $totalRecordwithFilter = $query->row()->allcount;

        ## Fetch records
        $this->db->limit($rowperpage, $row);  // number of records, start from
        if ($searchValue != '') {
            $this->db->like('card_id', $searchValue);
            $this->db->or_like('card_number', $searchValue);
        }
        $this->db->order_by($columnName, $columnSortOrder);
        $query = $this->db->get('membership_card');
        $records = $query->result_array();

        $data = array();
        foreach ($records as $record) {
            $data[] = array('id' => $record['id'],
                'card_id' => '<span class="td-f-l">' . $record['card_id'] . '</span>',
                'card_number' => $record['card_number']);
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecordwithFilter,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $data
        );
        echo json_encode($response);
    }

    public function getUserList() {
        $this->userRoleAuthentication(USER);
        //$arr['isActive'] = '';
        $results = $this->UserManagement_model->getUser();
        $response = array();
        $i = 1;
        foreach ($results as $result) {
            $status = "";
            if ($result['is_active'] == 1) {
                $status = 'Active';
            } else if ($result['is_active'] == 0) {
                $status = 'Inactive';
            }
            $x = array($i,
                '<span class="td-f-l">' . $result['full_name'] . '</span>',
                $result['mobile_no'],
                ($result['email']) ? '<span class="td-f-l">' . $result['email'] . '</span>' : '<small><i>N/A</i></small>',
                ($result['role_title'] != '0') ? $result['role_title'] : '<small><i>N/A</i></small>',
                $status,
                $result['user_id']
            );
            $response[] = $x;
            $i++;
        }

//        $response[] = array('<span class="td-f-l">sakib</span>', '01945882352', '1');
//        $response[] = array('<span class="td-f-l">rakib</span>', '01626026705', '2');
        echo json_encode(array('data' => $response));
    }

    public function newUser() {
        $this->userRoleAuthentication(USER);
        $response = (int) $this->input->get('response', true);
        $this->data['msgFlag'] = "";
        if ($response == 1) {
            $this->data['msg'] = "You have successfully crearted a user";
            $this->data['msgFlag'] = "success";
        } elseif ($response == 2) {
            $this->data['msg'] = "Failed";
            $this->data['msgFlag'] = "danger";
        } elseif ($response == 3) {
            $this->data['msg'] = "Duplicate user";
            $this->data['msgFlag'] = "danger";
        }

        $arr['isActive'] = 1;
        $this->data['userRoles'] = $this->UserManagement_model->getUserRole($arr);
        $this->data['currentPageCode'] = USER;
        $this->data['pageHeading'] = 'New User';
        $this->data['pageUrl'] = 'userManagement/addUserView';
        $this->loadView($this->data);
    }

    public function userDuplicateCheck() {
        $this->userRoleAuthentication(USER);
        $arr['fullName'] = trim($this->input->post('fullName', true));
        $arr['mobileNo'] = trim($this->input->post('mobileNo', true));
        $arr['addEditFlag'] = trim($this->input->post('addEditFlag', true));
        $result = $this->UserManagement_model->userDuplicateCheck($arr);
        echo $result;
    }

    public function addUser() {
        $this->userRoleAuthentication(USER);
        $fullName = trim($this->input->post('fullName', true));
        $mobileNo = trim($this->input->post('mobile', true));
        $email = ($this->input->post('email', true)) ? trim($this->input->post('email', true)) : NULL;
        $userRole = trim($this->input->post('userRole', true));

        if ($fullName && $mobileNo && $userRole) {
            $userInfo['user_id'] = USER_CODE . getCode(USER_CODE);
            $userInfo['full_name'] = $fullName;
            $userInfo['email'] = $email;
            $userInfo['mobile_no'] = $mobileNo;
            $userInfo['created_by'] = $this->user;
            $userInfo['created_dt_tm'] = $this->dateTime;
            $userInfo['updated_by'] = $this->user;
            $userInfo['updated_dt_tm'] = $this->dateTime;

            $userLogin['user_id'] = $userInfo['user_id'];
            $userLogin['username'] = $mobileNo;
            $userLogin['password'] = md5('1234');
            $userLogin['user_role'] = $userRole;
            $userLogin['created_by'] = $this->user;
            $userLogin['created_dt_tm'] = $this->dateTime;
            $userLogin['updated_by'] = $this->user;
            $userLogin['updated_dt_tm'] = $this->dateTime;

            $result = $this->UserManagement_model->addUser($userInfo, $userLogin);
            redirect('UserManagement/newUser?response=' . $result);
        } else {
            redirect('UserManagement/newUser?response=2');
        }
    }

    public function showUserDetails() {
        $this->userRoleAuthentication(USER);
        echo $this->input->get('userId', true);
    }

}
