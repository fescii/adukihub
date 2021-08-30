<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reward_model extends CI_Model
{
    //get page views counts by date
    public function get_page_views_counts_by_date($user_id)
    {
        $sql = "SELECT COUNT(id) AS count, SUM(reward_amount) as total_amount, DATE(created_at) AS date FROM post_pageviews_month WHERE post_user_id = ? AND reward_amount > 0 AND MONTH(created_at) = ? GROUP BY date;";
        $query = $this->db->query($sql, array(clean_number($user_id), date('m')));
        return $query->result();
    }

    //update settings
    public function update_settings()
    {
        $reward_amount = $this->input->post('reward_amount');
        if ($reward_amount < 0.01) {
            $reward_amount = 0.01;
        }
        $data = array(
            'reward_system_status' => $this->input->post('reward_system_status'),
            'reward_amount' => $reward_amount
        );
        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update payout methods
    public function update_payout_methods()
    {
        $data = [
            'payout_paypal_status' => $this->input->post('payout_paypal_status'),
            'payout_iban_status' => $this->input->post('payout_iban_status'),
            'payout_swift_status' => $this->input->post('payout_swift_status')
        ];
        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //update currency
    public function update_currency()
    {
        $data = array(
            'currency_name' => $this->input->post('currency_name', false),
            'currency_symbol' => $this->input->post('currency_symbol', false),
            'currency_format' => $this->input->post('currency_format', false),
            'currency_symbol_format' => $this->input->post('currency_symbol_format', false)
        );
        $this->db->where('id', 1);
        return $this->db->update('general_settings', $data);
    }

    //get earnings
    public function get_earnings()
    {
        $this->db->where('reward_system_enabled', 1);
        $this->db->or_where('balance >', 0);
        $this->db->order_by('balance', 'DESC');
        $query = $this->db->get('users');
        return $query->result();
    }

    //get paginated earnings
    public function get_paginated_earnings($per_page, $offset)
    {
        $this->filter_earnings();
        $this->db->group_start();
        $this->db->where('reward_system_enabled', 1);
        $this->db->or_where('balance >', 0);
        $this->db->group_end();
        $this->db->order_by('balance', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('users');
        return $query->result();
    }

    //get paginated earnings count
    public function get_paginated_earnings_count()
    {
        $this->filter_earnings();
        $this->db->group_start();
        $this->db->where('reward_system_enabled', 1);
        $this->db->or_where('balance >', 0);
        $this->db->group_end();
        $this->db->select('COUNT(id) as count');
        $query = $this->db->get('users');
        return $query->row()->count;
    }

    //earnings filter
    public function filter_earnings()
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('users.username', clean_str($q));
            $this->db->or_like('users.email', clean_str($q));
            $this->db->group_end();
        }
    }

    //get payout
    public function get_payout($id)
    {
        $this->db->where('id', clean_number($id));
        $query = $this->db->get('payouts');
        return $query->row();
    }

    //get paginated payouts
    public function get_paginated_payouts($per_page, $offset)
    {
        $this->filter_payouts();
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('payouts');
        return $query->result();
    }

    //get paginated payouts count
    public function get_paginated_payouts_count()
    {
        $this->filter_payouts();
        $this->db->select('COUNT(id) AS count');
        $query = $this->db->get('payouts');
        return $query->row()->count;
    }

    //payouts filter
    public function filter_payouts()
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('username', clean_str($q));
            $this->db->or_like('email', clean_str($q));
            $this->db->or_like('payout_method', clean_str($q));
            $this->db->group_end();
        }
    }

    //add payout
    public function add_payout($user, $amount)
    {
        if (!empty($user)) {
            $data = [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'payout_method' => $this->input->post('payout_method', true),
                'amount' => $amount,
                'created_at' => date('Y-m-d H:i:s')
            ];
            if ($this->db->insert('payouts', $data)) {
                $balance = $user->balance - $amount;
                if ($balance < 0) {
                    $balance = 0;
                }
                $this->db->where('id', $user->id);
                $this->db->update('users', ['balance' => $balance]);
                return true;
            }
        }
        return false;
    }

    //delete payout
    public function delete_payout($id)
    {
        $payout = $this->get_payout($id);
        if (!empty($payout)) {
            $this->db->where('id', $payout->id);
            return $this->db->delete('payouts');
        }
        return false;
    }

    //get paginated user payouts
    public function get_paginated_user_payouts($user_id, $per_page, $offset)
    {
        $this->db->where('user_id', clean_number($user_id));
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('payouts');
        return $query->result();
    }

    //get paginated user payouts count
    public function get_paginated_user_payouts_count($user_id)
    {
        $this->db->where('user_id', clean_number($user_id));
        $this->db->select('COUNT(id) AS count');
        $query = $this->db->get('payouts');
        return $query->row()->count;
    }

    //get user payout account
    public function get_user_payout_account($user_id)
    {
        $user_id = clean_number($user_id);
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('user_payout_accounts');
        $row = $query->row();

        if (!empty($row)) {
            return $row;
        } else {
            $data = array(
                'user_id' => $user_id,
                'payout_paypal_email' => "",
                'iban_full_name' => "",
                'iban_country' => "",
                'iban_bank_name' => "",
                'iban_number' => "",
                'swift_full_name' => "",
                'swift_address' => "",
                'swift_state' => "",
                'swift_city' => "",
                'swift_postcode' => "",
                'swift_country' => "",
                'swift_bank_account_holder_name' => "",
                'swift_iban' => "",
                'swift_code' => "",
                'swift_bank_name' => "",
                'swift_bank_branch_city' => "",
                'swift_bank_branch_country' => "",
                'default_payout_account' => ""
            );
            $this->db->insert('user_payout_accounts', $data);

            $this->db->where('user_id', $user_id);
            $query = $this->db->get('user_payout_accounts');
            return $query->row();
        }
    }

    //set paypal payout account
    public function set_paypal_payout_account()
    {
        $data = array(
            'payout_paypal_email' => $this->input->post('payout_paypal_email', true)
        );
        if ($this->input->post('default_payout_account') == 'paypal') {
            $data['default_payout_account'] = 'paypal';
        }
        $this->db->where('user_id', clean_number($this->auth_user->id));
        return $this->db->update('user_payout_accounts', $data);
    }

    //set iban payout account
    public function set_iban_payout_account()
    {
        $data = array(
            'iban_full_name' => $this->input->post('iban_full_name', true),
            'iban_country' => $this->input->post('iban_country', true),
            'iban_bank_name' => $this->input->post('iban_bank_name', true),
            'iban_number' => $this->input->post('iban_number', true),
        );
        if ($this->input->post('default_payout_account') == 'iban') {
            $data['default_payout_account'] = 'iban';
        }
        $this->db->where('user_id', clean_number($this->auth_user->id));
        return $this->db->update('user_payout_accounts', $data);
    }

    //set swift payout account
    public function set_swift_payout_account()
    {
        $data = array(
            'swift_full_name' => $this->input->post('swift_full_name', true),
            'swift_address' => $this->input->post('swift_address', true),
            'swift_state' => $this->input->post('swift_state', true),
            'swift_city' => $this->input->post('swift_city', true),
            'swift_postcode' => $this->input->post('swift_postcode', true),
            'swift_country' => $this->input->post('swift_country', true),
            'swift_bank_account_holder_name' => $this->input->post('swift_bank_account_holder_name', true),
            'swift_iban' => $this->input->post('swift_iban', true),
            'swift_code' => $this->input->post('swift_code', true),
            'swift_bank_name' => $this->input->post('swift_bank_name', true),
            'swift_bank_branch_city' => $this->input->post('swift_bank_branch_city', true),
            'swift_bank_branch_country' => $this->input->post('swift_bank_branch_country', true)
        );
        if ($this->input->post('default_payout_account') == 'swift') {
            $data['default_payout_account'] = 'swift';
        }
        $this->db->where('user_id', clean_number($this->auth_user->id));
        return $this->db->update('user_payout_accounts', $data);
    }

    //get paginated pageviews
    public function get_paginated_pageviews($per_page, $offset)
    {
        $this->db->join('users', 'users.id = post_pageviews_month.post_user_id');
        $this->db->select('post_pageviews_month.*, users.username AS author_username, users.slug AS author_slug');
        $this->filter_pageviews();
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('post_pageviews_month');
        return $query->result();
    }

    //get paginated pageviews count
    public function get_paginated_pageviews_count()
    {
        $this->db->join('users', 'users.id = post_pageviews_month.post_user_id');
        $this->db->select('COUNT(post_pageviews_month.id) AS count');
        $this->filter_pageviews();
        $query = $this->db->get('post_pageviews_month');
        return $query->row()->count;
    }

    //pageviews filter
    public function filter_pageviews()
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('users.username', clean_str($q));
            $this->db->or_like('ip_address', clean_str($q));
            $this->db->or_like('user_agent', clean_str($q));
            $this->db->group_end();
        }
    }

    //enable disable reward system
    public function enable_disable_reward_system($id)
    {
        $user = get_user($id);
        if (!empty($user)) {
            $data = array(
                'reward_system_enabled' => 1
            );
            if ($user->reward_system_enabled == 1) {
                $data['reward_system_enabled'] = 0;
            }
            $this->db->where('id', $user->id);
            return $this->db->update('users', $data);
        }
        return false;
    }
}