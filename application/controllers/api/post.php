<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class post extends Backend_Controller {

    /**
     * 
     */
    public function index() {
        
    }

    public function addpost() {
        $param = $_REQUEST;
        if (isset($param['user_id'])) {
            $userId = $param['user_id'];
        } else {
            $user = $this->getUser();
            $userId = $user['id'];
        }        
        $post = array(
            'stream_id' => $param['stream_id'],
            'user_id' => $param['user_id'],
            'title' => $param['title'],
            'content' => $param['content'],
            'image_url' => $param['image_url']?$param['image_url']:"",
            'movie_url' => $param['movie_url']
        );
        $this->load->model('posts_m', 'postModel');
        $newId = $this->postModel->insertPost($post);
        $post = $this->postModel->getPostById($newId);
        echo json_encode(array('result' => true, 'object' => $post));
        die();
    }

    public function updatepost() {
        $param = $_REQUEST;
        $id = $param['id'];
        $stream = array();
        if (isset($param['stream_id'])) {
            $stream['stream_id'] = $param['stream_id'];
        }
        if (isset($param['user_id'])) {
            $stream['user_id'] = $param['user_id'];
        }
        if (isset($param['title'])) {
            $stream['title'] = $param['title'];
        }        
        if (isset($param['content'])) {
            $stream['content'] = $param['content'];
        }
        if (isset($param['img_url'])) {
            $stream['img_url'] = $param['img_url'];
        }
        if (isset($param['title'])) {
            $stream['title'] = $param['title'];
        }
        $this->load->model('posts_m', 'postModel');
        $this->postModel->updatePost($id, $stream);
        $stream = $this->postModel->getPostById($id);
        echo json_encode(array('result' => true, 'object' => $stream));
        die();
    }

    public function deletepost() {
        $param = $_REQUEST;
        $id = $param['id'];
        $this->load->model('posts_m', 'postModel');
        $this->postModel->deletePost($id);
        echo json_encode(array('result' => true));
    }
    
    public function getpostsbystreamid(){
        $param = $_REQUEST;
        $streamId = $param['stream_id'];
        
    }

}
