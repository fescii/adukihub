<?php defined('BASEPATH') or exit('No direct script access allowed');

class Gallery_model extends CI_Model
{
    //input values
    public function input_values()
    {
        $data = array(
            'lang_id' => $this->input->post('lang_id', true),
            'album_id' => $this->input->post('album_id', true),
            'category_id' => 0,
            'title' => $this->input->post('title', true)
        );
        $category_id = $this->input->post('category_id', true);
        if (!empty($category_id)) {
            $data['category_id'] = $category_id;
        }
        return $data;
    }

    //add image
    public function add()
    {
        $data = $this->input_values();
        if (!empty($_FILES['files'])) {
            $this->load->model('upload_model');
            $file_count = count($_FILES['files']['name']);
            for ($i = 0; $i < $file_count; $i++) {
                if (isset($_FILES['files']['name'])) {
                    //file
                    $_FILES['file']['name'] = $_FILES['files']['name'][$i];
                    $_FILES['file']['type'] = $_FILES['files']['type'][$i];
                    $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
                    $_FILES['file']['error'] = $_FILES['files']['error'][$i];
                    $_FILES['file']['size'] = $_FILES['files']['size'][$i];
                    //upload
                    $is_gif = false;
                    $temp_data = $this->upload_model->upload_temp_image('file', 'array');
                    if (!empty($temp_data)) {
                        $temp_path = $temp_data['full_path'];
                        if ($temp_data['image_type'] == 'gif') {
                            $gif_path = $this->upload_model->gallery_gif_image_upload($temp_data['file_name']);
                            $data["path_big"] = $gif_path;
                            $data["path_small"] = $gif_path;
                            $is_gif = true;
                        } else {
                            $data["path_big"] = $this->upload_model->gallery_big_image_upload($temp_path);
                            $data["path_small"] = $this->upload_model->gallery_small_image_upload($temp_path);
                        }
                    }
                    $data["storage"] = $this->general_settings->storage;
                    @$this->db->close();
                    @$this->db->initialize();
                    $this->db->insert('gallery', $data);
                    $this->upload_model->delete_temp_image($temp_path);
                    //move to s3
                    if ($data["storage"] == "aws_s3") {
                        $this->load->model("aws_model");
                        if (!empty($data["path_big"])) {
                            $this->aws_model->upload_file($data["path_big"]);
                        }
                        if (!empty($data["path_small"]) && $is_gif == false) {
                            $this->aws_model->upload_file($data["path_small"]);
                        }
                    }
                }
            }
            return true;
        }

        return false;
    }

    //get gallery images
    public function get_images()
    {
        $sql = "SELECT * FROM gallery WHERE lang_id = ? ORDER BY id DESC";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id)));
        return $query->result();
    }

    //get paginated images
    public function get_paginated_images($per_page, $offset)
    {
        $this->filter_images();
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($per_page, $offset);
        $query = $this->db->get('gallery');
        return $query->result();
    }

    //get paginated images count
    public function get_paginated_images_count()
    {
        $this->db->select('COUNT(id) AS count');
        $this->filter_images();
        $query = $this->db->get('gallery');
        return $query->row()->count;
    }

    //images filter
    public function filter_images()
    {
        $q = trim($this->input->get('q', true));
        if (!empty($q)) {
            $this->db->like('title', clean_str($q));
        }
        $lang_id = trim($this->input->get('lang_id', true));
        if (!empty($lang_id)) {
            $this->db->where('lang_id', clean_number($lang_id));
        }
        $album = trim($this->input->get('album', true));
        if (!empty($album)) {
            $this->db->where('album_id', clean_number($album));
        }
        $category = trim($this->input->get('category', true));
        if (!empty($category)) {
            $this->db->where('category_id', clean_number($category));
        }
    }

    //get gallery images by category
    public function get_images_by_category($category_id)
    {
        $sql = "SELECT gallery.* , gallery_categories.name as category_name FROM gallery 
                INNER JOIN gallery_categories ON gallery_categories.id = gallery.category_id
                WHERE gallery.lang_id = ? AND gallery.category_id = ? ORDER BY gallery.id DESC";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($category_id)));
        return $query->result();
    }

    //get gallery images by album
    public function get_images_by_album($album_id)
    {
        $sql = "SELECT * FROM gallery WHERE album_id = ? ORDER BY id DESC";
        $query = $this->db->query($sql, array(clean_number($album_id)));
        return $query->result();
    }

    //get category image count
    public function get_category_image_count($category_id)
    {
        $sql = "SELECT COUNT(id) AS count FROM gallery WHERE lang_id = ? AND category_id = ?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($category_id)));
        return $query->row()->count;
    }

    //set as album cover
    public function set_as_album_cover($id)
    {
        $image = $this->get_image($id);
        if (!empty($image)) {
            //reset all
            $data = array(
                'is_album_cover' => 0
            );
            $this->db->where('album_id', $image->album_id);
            $this->db->update('gallery', $data);
            //set new
            $data = array(
                'is_album_cover' => 1
            );
            $this->db->where('id', $image->id);
            $this->db->update('gallery', $data);
        }
    }

    //get gallery album cover image
    public function get_cover_image($album_id)
    {
        $sql = "SELECT * FROM gallery WHERE album_id = ? AND is_album_cover = 1 ORDER BY id DESC LIMIT 1";
        $query = $this->db->query($sql, array(clean_number($album_id)));
        $row = $query->row();
        if (empty($row)) {
            $sql = "SELECT * FROM gallery WHERE album_id = ? ORDER BY id DESC LIMIT 1";
            $query = $this->db->query($sql, array(clean_number($album_id)));
            $row = $query->row();
        }
        return $row;
    }

    //get image
    public function get_image($id)
    {
        $sql = "SELECT * FROM gallery WHERE id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //update image
    public function update($id)
    {
        $id = clean_number($id);
        $data = $this->input_values();
        if (!empty($_FILES['file'])) {

            //delete old file
            $this->delete_image_file($id);

            $this->load->model('upload_model');
            $temp_data = $this->upload_model->upload_temp_image('file', 'array');
            if (!empty($temp_data)) {
                $temp_path = $temp_data['full_path'];
                if ($temp_data['image_type'] == 'gif') {
                    $gif_path = $this->upload_model->gallery_gif_image_upload($temp_data['file_name']);
                    $data["path_big"] = $gif_path;
                    $data["path_small"] = $gif_path;
                } else {
                    $data["path_big"] = $this->upload_model->gallery_big_image_upload($temp_path);
                    $data["path_small"] = $this->upload_model->gallery_small_image_upload($temp_path);
                }
                $data["storage"] = $this->general_settings->storage;
                $this->upload_model->delete_temp_image($temp_path);
                //move to s3
                if ($data["storage"] == "aws_s3") {
                    $this->load->model("aws_model");
                    if (!empty($data["path_big"])) {
                        $this->aws_model->upload_file($data["path_big"]);
                    }
                    if (!empty($data["path_small"]) && $is_gif == false) {
                        $this->aws_model->upload_file($data["path_small"]);
                    }
                }
            }
        }

        @$this->db->close();
        @$this->db->initialize();
        $this->db->where('id', $id);
        return $this->db->update('gallery', $data);
    }

    //delete image
    public function delete($id)
    {
        $image = $this->get_image($id);
        if (!empty($image)) {
            //delete image
            $this->delete_image_file($id);

            $this->db->where('id', $image->id);
            return $this->db->delete('gallery');
        }
        return false;
    }

    //delete image file
    public function delete_image_file($id)
    {
        $image = $this->get_image($id);
        if (!empty($image)) {
            if ($image->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_file($image->path_big);
                $this->aws_model->delete_file($image->path_small);
            } else {
                delete_file_from_server($image->path_big);
                delete_file_from_server($image->path_small);
            }
        }
    }
}