<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class posts_m extends CI_Model {   
    const T_NAME = 'posts';

    /**
     * 
     * @param type $userData
     */
    public function insertPost($postData) {
        $userData['created_date'] = date('Y-m-d H:i:s');
        $this->db->insert(self::T_NAME, $postData);
        return $this->db->insert_id();
    }

    public function updatePost($postId, $postData) {        
        $this->db->update(self::T_NAME, $postData, array('id' => $postId));
        return true;
    }

    public function deletePost($postId) {
        return $this->db->delete(self::T_NAME, array('id' => $postId));
    }

    public function getPostById($postId){
        $sql = sprintf(
                "SELECT * FROM %s WHERE 1 AND id='%d'", self::T_NAME, $postId
        );
        return $this->db->query($sql)->row_array();
    }
    
    public function getPostsByStreamId($streamId){
         $sql = sprintf(
                "SELECT * FROM %s WHERE stream_id=%d ORDER BY `created_date` ASC", self::T_NAME,$streamId
        );
        return $this->db->query($sql)->result_array();
    }
}
