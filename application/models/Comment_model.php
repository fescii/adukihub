<?php defined('BASEPATH') or exit('No direct script access allowed');

class Comment_model extends CI_Model
{
    //add comment
    public function add_comment()
    {
        $data = array(
            'parent_id' => $this->input->post('parent_id', true),
            'post_id' => $this->input->post('post_id', true),
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'comment' => $this->input->post('comment', true),
            'status' => 1,
            'ip_address' => 0,
        );
        if (!empty($data['post_id']) && !empty(trim($data['comment']))) {
            $data['user_id'] = 0;
            if ($this->auth_check) {
                $data['user_id'] = $this->auth_user->id;
                $data['name'] = $this->auth_user->username;
                $data['email'] = $this->auth_user->email;
            }
            $ip = $this->input->ip_address();
            if (!empty($ip)) {
                $data['ip_address'] = $ip;
            }
            if ($this->general_settings->comment_approval_system == 1 && !check_user_permission('comments_contact')) {
                $data["status"] = 0;
            }
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('comments', $data);
            $last_id = $this->db->insert_id();
            helper_setcookie('added_comment_id_' . $last_id, 1);
        }
    }

    //like comment
    public function like_comment($comment_id)
    {
        $comment = $this->get_comment($comment_id);
        if (!empty($comment)) {
            $like_count = $comment->like_count;
            //check comment owner
            if (check_comment_owner($comment)) {
                return $like_count;
            }

            $cookie = helper_getcookie('comment_voted_' . $comment->id);
            $new_cookie = "";
            if (!empty($cookie)) {
                $like_count = $like_count - 1;
                $new_cookie = "";
            } else {
                $like_count = $like_count + 1;
                $new_cookie = 1;
            }
            if ($like_count < 0) {
                $like_count = 0;
            }
            //update comment
            $this->db->where('id', $comment->id);
            if ($this->db->update('comments', ['like_count' => $like_count])) {
                helper_setcookie('comment_voted_' . $comment->id, $new_cookie);
                return $like_count;
            }
        }
        return 0;
    }

    //get comment
    public function get_comment($id)
    {
        $sql = "SELECT * FROM comments WHERE comments.id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //comments
    public function get_comments($post_id, $limit)
    {
        $sql = "SELECT comments.*, users.username AS user_username, users.slug AS user_slug, users.avatar AS user_avatar
                FROM comments LEFT JOIN users ON comments.user_id = users.id 
                WHERE comments.post_id = ? AND comments.parent_id = 0 AND comments.status = 1 ORDER BY comments.id DESC LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($post_id), clean_number($limit)));
        return $query->result();
    }

    //get all comments
    public function get_all_comments()
    {
        $sql = "SELECT comments.*, posts.title as post_title FROM comments 
                INNER JOIN posts ON posts.id = comments.post_id
                ORDER BY comments.id DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get last comments
    public function get_last_comments($limit)
    {
        $sql = "SELECT comments.*, posts.title_slug as post_slug FROM comments 
                INNER JOIN posts ON posts.id = comments.post_id WHERE comments.status = 1
                ORDER BY comments.id DESC LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($limit)));
        return $query->result();
    }

    //get last pending comments
    public function get_last_pending_comments($limit)
    {
        $sql = "SELECT comments.*, posts.title_slug as post_slug FROM comments 
                INNER JOIN posts ON posts.id = comments.post_id WHERE comments.status = 0
                ORDER BY comments.id DESC LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($limit)));
        return $query->result();
    }

    //get all approved comments
    public function get_all_approved_comments()
    {
        $sql = "SELECT comments.*, posts.title_slug as post_slug FROM comments 
                INNER JOIN posts ON posts.id = comments.post_id WHERE comments.status = 1
                ORDER BY comments.id DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get all pending comments
    public function get_all_pending_comments()
    {
        $sql = "SELECT comments.*, posts.title_slug as post_slug FROM comments 
                INNER JOIN posts ON posts.id = comments.post_id WHERE comments.status = 0
                ORDER BY comments.id DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //subomments
    public function get_subcomments($parent_id)
    {
        $sql = "SELECT comments.*, users.username AS user_username, users.slug AS user_slug, users.avatar AS user_avatar
                FROM comments LEFT JOIN users ON comments.user_id = users.id 
                WHERE comments.parent_id = ? AND comments.status = 1 ORDER BY created_at DESC";
        $query = $this->db->query($sql, array(clean_number($parent_id)));
        return $query->result();
    }

    //get comment count by post id
    public function get_comment_count_by_post_id($post_id)
    {
        $sql = "SELECT COUNT(comments.id) AS count FROM comments WHERE comments.post_id = ? AND parent_id = 0 AND status = 1";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        return $query->row()->count;
    }

    //approve comment
    public function approve_comment($id)
    {
        $comment = $this->get_comment($id);
        if (!empty($comment)) {
            $data = array(
                'status' => 1
            );
            $this->db->where('id', $comment->id);
            return $this->db->update('comments', $data);
        }
        return false;
    }

    //approve multi comments
    public function approve_multi_comments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                $this->approve_comment($id);
            }
        }
    }

    //delete comment
    public function delete_comment($id)
    {
        $comment = $this->get_comment($id);
        if (!empty($comment)) {
            $subcomments = $this->get_subcomments($id);
            if (!empty($subcomments)) {
                foreach ($subcomments as $subcomment) {
                    $this->db->where('id', $subcomment->id);
                    $this->db->delete('comments');
                }
            }
            $this->db->where('id', $comment->id);
            return $this->db->delete('comments');
        }
        return false;
    }

    //delete multi comments
    public function delete_multi_comments($comment_ids)
    {
        if (!empty($comment_ids)) {
            foreach ($comment_ids as $id) {
                $this->delete_comment($id);
            }
        }
    }
}
