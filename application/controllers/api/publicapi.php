<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class publicapi extends Backend_Controller {

    /**
     * 
     */
    public function index() {
        
    }

    /**
     * 
     */
    public function login() {
        $row = $_REQUEST;
        $this->load->model('users_m', 'userModel');
        $userName = $row['username'];
        $passwd = $row['passwd'];
        $user = $this->userModel->login($userName, $passwd);
        if ($user === false) {
            echo json_encode(array('result' => false, 'message' => 'Username and/or password is invalid'));
        } else {
            $this->userModel->updateUser($user['id'], array(
                'token' => generator_random_string(),
                'device_token' => $row['device_token']
            ));
            $row = $this->userModel->getUserById($user['id']);
            echo json_encode(array('result' => true, 'token' => $user['token'], 'object' => $row));
        }
        die();
    }

    /**
     * 
     */
    public function register() {
        $row = $_REQUEST;
        $this->load->model('users_m', 'userModel');
        $user = $this->userModel->getUserByLoginName($row['login_name']);
        if (sizeof($user) > 0) {
            $this->show400error("Exist Login Name");
        }
        $user = $this->userModel->getUserByEmailId($row['email_addr']);
        if (sizeof($user) > 0) {
            $this->show400error("Exist Email Address");
        }

        $user = array(
            'login_name' => $row['login_name'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email_addr' => $row['email_addr'],
            'photo_url' => $row['photo_url'],
            'passwd' => $row['passwd'],
            'device_token' => $row['device_token'],
            'token' => generator_random_string()
        );
        //add user
        $newId = $this->userModel->insertUser($user);
        $user['id'] = $newId;
        echo json_encode(array('result' => true, 'object' => $user));
        die();
    }

    /**
     *
     */
    public function forget_password() {
        $row = $_REQUEST;
        $this->load->model('users_m', 'userModel');
        $user = $this->userModel->getUserByEmailId($row['email_addr']);
        if (sizeof($user) == 0) {
            echo json_encode(array('result' => false, 'message' => 'No Exist Email Address'));
        } else {
            $this->userModel->updateUser($user['id'], array('reset_token' => generator_random_string()));
            /**
             * @todo
             * Send Email to User
             */
            echo json_encode(array('result' => true));
        }
        die();
    }

    /**
     * 
     */
    public function check_uniqueloginname() {
        $row = $_REQUEST;
        $this->load->model('users_m', 'userModel');
        $user = $this->userModel->getUserByLoginName($row['login_name']);
        if (!$row['login_name']) {
            $this->show400error("");
        }
        if (sizeof($user) > 0) {
            echo json_encode(array('result' => false, 'message' => 'Exist Login Name'));
        } else {
            echo json_encode(array('result' => true));
        }
        die();
    }

    /**
     * 
     */
    public function check_uniqueemail() {
        $row = $_REQUEST;
        $this->load->model('users_m', 'userModel');
        $user = $this->userModel->getUserByEmailId($row['email_addr']);
        if (!$row['email_addr']) {
            $this->show400error("");
        }
        if (sizeof($user) > 0) {
            echo json_encode(array('result' => false, 'message' => 'Exist Email Address'));
        } else {
            echo json_encode(array('result' => true));
        }
        die();
    }

    public function file_upload() {
        $config['upload_path'] = './assets/uploads/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|xml';
        $config['max_size'] = '10000';
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            echo json_encode(array('result' => true, 'object'=>$error));
//            $this->load->view('api/upload_form', $error);
        } else {
            $data = array('upload_data' => $this->upload->data());            
            $src = site_url().'/assets/uploads/'.$data['upload_data']['file_name'];
            echo json_encode(array('result' => true, 'object'=>array('file_url'=>$src)));
            //            $this->load->view('api/upload_success', $error);
        }
    }

}
