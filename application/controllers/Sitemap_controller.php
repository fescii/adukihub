<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sitemap_controller extends Admin_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
        check_permission('seo_tools');
        $this->load->model('sitemap_model');
    }

    /**
     * Generate Sitemap
     */
    public function generate_sitemap()
    {
        $data['title'] = trans("generate_sitemap");

        $this->load->view('admin/_header', $data);
        $this->load->view('admin/generate_sitemap', $data);
        $this->load->view('admin/_footer');
    }

    /**
     * Generate Sitemap Post
     */
    public function generate_sitemap_post()
    {
        //update sitemap settings
        $this->sitemap_model->update_sitemap_settings();
        $this->sitemap_model->generate_sitemap();
        $this->session->set_flashdata('success_form', trans("sitemap") . " " . trans("msg_suc_updated"));
        redirect($this->agent->referrer());
    }

    /**
     * Download File
     */
    public function delete_sitemap()
    {
        $path = $this->input->post('path', true);
        if (file_exists($path)) {
            @unlink($path);
        }
        redirect($this->agent->referrer());
        exit();
    }

}

