<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Login_model');
    }

    public function index($msgFlag = NULL) {
        $data['msg'] = "";
        if ($msgFlag == '2') {
            $data['msgFlag'] = "danger";
            $data['msg'] = "Your username or password is incorrect";
        }
        if ($this->session->userdata('validated')) {
            redirect('Home');
        } else {
            $this->session->sess_destroy();
            $data['pageUrl'] = 'login/loginForm';
            $this->load->view('login/loginLayout', $data);
        }
    }

    public function checkLogin() {
        if ($this->session->userdata('validated')) {
            redirect('Home');
        }
        $username = trim($this->input->post('username', true));
        $password = trim($this->input->post('password', true));
        if ($username && $password) {
            $result = $this->Login_model->checkLogin($username, $password);
            if ($result) {
                redirect('Home');
            }
            redirect('Login/index/2');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('Login');
    }

}
