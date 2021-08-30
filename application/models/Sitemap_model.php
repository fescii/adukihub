<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sitemap_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->urls = array();
    }

    //get pages
    public function get_pages()
    {
        $this->db->join('languages', 'languages.id = pages.lang_id');
        $this->db->select('pages.*, languages.short_form AS lang_short_form');
        $this->db->where('languages.status', 1);
        $this->db->where('pages.visibility', 1);
        $this->db->order_by('languages.id');
        $query = $this->db->get('pages');
        return $query->result();
    }

    //get categories
    public function get_categories()
    {
        $this->db->join('languages', 'languages.id = categories.lang_id');
        $this->db->select('categories.*, languages.short_form AS lang_short_form, (SELECT name_slug FROM categories AS tbl_categories WHERE tbl_categories.id = categories.parent_id) as parent_slug');
        $this->db->where('languages.status', 1);
        $this->db->order_by('languages.id, categories.id');
        $query = $this->db->get('categories');
        return $query->result();
    }


    //get posts
    public function get_posts()
    {
        $this->db->join('languages', 'languages.id = posts.lang_id');
        $this->db->select('posts.*, languages.short_form AS lang_short_form');
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.status', 1);
        $this->db->where('posts.is_scheduled', 0);
        $this->db->order_by('languages.id, posts.id');
        $query = $this->db->get('posts');
        return $query->result();
    }

    //get tags
    public function get_tags()
    {
        $this->db->join('posts', 'posts.id = tags.post_id');
        $this->db->join('users', 'posts.user_id = users.id');
        $this->db->select('tags.tag_slug, tags.tag');
        $this->db->group_by('tags.tag_slug, tags.tag');
        $this->db->order_by('tags.tag');
        $this->db->where('posts.status', 1);
        $this->db->where('posts.visibility', 1);
        $this->db->where('posts.is_scheduled', 0);
        $query = $this->db->get('tags');
        return $query->result();
    }

    //input values
    public function input_values()
    {
        $data = array(
            'frequency' => $this->general_settings->sitemap_frequency,
            'last_modification' => $this->general_settings->sitemap_last_modification,
            'priority' => $this->general_settings->sitemap_priority,
            'lastmod_time' => NULL
        );
        return $data;
    }

    //update sitemap settings
    public function update_sitemap_settings()
    {
        $db_data = array(
            'sitemap_frequency' => $this->input->post('frequency', true),
            'sitemap_last_modification' => $this->input->post('last_modification', true),
            'sitemap_priority' => $this->input->post('priority', true)
        );

        $this->db->where('id', 1);
        $this->db->update('general_settings', $db_data);
    }

    public function add($loc, $changefreq = NULL, $lastmod = NULL, $priority = NULL, $priority_value = NULL, $lastmod_time = NULL)
    {
        $item = new stdClass();
        $item->loc = $loc;
        $item->lastmod = $lastmod;
        $item->lastmod_time = $lastmod_time;
        $item->changefreq = $changefreq;
        $item->priority = $priority;
        $item->priority_value = $priority_value;
        $this->urls[] = $item;

        return true;
    }

    /**
     * Generate the sitemap file and replace any output with the valid XML of the sitemap
     *
     * @param string $type Type of sitemap to be generated. Use 'urlset' for a normal sitemap. Use 'sitemapindex' for a sitemap index file.
     * @access public
     * @return void
     */
    public function output($type = 'urlset')
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        if (!empty($this->urls)) {
            foreach ($this->urls as $url) {
                $child = $xml->addChild('url');
                $child->addChild('loc', htmlspecialchars(strtolower($url->loc)));

                if (isset($url->lastmod) && $url->lastmod != 'none') {
                    if ($url->lastmod == 'server_response') {
                        $child->addChild('lastmod', date("Y-m-d"));
                    } else {
                        $child->addChild('lastmod', $url->lastmod_time);
                    }
                }

                if (isset($url->changefreq) && $url->changefreq != 'none') {
                    $child->addChild('changefreq', $url->changefreq);
                }

                if (isset($url->priority) && $url->priority != 'none') {
                    $child->addChild('priority', $url->priority_value);
                }
            }
        }
        header('Content-Disposition: attachment; filename="sitemap.xml"');
        $this->output->set_content_type('application/xml')->set_output($xml->saveXML());

    }


    /**
     * Clear all items in the sitemap to be generated
     *
     * @access public
     * @return boolean
     */
    public function clear()
    {
        $this->urls = array();
        return true;
    }

    /**
     * Static Page Urls
     */
    public function add_static_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $priority_value = 0.8;
        $this->sitemap_model->add(base_url(), $frequency, $last_modification, '1', '1', $lastmod_time);
    }

    /**
     * Page Urls
     */
    public function add_page_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $pages = $this->get_pages();
        if (!empty($pages)) {
            foreach ($pages as $page) {
                $base_url = $this->get_base_url_by_lang($page->lang_id, $page->lang_short_form);
                if (!empty($base_url)) {
                    $priority_value = 0.8;
                    $this->sitemap_model->add($base_url . $page->slug, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
                }
            }
        }
    }


    /**
     * Category Urls
     */
    public function add_category_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $categories = $this->get_categories();
        $priority_value = 0.8;
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $base_url = $this->get_base_url_by_lang($category->lang_id, $category->lang_short_form);
                if (!empty($base_url)) {
                    $url = "";
                    if (!empty($category->parent_slug)) {
                        $url = $base_url . $category->parent_slug . "/" . $category->name_slug;
                    } else {
                        $url = $base_url . $category->name_slug;
                    }
                    $this->sitemap_model->add($url, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
                }
            }
        }
    }


    /**
     * Post Urls
     */
    public function add_post_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $posts = $this->get_posts();
        $priority_value = 0.8;
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $base_url = $this->get_base_url_by_lang($post->lang_id, $post->lang_short_form);
                if (!empty($base_url)) {
                    $this->sitemap_model->add($base_url . $post->title_slug, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
                }
            }
        }
    }

    /**
     * Tag Urls
     */
    public function add_tag_urls($frequency, $last_modification, $priority, $lastmod_time)
    {
        $tags = $this->get_tags();
        $priority_value = 0.8;
        if (!empty($tags)) {
            foreach ($tags as $tag) {
                if (!empty($tag)) {
                    $tag = $this->tag_model->get_tag($tag->tag_slug);
                    $base_url = $this->get_base_url_by_language_id($tag->tag_lang_id);
                    if (!empty($base_url)) {
                        $url = $base_url . get_route('tag', true) . $tag->tag_slug;
                        $this->sitemap_model->add($url, $frequency, $last_modification, $priority, $priority_value, $lastmod_time);
                    }
                }
            }
        }
    }

    public function generate_sitemap()
    {
        $this->add(base_url(), 'daily', 'server_response', '1', '1', NULL);
        $this->add_page_urls('daily', 'server_response', '0.8', NULL);
        $this->add_category_urls('daily', 'server_response', '0.8', NULL);
        $this->add_post_urls('daily', 'server_response', '0.8', NULL);
        $this->add_tag_urls('daily', 'server_response', '0.8', NULL);

        if (item_count($this->urls) > 49999) {
            $array_urls = array_chunk($this->urls, 49000);
            $i = 0;
            if (!empty($array_urls)) {
                foreach ($array_urls as $array_url) {
                    $full_path = FCPATH . "sitemap.xml";
                    if ($i != 0) {
                        $full_path = FCPATH . "sitemap-" . $i . ".xml";
                    }
                    $this->export_sitemap($full_path, $array_url);
                    $i++;
                }
            }
        } else {
            $full_path = FCPATH . "sitemap.xml";
            $this->export_sitemap($full_path, $this->urls);
        }
    }

    public function export_sitemap($full_path, $array)
    {
        if (file_exists($full_path)) {
            unlink($full_path);
        }
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><urlset/>');
        $xml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        foreach ($array as $url) {
            $child = $xml->addChild('url');
            $child->addChild('loc', htmlspecialchars(strtolower($url->loc)));

            if (isset($url->lastmod) && $url->lastmod != 'none') {
                if ($url->lastmod == 'server_response') {
                    $child->addChild('lastmod', date("Y-m-d"));
                } else {
                    $child->addChild('lastmod', $url->lastmod_time);
                }
            }

            if (isset($url->changefreq) && $url->changefreq != 'none') {
                $child->addChild('changefreq', $url->changefreq);
            }

            if (isset($url->priority) && $url->priority != 'none') {
                $child->addChild('priority', $url->priority_value);
            }
        }
        $xml->saveXML($full_path);
    }


    public function get_base_url_by_language_id($lang_id)
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

    public function get_base_url_by_lang($lang_id, $short_form)
    {
        $ci =& get_instance();
        if ($lang_id == $ci->general_settings->site_lang) {
            return base_url();
        } else {
            return base_url() . $short_form . "/";
        }
    }

}
