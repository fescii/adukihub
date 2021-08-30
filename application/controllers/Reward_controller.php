<?php defined('BASEPATH') or exit('No direct script access allowed');

class Reward_controller extends Admin_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        check_permission('categories');
        $this->load->model('reward_model');
    }

    /**
     * Reward System
     */
    public function reward_system()
    {
        $data['title'] = trans("reward_system");
        
        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/reward/reward_system', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Update Settings Post
     */
    public function update_settings_post()
    {
        if ($this->reward_model->update_settings()) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_reward_settings', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Update Payout Post
     */
    public function update_payout_post()
    {
        if ($this->reward_model->update_payout_methods()) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_payout_methods', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Update Currency Post
     */
    public function update_currency_post()
    {
        if ($this->reward_model->update_currency()) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        $this->session->set_flashdata('msg_reward_currency', 1);
        redirect($this->agent->referrer());
    }

    /**
     * Earnings
     */
    public function earnings()
    {
        $data['title'] = trans("earnings");
        
        //paginate
        $pagination = $this->paginate(admin_url() . 'reward-system/earnings', $this->reward_model->get_paginated_earnings_count());
        $data['earnings'] = $this->reward_model->get_paginated_earnings($pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/reward/earnings', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Update Balance Post
     */
    public function update_balance_post()
    {
        $user_id = $this->input->post('user_id');
        if ($this->reward_model->update_balance($user_id)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Payouts
     */
    public function payouts()
    {
        $data['title'] = trans("payouts");
        //paginate
        $pagination = $this->paginate(admin_url() . 'reward-system/payouts', $this->reward_model->get_paginated_payouts_count());
        $data['payouts'] = $this->reward_model->get_paginated_payouts($pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/reward/payouts', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Payout
     */
    public function add_payout()
    {
        $data['title'] = trans("add_payout");
        $data['users'] = $this->reward_model->get_earnings();

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/reward/add_payout', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Add Payout Post
     */
    public function add_payout_post()
    {
        $user_id = $this->input->post('user_id', true);
        $user = get_user($user_id);
        $amount = $this->input->post('amount');
        if (!empty($user)) {
            if ($user->balance < $amount) {
                $this->session->set_flashdata('error', trans("insufficient_balance"));
            } else {
                if ($this->reward_model->add_payout($user, $amount)) {
                    $this->session->set_flashdata('success', trans("msg_payout_added"));
                } else {
                    $this->session->set_flashdata('error', trans("msg_error"));
                }
            }
        }
        redirect($this->agent->referrer());
    }

    /**
     * Delete Payout Post
     */
    public function delete_payout_post()
    {
        $id = $this->input->post('id', true);
        if ($this->reward_model->delete_payout($id)) {
            $this->session->set_flashdata('success', trans("msg_deleted"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

    /**
     * Pageviews
     */
    public function pageviews()
    {
        $data['title'] = trans("pageviews");
        
        //paginate
        $pagination = $this->paginate(admin_url() . 'reward-system/pageviews', $this->reward_model->get_paginated_pageviews_count());
        $data['pageviews'] = $this->reward_model->get_paginated_pageviews($pagination['per_page'], $pagination['offset']);

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/reward/pageviews', $data);
        $this->load->view('admin/includes/_footer');
    }

    /**
     * Enable or Disable Reward System Post
     */
    public function enable_disable_reward_system()
    {
        if (!check_user_permission('users')) {
            exit();
        }
        $id = $this->input->post('id', true);
        if ($this->reward_model->enable_disable_reward_system($id)) {
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
        }
    }

}
