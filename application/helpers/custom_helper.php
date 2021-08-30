<?php
/*
 * Custom Helpers
 *
 */

if (strpos($_SERVER['REQUEST_URI'], '/index.php') !== false) {
    $ci =& get_instance();
    $ci->load->helper('url');
    redirect(current_url());
    exit();
}

//current full url
if (!function_exists('current_full_url')) {
    function current_full_url()
    {
        $current_url = current_url();
        if (!empty($_SERVER['QUERY_STRING'])) {
            $current_url = $current_url . "?" . $_SERVER['QUERY_STRING'];
        }
        return $current_url;
    }
}

//post method
if (!function_exists('post_method')) {
    function post_method()
    {
        $ci =& get_instance();
        if ($ci->input->method(FALSE) != 'post') {
            exit();
        }
    }
}

//get method
if (!function_exists('get_method')) {
    function get_method()
    {
        $ci =& get_instance();
        if ($ci->input->method(FALSE) != 'get') {
            exit();
        }
    }
}

//get
if (!function_exists('input_get')) {
    function input_get($input_name)
    {
        $ci =& get_instance();
        return clean_str($ci->input->get($input_name, true));
    }
}

//lang base url
if (!function_exists('lang_base_url')) {
    function lang_base_url()
    {
        $ci =& get_instance();
        return $ci->lang_base_url;
    }
}

//current full url
if (!function_exists('current_full_url')) {
    function current_full_url()
    {
        $current_url = current_url();
        if (!empty($_SERVER['QUERY_STRING'])) {
            $current_url = $current_url . "?" . $_SERVER['QUERY_STRING'];
        }
        return $current_url;
    }
}

//check auth
if (!function_exists('auth_check')) {
    function auth_check()
    {
        $ci =& get_instance();
        return $ci->auth_model->is_logged_in();
    }
}

//check admin
if (!function_exists('is_admin')) {
    function is_admin()
    {
        $ci =& get_instance();
        return $ci->auth_model->is_admin();
    }
}

//check user permission
if (!function_exists('check_user_permission')) {
    function check_user_permission($section)
    {
        $ci =& get_instance();
        if ($ci->auth_check) {
            $user_role = $ci->auth_user->role;
            if ($user_role == 'admin') {
                return true;
            }
            $role_permission = array_filter($ci->roles_permissions, function ($item) use ($user_role) {
                return $item->role == $user_role;
            });
            foreach ($role_permission as $key => $value) {
                $role_permission = $value;
                break;
            }
            if (!empty($role_permission) && $role_permission->$section == 1) {
                return true;
            }
        }
        return false;
    }
}

//check permission
if (!function_exists('check_permission')) {
    function check_permission($section)
    {
        if (!check_user_permission($section)) {
            redirect(lang_base_url());
        }
    }
}

//check post delete permission
if (!function_exists('check_post_ownership')) {
    function check_post_ownership($post_owner_id)
    {
        $ci =& get_instance();
        if ($ci->auth_user->id == $post_owner_id) {
            return true;
        }
        return check_user_permission('manage_all_posts');
    }
}

//check permission
if (!function_exists('check_admin')) {
    function check_admin()
    {
        if (!is_admin()) {
            redirect(lang_base_url());
        }
    }
}


//admin url
if (!function_exists('admin_url')) {
    function admin_url()
    {
        $ci =& get_instance();
        return base_url() . $ci->routes->admin . '/';
    }
}

//generate base url
if (!function_exists('generate_base_url')) {
    function generate_base_url($lang)
    {
        $ci =& get_instance();
        if (!empty($lang)) {
            if ($ci->selected_lang->id == $lang->id) {
                return base_url();
            }
            return base_url() . $lang->short_form . "/";
        }
        return lang_base_url();
    }
}

//get site color
if (!function_exists('get_site_color')) {
    function get_site_color()
    {
        $ci =& get_instance();
        if ($ci->auth_check) {
            if (!empty($ci->auth_user)) {
                if (!empty($ci->auth_user->site_color)) {
                    return $ci->auth_user->site_color;
                }
            }
        }
        return $ci->visual_settings->site_color;
    }
}

//check if dark mode enabled
if (!function_exists('check_dark_mode_enabled')) {
    function check_dark_mode_enabled()
    {
        $ci =& get_instance();
        $dark_mode = $ci->visual_settings->dark_mode;
        $ck_name = COOKIE_PREFIX . '_vr_dark_mode';
        if (isset($_COOKIE[$ck_name])) {
            if ($_COOKIE[$ck_name] == 1 || $_COOKIE[$ck_name] == 0) {
                $dark_mode = $_COOKIE[$ck_name];
            }
        }
        return $dark_mode;
    }
}

//get route
if (!function_exists('get_route')) {
    function get_route($key, $slash = false)
    {
        $ci =& get_instance();
        $route = $key;
        if (!empty($ci->routes->$key)) {
            $route = $ci->routes->$key;
            if ($slash == true) {
                $route .= '/';
            }
        }
        return $route;
    }
}

//generate post url
if (!function_exists('generate_post_url')) {
    function generate_post_url($post, $base_url = null)
    {
        $ci =& get_instance();
        if ($base_url == null) {
            $base_url = lang_base_url();
        }
        if (!empty($post)) {
            if (!empty($post->post_url) && $ci->general_settings->redirect_rss_posts_to_original == 1) {
                return $post->post_url;
            }
            return $base_url . $post->title_slug;
        }
        return "#";
    }
}

//generate category url
if (!function_exists('generate_category_url')) {
    function generate_category_url($category)
    {
        if (!empty($category)) {
            if (!empty($category->parent_slug)) {
                return lang_base_url() . $category->parent_slug . "/" . $category->name_slug;
            } else {
                return lang_base_url() . $category->name_slug;
            }
        }
        return "#";
    }
}

//generate category url by id
if (!function_exists('generate_category_url_by_id')) {
    function generate_category_url_by_id($category_id)
    {
        $ci =& get_instance();
        $category = get_category(clean_number($category_id), $ci->categories);
        if (!empty($category)) {
            if (!empty($category->parent_slug)) {
                return lang_base_url() . $category->parent_slug . "/" . $category->name_slug;
            } else {
                return lang_base_url() . $category->name_slug;
            }
        }
        return "#";
    }
}

//generate tag url
if (!function_exists('generate_tag_url')) {
    function generate_tag_url($tag_slug)
    {
        if (!empty($tag_slug)) {
            return lang_base_url() . get_route('tag', true) . $tag_slug;
        }
        return "#";
    }
}

//generate profile url
if (!function_exists('generate_profile_url')) {
    function generate_profile_url($user_slug)
    {
        if (!empty($user_slug)) {
            return lang_base_url() . get_route('profile', true) . $user_slug;
        }
        return "#";
    }
}

//generate static url
if (!function_exists('generate_url')) {
    function generate_url($route_1, $route_2 = null)
    {
        if (!empty($route_2)) {
            return lang_base_url() . get_route($route_1, true) . get_route($route_2);
        } else {
            return lang_base_url() . get_route($route_1);
        }
    }
}

//generate menu item url
if (!function_exists('generate_menu_item_url')) {
    function generate_menu_item_url($item)
    {
        $ci =& get_instance();
        if (empty($item)) {
            return lang_base_url() . "#";
        }
        if ($item->item_type == 'page') {
            if (!empty($item->item_link)) {
                return $item->item_link;
            } else {
                return lang_base_url() . $item->item_slug;
            }
        } elseif ($item->item_type == 'category') {
            $category = get_category($item->item_id, $ci->categories);
            if (!empty($category)) {
                if (!empty($category->parent_slug)) {
                    return lang_base_url() . $category->parent_slug . "/" . $category->name_slug;
                } else {
                    return lang_base_url() . $category->name_slug;
                }
            }
        } else {
            return lang_base_url() . "#";
        }
    }
}

//add new tab for post url
if (!function_exists('post_url_new_tab')) {
    function post_url_new_tab($ci, $post)
    {
        if (!empty($post)) {
            if (!empty($post->post_url) && $ci->general_settings->redirect_rss_posts_to_original == 1) {
                echo ' target="_blank"';
            }
        }
    }
}


//get logged user
if (!function_exists('user')) {
    function user()
    {
        $ci =& get_instance();
        $user = $ci->auth_model->get_logged_user();
        if (empty($user)) {
            $ci->auth_model->logout();
            redirect($ci->agent->referrer());
            exit();
        } else {
            return $user;
        }
    }
}

//get user by id
if (!function_exists('get_user')) {
    function get_user($user_id)
    {
        $ci =& get_instance();
        return $ci->auth_model->get_user($user_id);
    }
}

//get parent link
if (!function_exists('helper_get_parent_link')) {
    function helper_get_parent_link($parent_id, $type)
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        return $ci->navigation_model->get_parent_link($parent_id, $type);
    }
}

//get sub menu links
if (!function_exists('get_sub_menu_links')) {
    function get_sub_menu_links($menu_links, $parent_id, $type)
    {
        $ci =& get_instance();
        $sub_links = array();
        if (!empty($menu_links)) {
            $sub_links = array_filter($menu_links, function ($item) use ($parent_id, $type) {
                return $item->item_type == $type && $item->item_parent_id == $parent_id;
            });
        }
        return $sub_links;
    }
}

//get navigation item type
if (!function_exists('get_navigation_item_type')) {
    function get_navigation_item_type($menu_item)
    {
        if (!empty($menu_item)) {
            if ($menu_item->item_type == "category") {
                return trans("category");
            } else {
                if (!empty($menu_item->item_link)) {
                    return trans("link");
                } else {
                    return trans("page");
                }
            }
        }
    }
}

//get navigation item edit link
if (!function_exists('get_navigation_item_edit_link')) {
    function get_navigation_item_edit_link($menu_item)
    {
        if (!empty($menu_item)) {
            if ($menu_item->item_type == "category") {
                if ($menu_item->item_parent_id == 0) {
                    return admin_url() . 'update-category/' . $menu_item->item_id . '?redirect_url=' . current_url() . '?' . $_SERVER['QUERY_STRING'];
                } else {
                    return admin_url() . 'update-subcategory/' . $menu_item->item_id . '?redirect_url=' . current_url() . '?' . $_SERVER['QUERY_STRING'];
                }
            } else {
                if (!empty($menu_item->item_link)) {
                    return admin_url() . 'update-menu-link/' . $menu_item->item_id;
                } else {
                    return admin_url() . 'update-page/' . $menu_item->item_id . '?redirect_url=' . current_url() . '?' . $_SERVER['QUERY_STRING'];
                }
            }
        }
    }
}

//get navigation item delete function
if (!function_exists('get_navigation_item_delete_function')) {
    function get_navigation_item_delete_function($menu_item)
    {
        if (!empty($menu_item)) {
            if ($menu_item->item_type == "category") {
                return "delete_item('category_controller/delete_category_post','" . $menu_item->item_id . "','" . trans("confirm_category") . "');";
            } else {
                if (!empty($menu_item->item_link)) {
                    return "delete_item('admin_controller/delete_navigation_post','" . $menu_item->item_id . "','" . trans("confirm_link") . "');";
                } else {
                    return "delete_item('page_controller/delete_page_post','" . $menu_item->item_id . "','" . trans("confirm_page") . "');";
                }
            }
        }
    }
}

//check post image exist
if (!function_exists('check_post_img')) {
    function check_post_img($post, $type = '')
    {
        $is_exist = false;
        if (!empty($post)) {
            if (!empty($post->image_mid) || !empty($post->image_small) || !empty($post->image_url)) {
                $is_exist = true;
            }
        }
        if ($is_exist == false && $type == 'class') {
            echo " post-item-no-image";
        } else {
            if ($type != 'class') {
                return $is_exist;
            }
        }
    }
}

//get post images
if (!function_exists('get_post_image')) {
    function get_post_image($post, $image_size)
    {
        $ci =& get_instance();
        if (!empty($post)) {
            if (!empty($post->image_url)) {
                return $post->image_url;
            } else {
                $path = "";
                if ($image_size == "big") {
                    $path = $post->image_big;
                } elseif ($image_size == "default") {
                    $path = $post->image_default;
                } elseif ($image_size == "slider") {
                    $path = $post->image_slider;
                } elseif ($image_size == "mid") {
                    $path = $post->image_mid;
                } elseif ($image_size == "small") {
                    $path = $post->image_small;
                }
                if ($post->image_storage == "aws_s3") {
                    $path = $ci->aws_base_url . $path;
                } else {
                    $path = base_url() . $path;
                }
                return $path;
            }
        }
    }
}


//get post images
if (!function_exists('get_post_additional_images')) {
    function get_post_additional_images($post_id)
    {
        $ci =& get_instance();
        return $ci->post_file_model->get_post_additional_images($post_id);
    }
}

//get post files
if (!function_exists('get_post_files')) {
    function get_post_files($post_id)
    {
        $ci =& get_instance();
        return $ci->post_file_model->get_post_files($post_id);
    }
}

//get post audios
if (!function_exists('get_post_audios')) {
    function get_post_audios($post_id)
    {
        $ci =& get_instance();
        return $ci->post_file_model->get_post_audios($post_id);
    }
}


//get ad codes
if (!function_exists('get_ad_codes')) {
    function get_ad_codes($ad_space)
    {
        $ci =& get_instance();
        $ad = null;
        if (!empty($ci->ads)) {
            $ad = array_filter($ci->ads, function ($item) use ($ad_space) {
                return $item->ad_space == $ad_space;
            });
            foreach ($ad as $key => $value) {
                $ad = $value;
                break;
            }
        }
        return $ad;
    }
}

//get translated message
if (!function_exists('trans')) {
    function trans($string)
    {
        $ci =& get_instance();
        if (!empty($ci->language_translations[$string])) {
            return $ci->language_translations[$string];
        }
        return "";
    }
}

//print old form data
if (!function_exists('old')) {
    function old($field)
    {
        $ci =& get_instance();
        if (isset($ci->session->flashdata('form_data')[$field])) {
            return html_escape($ci->session->flashdata('form_data')[$field]);
        }
    }
}

//delete image from server
if (!function_exists('delete_image_from_server')) {
    function delete_image_from_server($path)
    {
        $full_path = FCPATH . $path;
        if (strlen($path) > 15 && file_exists($full_path)) {
            unlink($full_path);
        }
    }
}

//delete file from server
if (!function_exists('delete_file_from_server')) {
    function delete_file_from_server($path)
    {
        $full_path = FCPATH . $path;
        if (strlen($path) > 15 && file_exists($full_path)) {
            unlink($full_path);
        }
    }
}

//get user avatar
if (!function_exists('get_user_avatar')) {
    function get_user_avatar($avatar_path)
    {
        $ci =& get_instance();
        if (!empty($avatar_path)) {
            if (file_exists(FCPATH . $avatar_path)) {
                return base_url() . $avatar_path;
            } else {
                return $avatar_path;
            }
        } else {
            return base_url() . "assets/img/user.png";
        }
    }
}

//get page by default name
if (!function_exists('get_page_by_default_name')) {
    function get_page_by_default_name($default_name, $lang_id)
    {
        $ci =& get_instance();
        return $ci->page_model->get_page_by_default_name($default_name, $lang_id);
    }
}

//get page link by default name
if (!function_exists('get_page_link_by_default_name')) {
    function get_page_link_by_default_name($default_name, $lang_id)
    {
        $ci =& get_instance();
        $page = get_page_by_default_name($default_name, $lang_id);
        if (!empty($page)) {
            return lang_base_url() . $page->slug;
        }
        return "#";
    }
}

//get page title
if (!function_exists('get_page_title')) {
    function get_page_title($page)
    {
        if (!empty($page)) {
            return html_escape($page->title);
        } else {
            return "";
        }
    }
}

//get page description
if (!function_exists('get_page_description')) {
    function get_page_description($page)
    {
        if (!empty($page)) {
            return html_escape($page->description);
        } else {
            return "";
        }
    }
}

//get page keywords
if (!function_exists('get_page_keywords')) {
    function get_page_keywords($page)
    {
        if (!empty($page)) {
            return html_escape($page->keywords);
        } else {
            return "";
        }
    }
}

//get subcomments
if (!function_exists('get_subcomments')) {
    function get_subcomments($comment_id)
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        return $ci->comment_model->get_subcomments($comment_id);
    }
}

//check if comment voted
if (!function_exists('is_comment_voted')) {
    function is_comment_voted($comment_id)
    {
        if (!empty(helper_getcookie('comment_voted_' . $comment_id))) {
            return true;
        }
        return false;
    }
}

//check comment owner
if (!function_exists('check_comment_owner')) {
    function check_comment_owner($comment)
    {
        $ci =& get_instance();
        if ($ci->auth_check) {
            if ($comment->user_id == $ci->auth_user->id) {
                return true;
            }
        } else {
            if (!empty(helper_getcookie('added_comment_id_' . $comment->id))) {
                return true;
            }
        }
        return false;
    }
}

//calculate total vote of poll option
if (!function_exists('calculate_total_vote_poll_option')) {
    function calculate_total_vote_poll_option($poll)
    {
        $total = 0;
        if (!empty($poll)) {
            for ($i = 1; $i <= 10; $i++) {
                $op = "option{$i}_vote_count";
                $total += $poll->$op;
            }
        }
        return $total;
    }
}

//date format
if (!function_exists('replace_month_name')) {
    function replace_month_name($str)
    {
        $str = trim($str);
        $str = str_replace("Jan", trans("January"), $str);
        $str = str_replace("Feb", trans("February"), $str);
        $str = str_replace("Mar", trans("March"), $str);
        $str = str_replace("Apr", trans("April"), $str);
        $str = str_replace("May", trans("May"), $str);
        $str = str_replace("Jun", trans("June"), $str);
        $str = str_replace("Jul", trans("July"), $str);
        $str = str_replace("Aug", trans("August"), $str);
        $str = str_replace("Sep", trans("September"), $str);
        $str = str_replace("Oct", trans("October"), $str);
        $str = str_replace("Nov", trans("November"), $str);
        $str = str_replace("Dec", trans("December"), $str);
        return $str;
    }
}

//date format
if (!function_exists('helper_date_format')) {
    function helper_date_format($datetime)
    {
        $date = date("M j, Y", strtotime($datetime));
        $date = replace_month_name($date);
        return $date;
    }
}

//get logo
if (!function_exists('get_logo')) {
    function get_logo($visual_settings)
    {
        if (!empty($visual_settings)) {
            if (!empty($visual_settings->logo) && file_exists(FCPATH . $visual_settings->logo)) {
                return base_url() . $visual_settings->logo;
            } else {
                return base_url() . "assets/img/logo.svg";
            }
        } else {
            return base_url() . "assets/img/logo.svg";
        }
    }
}

//get logo footer
if (!function_exists('get_logo_footer')) {
    function get_logo_footer($visual_settings)
    {
        if (!empty($visual_settings)) {
            if (!empty($visual_settings->logo_footer) && file_exists(FCPATH . $visual_settings->logo_footer)) {
                return base_url() . $visual_settings->logo_footer;
            } else {
                return base_url() . "assets/img/logo-footer.svg";
            }
        } else {
            return base_url() . "assets/img/logo-footer.svg";
        }
    }
}

//get logo email
if (!function_exists('get_logo_email')) {
    function get_logo_email($visual_settings)
    {
        if (!empty($visual_settings)) {
            if (!empty($visual_settings->logo_email) && file_exists(FCPATH . $visual_settings->logo_email)) {
                return base_url() . $visual_settings->logo_email;
            } else {
                return base_url() . "assets/img/logo.png";
            }
        } else {
            return base_url() . "assets/img/logo.png";
        }
    }
}

//get favicon
if (!function_exists('get_favicon')) {
    function get_favicon($visual_settings)
    {
        if (!empty($visual_settings)) {
            if (!empty($visual_settings->favicon) && file_exists(FCPATH . $visual_settings->favicon)) {
                return base_url() . $visual_settings->favicon;
            } else {
                return base_url() . "assets/img/favicon.png";
            }
        } else {
            return base_url() . "assets/img/favicon.png";
        }
    }
}

//get settings
if (!function_exists('get_settings')) {
    function get_settings($lang_id)
    {
        $ci =& get_instance();
        $ci->load->model('settings_model');
        return $ci->settings_model->get_settings($lang_id);
    }
}

//get general settings
if (!function_exists('get_general_settings')) {
    function get_general_settings()
    {
        $ci =& get_instance();
        $ci->load->model('settings_model');
        return $ci->settings_model->get_general_settings();
    }
}

//get admin url
if (!function_exists('get_admin_url')) {
    function get_admin_url()
    {
        // Get a reference to the controller object
        $ci =& get_instance();
        $ci->load->model('settings_model');
        $settings = $ci->settings_model->get_general_settings();
        if (!empty($settings)) {
            return $settings->admin_url();
        }
    }
}

//date diff
if (!function_exists('date_difference')) {
    function date_difference($date1, $date2, $format = '%a')
    {
        $datetime_1 = date_create($date1);
        $datetime_2 = date_create($date2);
        $diff = date_diff($datetime_1, $datetime_2);
        return $diff->format($format);
    }
}

//date difference in hours
if (!function_exists('date_difference_in_hours')) {
    function date_difference_in_hours($date1, $date2)
    {
        $datetime_1 = date_create($date1);
        $datetime_2 = date_create($date2);
        $diff = date_diff($datetime_1, $datetime_2);
        $days = $diff->format('%a');
        $hours = $diff->format('%h');
        return $hours + ($days * 24);
    }
}

//check cron time
if (!function_exists('check_cron_time')) {
    function check_cron_time($hour)
    {
        $ci =& get_instance();
        if (empty($ci->general_settings->last_cron_update) || date_difference_in_hours(date('Y-m-d H:i:s'), $ci->general_settings->last_cron_update) >= $hour) {
            return true;
        }
        return false;
    }
}

//get feed posts count
if (!function_exists('get_feed_posts_count')) {
    function get_feed_posts_count($feed_id)
    {
        $ci =& get_instance();
        return $ci->post_admin_model->get_feed_posts_count($feed_id);
    }
}

//get language
if (!function_exists('get_language')) {
    function get_language($id)
    {
        $ci =& get_instance();
        $language = null;
        if (!empty($ci->languages)) {
            $language = array_filter($ci->languages, function ($item) use ($id) {
                return $item->id == $id;
            });
            foreach ($language as $key => $value) {
                $language = $value;
                break;
            }
        }
        return $language;
    }
}

//set cookie
if (!function_exists('helper_setcookie')) {
    function helper_setcookie($name, $value)
    {
        setcookie(COOKIE_PREFIX . '_' . $name, $value, time() + (86400 * 30), "/"); //30 days
    }
}

//get cookie
if (!function_exists('helper_getcookie')) {
    function helper_getcookie($name, $data_type = 'string')
    {
        if (isset($_COOKIE[COOKIE_PREFIX . '_' . $name])) {
            return $_COOKIE[COOKIE_PREFIX . '_' . $name];
        }
        if ($data_type == 'int') {
            return 0;
        }
        return "";
    }
}

//delete cookie
if (!function_exists('helper_deletecookie')) {
    function helper_deletecookie($name)
    {
        if (!empty(helper_getcookie($name))) {
            setcookie(COOKIE_PREFIX . '_' . $name, "", time() - 3600, "/");
        }
    }
}

//set session
if (!function_exists('helper_setsession')) {
    function helper_setsession($name, $value)
    {
        $ci =& get_instance();
        $ci->session->set_userdata($name, $value);
    }
}

//get session
if (!function_exists('helper_getsession')) {
    function helper_getsession($name, $data_type = 'string')
    {
        $ci =& get_instance();
        if (!empty($ci->session->userdata($name))) {
            return $ci->session->userdata($name);
        }
        if ($data_type == 'int') {
            return 0;
        }
        return "";
    }
}

//get gallery album
if (!function_exists('get_gallery_album')) {
    function get_gallery_album($id)
    {
        $ci =& get_instance();
        return $ci->gallery_category_model->get_album($id);
    }
}

//get gallery category
if (!function_exists('get_gallery_category')) {
    function get_gallery_category($id)
    {
        $ci =& get_instance();
        return $ci->gallery_category_model->get_category($id);
    }
}

//get page keywords
if (!function_exists('get_gallery_cover_image')) {
    function get_gallery_cover_image($album_id)
    {
        $ci =& get_instance();
        return $ci->gallery_model->get_cover_image($album_id);
    }
}

//print date
if (!function_exists('formatted_date')) {
    function formatted_date($timestamp)
    {
        return date("Y-m-d / H:i", strtotime($timestamp));
    }
}

//print formatted hour
if (!function_exists('formatted_hour')) {
    function formatted_hour($timestamp)
    {
        return date("H:i", strtotime($timestamp));
    }
}

//is reaction voted
if (!function_exists('is_reaction_voted')) {
    function is_reaction_voted($post_id, $reaction)
    {
        if (helper_getsession("vr_reaction_" . $reaction . "_" . $post_id) == 1) {
            return true;
        } else {
            return false;
        }
    }
}

//get recaptcha
if (!function_exists('generate_recaptcha')) {
    function generate_recaptcha()
    {
        $ci =& get_instance();
        if ($ci->recaptcha_status) {
            $ci->load->library('recaptcha');
            echo '<div class="form-group">';
            echo $ci->recaptcha->getWidget();
            echo $ci->recaptcha->getScriptTag();
            echo ' </div>';
        }
    }
}

//check user follows
if (!function_exists('is_user_follows')) {
    function is_user_follows($following_id, $follower_id)
    {
        $ci =& get_instance();
        return $ci->profile_model->is_user_follows($following_id, $follower_id);
    }
}

//get file name without extension
if (!function_exists('get_file_name_without_extension')) {
    function get_file_name_without_extension($file_name)
    {
        $ci =& get_instance();
        $ci->load->model('upload_model');
        return $ci->upload_model->get_file_name_without_extension($file_name);
    }
}

//get post base url
if (!function_exists('get_base_url_by_language_id')) {
    function get_base_url_by_language_id($lang_id)
    {
        $ci =& get_instance();
        if ($lang_id == $ci->general_settings->site_lang) {
            return base_url();
        } else {
            $lang = get_language($lang_id);
            if (!empty($lang)) {
                return base_url() . $lang->short_form . "/";
            }
        }
    }
}

//check admin nav
if (!function_exists('get_earning_object_by_day')) {
    function get_earning_object_by_day($day, $page_views_counts)
    {
        $ci =& get_instance();

        if ($day < 10 && strpos($day, '0') == false) {
            $day = str_pad($day, 2, '0', STR_PAD_LEFT);
        }
        $date = date('Y') . '-' . date('m') . '-' . $day;
        $objects = array_filter($page_views_counts, function ($item) use ($date) {
            return $item->date == $date;
        });
        $object = null;
        if (!empty($objects)) {
            foreach ($objects as $key => $value) {
                $object = $value;
                break;
            }
        }
        return $object;
    }
}

//check admin nav
if (!function_exists('is_admin_nav_active')) {
    function is_admin_nav_active($array_nav_items)
    {
        $ci =& get_instance();
        $segment2 = @$ci->uri->segment(2);
        if (!empty($segment2) && !empty($array_nav_items)) {
            if (in_array($segment2, $array_nav_items)) {
                echo ' ' . 'active';
            }
        }
    }
}

//get csv value
if (!function_exists('get_csv_value')) {
    function get_csv_value($array, $key, $data_type = 'string')
    {
        if (!empty($array)) {
            if (!empty($array[$key])) {
                return $array[$key];
            }
        }
        if ($data_type == 'int') {
            return 0;
        }
        return "";
    }
}

//generate unique id
if (!function_exists('generate_unique_id')) {
    function generate_unique_id()
    {
        $id = uniqid("", TRUE);
        $id = str_replace(".", "-", $id);
        return $id . "-" . rand(10000000, 99999999);
    }
}

function time_ago($timestamp)
{
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60);           // value 60 is seconds
    $hours = round($seconds / 3600);           //value 3600 is 60 minutes * 60 sec
    $days = round($seconds / 86400);          //86400 = 24 * 60 * 60;
    $weeks = round($seconds / 604800);          // 7*24*60*60;
    $months = round($seconds / 2629440);     //((365+365+365+365+366)/5/12)*24*60*60
    $years = round($seconds / 31553280);     //(365+365+365+365+366)/5 * 24 * 60 * 60
    if ($seconds <= 60) {
        return trans("just_now");
    } else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "1 " . trans("minute") . " " . trans("ago");
        } else {
            return $minutes . " " . trans("minutes") . " " . trans("ago");
        }
    } else if ($hours <= 24) {
        if ($hours == 1) {
            return "1 " . trans("hour") . " " . trans("ago");
        } else {
            return $hours . " " . trans("hours") . " " . trans("ago");
        }
    } else if ($days <= 30) {
        if ($days == 1) {
            return "1 " . trans("day") . " " . trans("ago");
        } else {
            return $days . " " . trans("days") . " " . trans("ago");
        }
    } else if ($months <= 12) {
        if ($months == 1) {
            return "1 " . trans("month") . " " . trans("ago");
        } else {
            return $months . " " . trans("months") . " " . trans("ago");
        }
    } else {
        if ($years == 1) {
            return "1 " . trans("year") . " " . trans("ago");
        } else {
            return $years . " " . trans("years") . " " . trans("ago");
        }
    }
}

function is_user_online($timestamp)
{
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    $minutes = round($seconds / 60);
    if ($minutes <= 3) {
        return true;
    } else {
        return false;
    }
}

//generate slug
if (!function_exists('str_slug')) {
    function str_slug($str)
    {
        $str = trim($str);
        return url_title(convert_accented_characters($str), "-", true);
    }
}

//clean slug
if (!function_exists('clean_slug')) {
    function clean_slug($slug)
    {
        $ci =& get_instance();
        $slug = urldecode($slug);
        $slug = $ci->security->xss_clean($slug);
        $slug = remove_special_characters($slug, true);
        return $slug;
    }
}

//clean number
if (!function_exists('clean_number')) {
    function clean_number($num)
    {
        $ci =& get_instance();
        $num = trim($num);
        $num = $ci->security->xss_clean($num);
        $num = intval($num);
        return $num;
    }
}

//clean string
if (!function_exists('clean_str')) {
    function clean_str($str)
    {
        $ci =& get_instance();
        $str = $ci->security->xss_clean($str);
        $str = remove_special_characters($str, false);
        return $str;
    }
}

//remove special characters
if (!function_exists('remove_special_characters')) {
    function remove_special_characters($str, $is_slug = false)
    {
        $str = trim($str);
        $str = str_replace('#', '', $str);
        $str = str_replace(';', '', $str);
        $str = str_replace('!', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('(', '', $str);
        $str = str_replace(')', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('+', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('?', '', $str);
        $str = str_replace('[', '', $str);
        $str = str_replace(']', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('`', '', $str);
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('~', '', $str);
        if ($is_slug == true) {
            $str = str_replace(" ", '-', $str);
            $str = str_replace("'", '', $str);
        }
        return $str;
    }
}


//remove forbidden characters
if (!function_exists('remove_forbidden_characters')) {
    function remove_forbidden_characters($str)
    {
        $str = str_replace(';', '', $str);
        $str = str_replace('"', '', $str);
        $str = str_replace('$', '', $str);
        $str = str_replace('%', '', $str);
        $str = str_replace('*', '', $str);
        $str = str_replace('/', '', $str);
        $str = str_replace('\'', '', $str);
        $str = str_replace('<', '', $str);
        $str = str_replace('>', '', $str);
        $str = str_replace('=', '', $str);
        $str = str_replace('?', '', $str);
        $str = str_replace('[', '', $str);
        $str = str_replace(']', '', $str);
        $str = str_replace('\\', '', $str);
        $str = str_replace('^', '', $str);
        $str = str_replace('`', '', $str);
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
        $str = str_replace('|', '', $str);
        $str = str_replace('~', '', $str);
        return $str;
    }
}

//convert xml characters
if (!function_exists('convert_to_xml_character')) {
    function convert_to_xml_character($string)
    {
        $str = str_replace(array('&', '<', '>', '\'', '"'), array('&amp;', '&lt;', '&gt;', '&apos;', '&quot;'), $string);
        $str = str_replace('#45;', '', $str);
        return $str;
    }
}

//price formatted
if (!function_exists('price_formatted')) {
    function price_formatted($price, $decimal_point = 2)
    {
        $ci =& get_instance();
        $thousands_sep = ',';
        $dec_point = '.';
        if (get_thousands_separator() != ',') {
            $thousands_sep = '.';
            $dec_point = ',';
        }
        if (is_int($price)) {
            $price = number_format($price, 0, $dec_point, $thousands_sep);
        } else {
            $price = number_format($price, $decimal_point, $dec_point, $thousands_sep);
        }

        if ($ci->general_settings->currency_symbol_format == "left") {
            $price = "<span>" . $ci->general_settings->currency_symbol . "</span>" . $price;
        } else {
            $price = $price . "<span>" . $ci->general_settings->currency_symbol . "</span>";
        }
        return $price;
    }
}

//get thousands separator
if (!function_exists('get_thousands_separator')) {
    function get_thousands_separator()
    {
        $ci =& get_instance();
        $thousands_separator = ',';
        if ($ci->general_settings->currency_format == 'european') {
            $thousands_separator = '.';
        }
        return $thousands_separator;
    }
}

//count item
if (!function_exists('item_count')) {
    function item_count($items)
    {
        if (!empty($items) && is_array($items)) {
            return count($items);
        }
        return 0;
    }
}

//get social links array
if (!function_exists('get_social_links_array')) {
    function get_social_links_array($settings)
    {
        $ci =& get_instance();
        $array = array();
        if (!empty($ci->settings->facebook_url)) {
            array_push($array, ['name' => 'facebook', 'url' => $ci->settings->facebook_url]);
        }
        if (!empty($ci->settings->twitter_url)) {
            array_push($array, ['name' => 'twitter', 'url' => $ci->settings->twitter_url]);
        }
        if (!empty($ci->settings->pinterest_url)) {
            array_push($array, ['name' => 'pinterest', 'url' => $ci->settings->pinterest_url]);
        }
        if (!empty($ci->settings->instagram_url)) {
            array_push($array, ['name' => 'instagram', 'url' => $ci->settings->instagram_url]);
        }
        if (!empty($ci->settings->linkedin_url)) {
            array_push($array, ['name' => 'linkedin', 'url' => $ci->settings->linkedin_url]);
        }
        if (!empty($ci->settings->vk_url)) {
            array_push($array, ['name' => 'vk', 'url' => $ci->settings->vk_url]);
        }
        if (!empty($ci->settings->telegram_url)) {
            array_push($array, ['name' => 'telegram', 'url' => $ci->settings->telegram_url]);
        }
        if (!empty($ci->settings->youtube_url)) {
            array_push($array, ['name' => 'youtube', 'url' => $ci->settings->youtube_url]);
        }
        return $array;
    }
}

//detect bots
if (!function_exists('is_bot')) {
    function is_bot()
    {
        if (preg_match('/abacho|accona|AddThis|AdsBot|ahoy|AhrefsBot|AISearchBot|alexa|altavista|anthill|appie|applebot|arale|araneo|AraybOt|ariadne|arks|aspseek|ATN_Worldwide|Atomz|baiduspider|baidu|bbot|bingbot|bing|Bjaaland|BlackWidow|BotLink|bot|boxseabot|bspider|calif|CCBot|ChinaClaw|christcrawler|CMC\/0\.01|combine|confuzzledbot|contaxe|CoolBot|cosmos|crawler|crawlpaper|crawl|curl|cusco|cyberspyder|cydralspider|dataprovider|digger|DIIbot|DotBot|downloadexpress|DragonBot|DuckDuckBot|dwcp|EasouSpider|ebiness|ecollector|elfinbot|esculapio|ESI|esther|eStyle|Ezooms|facebookexternalhit|facebook|facebot|fastcrawler|FatBot|FDSE|FELIX IDE|fetch|fido|find|Firefly|fouineur|Freecrawl|froogle|gammaSpider|gazz|gcreep|geona|Getterrobo-Plus|get|girafabot|golem|googlebot|\-google|grabber|GrabNet|griffon|Gromit|gulliver|gulper|hambot|havIndex|hotwired|htdig|HTTrack|ia_archiver|iajabot|IDBot|Informant|InfoSeek|InfoSpiders|INGRID\/0\.1|inktomi|inspectorwww|Internet Cruiser Robot|irobot|Iron33|JBot|jcrawler|Jeeves|jobo|KDD\-Explorer|KIT\-Fireball|ko_yappo_robot|label\-grabber|larbin|legs|libwww-perl|linkedin|Linkidator|linkwalker|Lockon|logo_gif_crawler|Lycos|m2e|majesticsEO|marvin|mattie|mediafox|mediapartners|MerzScope|MindCrawler|MJ12bot|mod_pagespeed|moget|Motor|msnbot|muncher|muninn|MuscatFerret|MwdSearch|NationalDirectory|naverbot|NEC\-MeshExplorer|NetcraftSurveyAgent|NetScoop|NetSeer|newscan\-online|nil|none|Nutch|ObjectsSearch|Occam|openstat.ru\/Bot|packrat|pageboy|ParaSite|patric|pegasus|perlcrawler|phpdig|piltdownman|Pimptrain|pingdom|pinterest|pjspider|PlumtreeWebAccessor|PortalBSpider|psbot|rambler|Raven|RHCS|RixBot|roadrunner|Robbie|robi|RoboCrawl|robofox|Scooter|Scrubby|Search\-AU|searchprocess|search|SemrushBot|Senrigan|seznambot|Shagseeker|sharp\-info\-agent|sift|SimBot|Site Valet|SiteSucker|skymob|SLCrawler\/2\.0|slurp|snooper|solbot|speedy|spider_monkey|SpiderBot\/1\.0|spiderline|spider|suke|tach_bw|TechBOT|TechnoratiSnoop|templeton|teoma|titin|topiclink|twitterbot|twitter|UdmSearch|Ukonline|UnwindFetchor|URL_Spider_SQL|urlck|urlresolver|Valkyrie libwww\-perl|verticrawl|Victoria|void\-bot|Voyager|VWbot_K|wapspider|WebBandit\/1\.0|webcatcher|WebCopier|WebFindBot|WebLeacher|WebMechanic|WebMoose|webquest|webreaper|webspider|webs|WebWalker|WebZip|wget|whowhere|winona|wlm|WOLP|woriobot|WWWC|XGET|xing|yahoo|YandexBot|YandexMobileBot|yandex|yeti|Zeus/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        }
        return false;
    }
}
?>
