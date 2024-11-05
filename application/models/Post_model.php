<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

  public function __construct(){
  parent::__construct();
  $this->load->database();
  }

  public function create_post($param) {
        return $this->db->insert("posts", $param);
    }

    public function get_posts() {
    // İstifadəçi sessiyasında olan user_id-ni əldə edirik
    $session_user_id = $this->session->userdata('user_id');

    $post_sql = "SELECT
                    post.`id`,
                    post.`user_id`,
                    post.`title`,
                    post.`description`,
                    post.`like_count`,
                    COUNT(likes.`id`) as like_count,
                    (CASE WHEN likes.`user_id` = ? THEN 1 ELSE 0 END) as is_liked
                 FROM `posts` post
                 LEFT JOIN `likes` likes
                 ON likes.`post_id` = post.`id`
                 GROUP BY post.`id`
                 ORDER BY post.`created_at` ASC";

    // Sorğunu sessiya istifadəçi ID ilə icra edirik
    $post_query = $this->db->query($post_sql, array($session_user_id));

    if (!$post_query->num_rows()) {
        return null;
    }

    // Postları qaytarırıq
    $posts = $post_query->result_array();

    return $posts;
}

  }
