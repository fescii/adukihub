<?php defined('BASEPATH') or exit('No direct script access allowed');

class Ajax_controller extends Home_Core_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_ajax_request()) {
            exit();
        }
    }

    /**
     * Run Internal Cron
     */
    public function run_internal_cron()
    {
        //delete old posts
        if ($this->general_settings->auto_post_deletion == 1) {
            $this->post_model->delete_old_posts();
        }

        //delete old page views
        $this->post_model->delete_old_pageviews();

        //delete old sessions
        $this->settings_model->delete_old_sessions();

        //add last update
        $this->db->where('id', 1);
        $this->db->update('general_settings', ['last_cron_update' => date('Y-m-d H:i:s')]);
    }

    /**
     * Add to Newsletter
     */
    public function add_to_newsletter()
    {
        post_method();
        $vld = $this->input->post('url', true);
        if (!empty($vld)) {
            exit();
        }
        $data = array(
            'result' => 0,
            'response' => "",
            'is_success' => "",
        );
        $email = clean_str($this->input->post('email', true));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $data['response'] = '<p class="text-danger m-t-5">' . trans("message_invalid_email") . '</p>';
        } else {
            if ($email) {
                if (empty($this->newsletter_model->get_subscriber($email))) {
                    if ($this->newsletter_model->add_subscriber($email)) {
                        $data['response'] = '<p class="text-success m-t-5">' . trans("message_newsletter_success") . '</p>';
                        $data['is_success'] = 1;
                        $this->close_newsletter_popup();
                    }
                } else {
                    $data['response'] = '<p class="text-danger m-t-5">' . trans("message_newsletter_error") . '</p>';
                }
            }
        }
        $data['result'] = 1;
        echo json_encode($data);
    }


    /**
     * Set cookie for newsletter popup
     */
    public function close_newsletter_popup()
    {
        helper_setcookie('vr_news_p', 1);
    }

    /**
     * Get Quiz Answers
     */
    public function get_quiz_answers()
    {
        post_method();
        $post_id = $this->input->post('post_id', true);
        $array_quiz_answers = array();
        $questions = $this->quiz_model->get_quiz_questions($post_id);
        if (!empty($questions)) {
            $i = 0;
            foreach ($questions as $question) {
                $correct_answer = $this->quiz_model->get_quiz_question_correct_answer($question->id);
                if (!empty($correct_answer)) {
                    $item = array($question->id, $correct_answer->id);
                    array_push($array_quiz_answers, $item);
                }
                $i++;
            }
        }
        $data = array(
            'result' => 1,
            'array_quiz_answers' => $array_quiz_answers,
        );
        echo json_encode($data);
    }

    /**
     * Get Quiz Results
     */
    public function get_quiz_results()
    {
        post_method();
        $post_id = $this->input->post('post_id', true);
        $array_quiz_results = array();
        $results = $this->quiz_model->get_quiz_results($post_id);
        if (!empty($results)) {
            foreach ($results as $result) {
                $vars = array('result' => $result);
                $html_content = $this->load->view('post/details/_quiz_result', $vars, true);
                //array: [0]: result id, [1]: min correct, [2]: max correct, [3]: html content
                $item = array($result->id, $result->min_correct_count, $result->max_correct_count, $html_content);
                array_push($array_quiz_results, $item);
            }
        }
        $data = array(
            'result' => 1,
            'array_quiz_results' => $array_quiz_results,
        );
        echo json_encode($data);
    }

    /**
     * Show Popular Posts
     */
    public function get_popular_posts()
    {
        $date_type = $this->input->post('date_type', true);
        $lang_id = $this->input->post('lang_id', true);
        $popular_posts = "";
        $html_content = "";

        //set language
        if ($this->general_settings->site_lang != $lang_id) {
            $lang = $this->language_model->get_language($lang_id);
            if (!empty($lang)) {
                $this->lang_base_url = base_url() . $lang->short_form . "/";
            }
        }
        $popular_posts = get_popular_posts($date_type, $lang_id);
        $data = array(
            'result' => 1,
            'html_content' => "",
        );
        if (!empty($popular_posts)) {
            foreach ($popular_posts as $post) {
                $vars = array('post' => $post);
                $html_content .= '<ul class="popular-posts"><li>' . $this->load->view('post/_post_item_small', $vars, true) . '</li></ul>';
            }
            $data = array(
                'result' => 1,
                'html_content' => $html_content,
            );
        }
        echo json_encode($data);
    }

    /**
     * Add or Delete Reading List
     */
    public function add_delete_reading_list_post()
    {
        post_method();
        $post_id = clean_number($this->input->post('post_id'));
        $is_post_in_reading_list = $this->reading_list_model->is_post_in_reading_list($post_id);
        if ($is_post_in_reading_list == true) {
            $this->reading_list_model->delete_from_reading_list($post_id);
        } else {
            $this->reading_list_model->add_to_reading_list($post_id);
        }
    }
}
