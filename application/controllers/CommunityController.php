<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommunityController extends CI_Controller{

      public function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->model("list_model");
      $this->load->model("Calculate_model");
      $this->load->model("User_model");
      $this->load->model("Post_model");
      $this->load->library("session");
      $this->load->helper('url');
      }

//       public function toggle_like() {
//     $user_id = $this->session->userdata('user_id');
//     $post_id = $this->input->post('post_id');
//
//     if (!$user_id || !$post_id) {
//         echo json_encode(['error' => 'Invalid request']);
//         return;
//     }
//
//     $this->load->model('Like_model');
//     $liked = $this->Like_model->toggle_like($user_id, $post_id);
//
//     echo json_encode(['liked' => $liked]);
// }
public function toggleLike() {
    $postId = $this->input->post('post_id');
    $userId = $this->session->userdata('user_id'); // Get logged-in user's ID

    // Check if the user has already liked the post
    $likeExists = $this->db->where('post_id', $postId)
                           ->where('user_id', $userId)
                           ->get('likes')
                           ->num_rows() > 0;

    if ($likeExists) {
        // User is unliking the post
        $this->db->where('post_id', $postId)
                 ->where('user_id', $userId)
                 ->delete('likes');
        $this->db->set('like_count', 'like_count - 1', FALSE)
                 ->where('id', $postId)
                 ->update('posts');
        $response = ['liked' => false];
    } else {
        // User is liking the post
        $this->db->insert('likes', ['post_id' => $postId, 'user_id' => $userId]);
        $this->db->set('like_count', 'like_count + 1', FALSE)
                 ->where('id', $postId)
                 ->update('posts');
        $response = ['liked' => true];
    }

    echo json_encode($response);
}



}
