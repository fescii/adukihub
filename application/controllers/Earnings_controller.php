<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Earnings_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->auth_check) {
            redirect(lang_base_url());
        }
        if ($this->auth_user->reward_system_enabled != 1) {
            redirect(lang_base_url());
            exit();
        }
        $this->load->model('reward_model');
    }

    /**
     * Earnings Page
     */
    public function earnings()
    {
        get_method();
        $data['title'] = trans("earnings");
        $data['description'] = trans("earnings") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("earnings") . ', ' . $this->settings->application_name;
        $data['user'] = $this->auth_user;
        $data['active_tab'] = "earnings";
        
        //user posts count
        $data["user_posts_count"] = $this->post_model->get_post_count_by_user($data['user']->id);


        $data["page_views_counts"] = $this->reward_model->get_page_views_counts_by_date($data['user']->id);
        $data["number_of_days"] = date('t');
        if (empty($data["number_of_days"])) {
            $data["number_of_days"] = 30;
        }
        $data["today"] = date('d');

        $this->load->view('partials/_header', $data);
        $this->load->view('earnings/earnings', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Payouts Page
     */
    public function payouts()
    {
        get_method();
        $data['title'] = trans("payouts");
        $data['description'] = trans("payouts") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("payouts") . ', ' . $this->settings->application_name;
        $data['user'] = $this->auth_user;
        
        $data['active_tab'] = "payouts";
        //user posts count
        $data["user_posts_count"] = $this->post_model->get_post_count_by_user($data['user']->id);

        //paginate
        $pagination = $this->paginate(get_route("payouts"), $this->reward_model->get_paginated_user_payouts_count($data['user']->id));
        $data['payouts'] = $this->reward_model->get_paginated_user_payouts($data['user']->id, $pagination['per_page'], $pagination['offset']);

        $this->load->view('partials/_header', $data);
        $this->load->view('earnings/payouts', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Set Payout Account
     */
    public function set_payout_account()
    {
        $data['title'] = trans("set_payout_account");
        $data['description'] = trans("set_payout_account") . " - " . $this->settings->site_title;
        $data['keywords'] = trans("set_payout_account") . "," . $this->settings->application_name;
        $data["active_tab"] = "set_payout_account";
        
        $data['user'] = $this->auth_user;
        //user posts count
        $data["user_posts_count"] = $this->post_model->get_post_count_by_user($data['user']->id);

        $data['user_payout'] = $this->reward_model->get_user_payout_account($data['user']->id);

        if (empty($this->session->flashdata('msg_payout'))) {
            if ($this->general_settings->payout_paypal_status) {
                $this->session->set_flashdata('msg_payout', "paypal");
            } elseif ($this->general_settings->payout_iban_status) {
                $this->session->set_flashdata('msg_payout', "iban");
            } elseif ($this->general_settings->payout_swift_status) {
                $this->session->set_flashdata('msg_payout', "swift");
            }
        }

        $this->load->view('partials/_header', $data);
        $this->load->view('earnings/set_payout_account', $data);
        $this->load->view('partials/_footer');
    }

    /**
     * Set Paypal Payout Account Post
     */
    public function set_paypal_payout_account_post()
    {
        if ($this->reward_model->set_paypal_payout_account()) {
            $this->session->set_flashdata('msg_payout', "paypal");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('msg_payout', "paypal");
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Set IBAN Payout Account Post
     */
    public function set_iban_payout_account_post()
    {
        if ($this->reward_model->set_iban_payout_account()) {
            $this->session->set_flashdata('msg_payout', "iban");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('msg_payout', "iban");
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }

    /**
     * Set SWIFT Payout Account Post
     */
    public function set_swift_payout_account_post()
    {
        if ($this->reward_model->set_swift_payout_account()) {
            $this->session->set_flashdata('msg_payout', "swift");
            $this->session->set_flashdata('success', trans("msg_updated"));
        } else {
            $this->session->set_flashdata('msg_payout', "swift");
            $this->session->set_flashdata('error', trans("msg_error"));
        }
        redirect($this->agent->referrer());
    }
}
