<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class streams_m extends CI_Model {

    const T_NAME = 'streams';

    /**
     * 
     * @param type $userData
     */
    public function insertStream($streamData) {
        $streamData['created_date'] = date('Y-m-d H:i:s');
        $this->db->insert(self::T_NAME, $streamData);
        return $this->db->insert_id();
    }

    public function updateStream($streamId, $streamData) {        
        $this->db->update(self::T_NAME, $streamData, array('id' => $streamId));
        return true;
    }

    public function deleteStream($streamId) {
        return $this->db->delete(self::T_NAME, array('id' => $streamId));
    }

    public function getStreamById($streamId){
        $sql = sprintf(
                "SELECT * FROM %s WHERE 1 AND id='%d'", self::T_NAME, $streamId
        );
        $stream = $this->db->query($sql)->row_array();
        
        
        $this->load->model('stream_users_m');
        $stream['stream_members'] = $this->stream_users_m->getAssignedUsers($stream['id'], false);        
        
        $this->load->model('posts_m');
        $stream['stream_posts'] = $this->posts_m->getPostsByStreamId($stream['id']);        
        return $stream;
    }
    /**
     * get All Users
     * @return type
     */
    public function getAllStream(){
        $sql = sprintf(
                "SELECT * FROM %s WHERE 1 ORDER BY `created_date` ASC", self::T_NAME
        );
        return $this->db->query($sql)->result_array();
    }
    
    public function getStreamsByUserId($userId){
        $sql = sprintf(
                "SELECT * FROM %s WHERE user_id=%d", self::T_NAME,$userId
        );
        return $this->db->query($sql)->result_array();
    }
    
}
