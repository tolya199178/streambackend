<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class users_m extends CI_Model {   
    
    const T_NAME = 'users';
    /**
     * 
     * @param type $userData
     */
    public function insertUser($userData) {        
        $userData['passwd'] = md5($userData['passwd']);
        $userData['created_date'] = date('Y-m-d H:i:s');
        $this->db->insert(self::T_NAME, $userData);
        return $this->db->insert_id();
    }

    public function updateUser($userId, $userData) {
        if ($userData['passwd'])
            $userData['passwd'] = md5($userData['passwd']);
        $this->db->update(self::T_NAME, $userData, array('id' => $userId));
        return true;
    }

    public function deleteUser($userId) {
        return $this->db->delete(self::T_NAME, array('id' => $userId));
    }
    
    
    /**
     * 
     * @param type $userId
     * @return type
     */
    public function getUserById($userId) {
        $sql = sprintf(
                "SELECT * FROM %s WHERE 1 AND id='%d'", self::T_NAME, $userId
        );
        return $this->db->query($sql)->row_array();
    }
    
    /**
     * 
     * @param type $userId
     * @return type
     */
    public function getUserByLoginName($userName) {
        $sql = sprintf(
                "SELECT * FROM %s WHERE 1 AND login_name='%s'", self::T_NAME, $userName
        );
        return $this->db->query($sql)->row_array();
    }
    
    /**
     * 
     * @param type $userId
     * @return type
     */
    public function getUserByEmailId($emailId) {
        $sql = sprintf(
                "SELECT * FROM %s WHERE  email_addr = '%s'", self::T_NAME, $emailId
        );        
        return $this->db->query($sql)->row_array();
    }
    
    public function getUserByToken($token){
        $sql = sprintf(
                "SELECT * FROM %s WHERE  token = '%s'", self::T_NAME, $token
        );        
        return $this->db->query($sql)->row_array();
    }
    
    
    /**
     * get All Users
     * @return type
     */
    public function getUserList(){
        $sql = sprintf(
                "SELECT * FROM %s WHERE 1 ORDER BY `created_date` ASC", self::T_NAME
        );
        return $this->db->query($sql)->result_array();
    }
    
    
    public function getUsersByIds($ids){
        $sql = sprintf(
                "SELECT * FROM %s WHERE id IN (%s) ORDER BY `created_date` ASC", self::T_NAME,  implode(',', $ids)
        );
        return $this->db->query($sql)->result_array();
    }
    /**
     * @decription  
     * @param type $username
     * @param type $password
     * @return boolean
     */
    public function login($username, $password) {

        $sql = sprintf(
                "SELECT * FROM %s WHERE (login_name='%s' or email_addr='%s' ) AND passwd='%s'", self::T_NAME, mysql_real_escape_string($username), mysql_real_escape_string($username), md5($password)
        );
        $query = $this->db->query($sql);
        if ($query->num_rows() == 0) {
            return false;
        }
        $resultAry = $query->row_array();
        return $resultAry;
    }
    
}
