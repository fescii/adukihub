<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reaction_model extends CI_Model
{
    //save reaction
    public function save_reaction($post_id, $reaction)
    {
        if (is_reaction_voted($post_id, $reaction)) {
            helper_setsession('vr_reaction_' . $reaction . '_' . $post_id, '0');
            $this->set_cookie_reaction('vr_reaction_' . $reaction . '_' . $post_id, '0');
            $this->decrease_reaction_vote($post_id, $reaction);
            $this->decrease_post_vote_session($post_id);
        } else {
            helper_setsession('vr_reaction_' . $reaction . '_' . $post_id, '1');
            $this->set_cookie_reaction('vr_reaction_' . $reaction . '_' . $post_id, '1');
            $this->increase_reaction_vote($post_id, $reaction);
            $this->increase_post_vote_session($post_id);
        }
    }

    //increase reaction vote
    public function increase_reaction_vote($post_id, $reaction)
    {
        $row = $this->get_reaction($post_id);
        $re = 're_' . $reaction;
        $data = array(
            're_' . $reaction => $row->$re + 1,
        );
        $this->db->where('post_id', clean_number($post_id));
        $this->db->update('reactions', $data);
    }

    //decrease reaction vote
    public function decrease_reaction_vote($post_id, $reaction)
    {
        $row = $this->get_reaction($post_id);
        $re = 're_' . $reaction;
        $data = array(
            're_' . $reaction => $row->$re - 1,
        );
        $this->db->where('post_id', clean_number($post_id));
        $this->db->update('reactions', $data);
    }

    //get reaction
    public function get_reaction($post_id)
    {
        $sql = "SELECT * FROM reactions WHERE reactions.post_id = ?";
        $query = $this->db->query($sql, array(clean_number($post_id)));
        $row = $query->row();

        if (empty($row)) {
            $data = array(
                'post_id' => $post_id,
                're_like' => 0,
                're_dislike' => 0,
                're_love' => 0,
                're_funny' => 0,
                're_angry' => 0,
                're_sad' => 0,
                're_wow' => 0
            );

            $this->db->insert('reactions', $data);

            $sql = "SELECT * FROM reactions WHERE reactions.post_id = ?";
            $query = $this->db->query($sql, array(clean_number($post_id)));
            $row = $query->row();
        }
        return $row;
    }

    //increase post vote session
    public function increase_post_vote_session($post_id)
    {
        $post_id = clean_number($post_id);
        $vote = helper_getcookie('vr_reaction_vote_count_' . $post_id, 'int');
        helper_setsession('vr_reaction_vote_count_' . $post_id, $vote + 1);
        $this->set_cookie_reaction('vr_reaction_vote_count_' . $post_id, $vote + 1);
    }

    //decrease post vote session
    public function decrease_post_vote_session($post_id)
    {
        $post_id = clean_number($post_id);
        $vote = helper_getcookie('vr_reaction_vote_count_' . $post_id, 'int');
        helper_setsession('vr_reaction_vote_count_' . $post_id, $vote - 1);
        $this->set_cookie_reaction('vr_reaction_vote_count_' . $post_id, $vote - 1);
    }

    //set voted reactions session
    public function set_voted_reactions_session($post_id)
    {
        $post_id = clean_number($post_id);
        $vote = helper_getcookie('vr_reaction_vote_count_' . $post_id,'int');
        helper_setsession('vr_reaction_vote_count_' . $post_id, $vote);

        $this->set_session_by_cookie('vr_reaction_like_' . $post_id);
        $this->set_session_by_cookie('vr_reaction_dislike_' . $post_id);
        $this->set_session_by_cookie('vr_reaction_love_' . $post_id);
        $this->set_session_by_cookie('vr_reaction_funny_' . $post_id);
        $this->set_session_by_cookie('vr_reaction_angry_' . $post_id);
        $this->set_session_by_cookie('vr_reaction_sad_' . $post_id);
        $this->set_session_by_cookie('vr_reaction_wow_' . $post_id);
    }

    //set session by cookie
    public function set_session_by_cookie($name)
    {
        if (helper_getcookie($name) == '1') {
            helper_setsession($name, '1');
        } else {
            helper_setsession($name, '0');
        }
    }

    //set reaction cookie
    public function set_cookie_reaction($name, $value)
    {
        setcookie(COOKIE_PREFIX . '_' . $name, $value, time() + (86400 * 365), "/");
    }
}