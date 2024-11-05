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
      $post_sql = "SELECT
                          post.`id`,
                          post.`user_id`,
                          post.`title`,
                          post.`description`,
                          post.`like_count`,
                          COUNT(likes.`id`) as like_count
                   FROM `posts` post
                   LEFT JOIN `likes` likes
                    ON likes.`post_id` = post.`id`
                   GROUP BY likes.`post_id`
                   ORDER BY `created_at` ASC
                  ";

        $post_query = $this->db->query($post_sql);

        if (!$post_query->num_rows()) {
          return null;
        }

        $posts = $post_query->result_array();

        foreach ($posts as $key => $post) {
          $posts[$key]['is_liked'] = true;
        }

        return $posts;
    }
  }
