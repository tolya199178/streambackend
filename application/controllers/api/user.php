<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class user extends Backend_Controller {

    /**
     * 
     */
    public function index() {
        
    }

    public function getuserlist() {
        $this->load->model('users_m', 'userModel');
        $users = $this->userModel->getUserList();
        echo json_encode(array('result' => true, 'object' => $users));
        die();
    }

    public function getuserbyid() {
        $this->load->model('users_m', 'userModel');
        $id = $_REQUEST['id'];
        $user = $this->userModel->getUserById($id);
        if(count($user) == 0){
            $this->show400error("No Exist User Id");
        }else{
            $this->load->model('user_follows_m', 'folowModel');
            $followUsers = $this->folowModel->getFollowIds($id, false);
            $followingUsers = $this->folowModel->getFollowingIds($id, false);
            $user['followers_count'] = count($followUsers);
            $user['followings_count'] = count($followingUsers);
            $this->load->model('streams_m', 'streamsModel');            
            $user['streams'] = $this->streamsModel->getStreamsByUserId($id);
            echo json_encode(array('result' => true, 'object' => $user));
            die();
        }        
    }

    public function updateuser() {
        $row =  $_REQUEST;
        if(isset($row['id'])){
            $id = $row['id'];
        }else{
            $user = $this->getUser();
            $id = $user['id'];
        }
        
        $user = array();
        
        if(isset($row['login_name'])){
            $user['login_name'] = $row['login_name'];
        }
        if(isset($row['first_name'])){
            $user['first_name'] = $row['first_name'];
        }
        if(isset($row['last_name'])){
            $user['last_name'] = $row['last_name'];
        }
        if(isset($row['email_addr'])){
            $user['email_addr'] = $row['email_addr'];
        }
        if(isset($row['photo_url'])){
            $user['photo_url'] = $row['photo_url'];
        }
        if(isset($row['passwd'])){
            $user['passwd'] = $row['passwd'];
        }
        if(isset($row['device_token'])){
            $user['device_token'] = $row['device_token'];
        }
        
        //update user
        $this->userModel->updateUser($id, $user);
        $user = $this->userModel->getUserById($id);
        echo json_encode(array('result'=>true,'object'=>$user));
        die();
    }

    public function deleteuser() {
        $row =  $_REQUEST;
        if(isset($row['id'])){
            $id = $row['id'];
        }else{
            $user = $this->getUser();
            $id = $user['id'];
        }
        $user = $this->userModel->getUserById($id);
        if(count($user) > 0){
            $this->userModel->deleteUser($id);
            echo json_encode(array('result'=>true));
            die();
        }else{
            $this->show400error("No Exist User Id");
        }        
    }    
}
