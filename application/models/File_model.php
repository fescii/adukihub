<?php defined('BASEPATH') or exit('No direct script access allowed');

class File_model extends CI_Model
{
    /*
    *------------------------------------------------------------------------------------------
    * IMAGES
    *------------------------------------------------------------------------------------------
    */

    //upload image
    public function upload_image()
    {
        $this->load->model('upload_model');
        $temp_data = $this->upload_model->upload_temp_image('file', 'array');
        if (!empty($temp_data)) {
            $temp_path = $temp_data['full_path'];
            if ($temp_data['image_type'] == 'gif') {
                $gif_path = $this->upload_model->post_gif_image_upload($temp_data['file_name']);
                $data["image_big"] = $gif_path;
                $data["image_default"] = $gif_path;
                $data["image_slider"] = $gif_path;
                $data["image_mid"] = $gif_path;
                $data["image_small"] = $gif_path;
                $data["image_mime"] = 'gif';
                $data["file_name"] = @$temp_data['client_name'];
            } else {
                $data["image_big"] = $this->upload_model->post_big_image_upload($temp_path);
                $data["image_default"] = $this->upload_model->post_default_image_upload($temp_path);
                $data["image_slider"] = $this->upload_model->post_slider_image_upload($temp_path);
                $data["image_mid"] = $this->upload_model->post_mid_image_upload($temp_path);
                $data["image_small"] = $this->upload_model->post_small_image_upload($temp_path);
                $data["image_mime"] = 'jpg';
                $data["file_name"] = @$temp_data['client_name'];
            }
            $data["user_id"] = $this->auth_user->id;
            $data["storage"] = $this->general_settings->storage;

            @$this->db->close();
            @$this->db->initialize();
            $this->db->insert('images', $data);
            $this->upload_model->delete_temp_image($temp_path);
            //move to s3
            if ($data["storage"] == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->upload_file($data["image_big"]);
                if ($data['image_type'] != 'gif') {
                    $this->aws_model->upload_file($data["image_default"]);
                    $this->aws_model->upload_file($data["image_slider"]);
                    $this->aws_model->upload_file($data["image_mid"]);
                    $this->aws_model->upload_file($data["image_small"]);
                }
            }
        }
    }

    //get image
    public function get_image($id)
    {
        $sql = "SELECT * FROM images WHERE id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get images
    public function get_images($limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM images WHERE user_id = ? ORDER BY images.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM images ORDER BY images.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($limit)));
        }
        return $query->result();
    }

    //get more images
    public function get_more_images($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM images WHERE images.id < ? AND user_id = ? ORDER BY images.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM images WHERE images.id < ? ORDER BY images.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($limit)));
        }
        return $query->result();
    }

    //search images
    public function search_images($search)
    {
        $like = '%' . $search . '%';
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM images WHERE user_id = ? AND images.file_name LIKE ? ORDER BY images.id DESC";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), $like));
        } else {
            $sql = "SELECT * FROM images WHERE images.file_name LIKE ? ORDER BY images.id DESC";
            $query = $this->db->query($sql, array($like));
        }
        return $query->result();
    }

    //delete image
    public function delete_image($id)
    {
        $image = $this->get_image($id);
        if (!empty($image)) {
            if ($image->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_file($image->image_big);
                if ($data['image_type'] != 'gif') {
                    $this->aws_model->delete_file($image->image_default);
                    $this->aws_model->delete_file($image->image_slider);
                    $this->aws_model->delete_file($image->image_mid);
                    $this->aws_model->delete_file($image->image_small);
                }
            } else {
                delete_file_from_server($image->image_big);
                delete_file_from_server($image->image_default);
                delete_file_from_server($image->image_slider);
                delete_file_from_server($image->image_mid);
                delete_file_from_server($image->image_small);
            }
            $this->db->where('id', $id);
            $this->db->delete('images');
        }
    }

    /*
    *------------------------------------------------------------------------------------------
    * QUIZ IMAGES
    *------------------------------------------------------------------------------------------
    */

    //upload quiz image
    public function upload_quiz_image()
    {
        $this->load->model('upload_model');
        $temp_data = $this->upload_model->upload_temp_image('file', 'array');
        if (!empty($temp_data)) {
            $temp_path = $temp_data['full_path'];
            if ($temp_data['image_type'] == 'gif') {
                $gif_path = $this->upload_model->quiz_gif_image_upload($temp_data['file_name']);
                $data["image_default"] = $gif_path;
                $data["image_small"] = $gif_path;
                $data["image_mime"] = 'gif';
                $data["file_name"] = @$temp_data['client_name'];
            } else {
                $data["image_default"] = $this->upload_model->quiz_default_image_upload($temp_path);
                $data["image_small"] = $this->upload_model->quiz_small_image_upload($temp_path);
                $data["image_mime"] = 'jpg';
                $data["file_name"] = @$temp_data['client_name'];
            }
            $data["user_id"] = $this->auth_user->id;
            $data["storage"] = $this->general_settings->storage;

            @$this->db->close();
            @$this->db->initialize();
            $this->db->insert('quiz_images', $data);
            $this->upload_model->delete_temp_image($temp_path);
            //move to s3
            if ($data["storage"] == "aws_s3") {
                $this->load->model("aws_model");
                $data["storage"] = "aws_s3";
                $this->aws_model->upload_file($data["image_default"]);
                if ($data['image_type'] != 'gif') {
                    $this->aws_model->upload_file($data["image_small"]);
                }
            }
        }
    }

    //get quiz image
    public function get_quiz_image($id)
    {
        $sql = "SELECT * FROM quiz_images WHERE id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get quiz images
    public function get_quiz_images($limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM quiz_images WHERE user_id = ? ORDER BY id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM quiz_images ORDER BY id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($limit)));
        }
        return $query->result();
    }

    //get more quiz images
    public function get_more_quiz_images($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM quiz_images WHERE id < ? AND user_id = ? ORDER BY id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM quiz_images WHERE id < ? ORDER BY id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($limit)));
        }
        return $query->result();
    }

    //search quiz images
    public function search_quiz_images($search)
    {
        $like = '%' . $search . '%';
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM quiz_images WHERE user_id = ? AND file_name LIKE ? ORDER BY id DESC";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), $like));
        } else {
            $sql = "SELECT * FROM quiz_images WHERE file_name LIKE ? ORDER BY id DESC";
            $query = $this->db->query($sql, array($like));
        }
        return $query->result();
    }

    //delete quiz image
    public function delete_quiz_image($id)
    {
        $quiz_image = $this->get_quiz_image($id);
        if (!empty($quiz_image)) {
            if ($quiz_image->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_file($quiz_image->image_default);
                if ($data['image_type'] != 'gif') {
                    $this->aws_model->delete_file($quiz_image->image_small);
                }
            } else {
                delete_file_from_server($quiz_image->image_default);
                delete_file_from_server($quiz_image->image_small);
            }
            $this->db->where('id', $id);
            $this->db->delete('quiz_images');
        }
    }

    /*
    *------------------------------------------------------------------------------------------
    * FILES
    *------------------------------------------------------------------------------------------
    */

    //upload file
    public function upload_file()
    {
        $this->load->model('upload_model');
        $file_array = null;
        if ($this->general_settings->storage == "aws_s3") {
            $this->load->model("aws_model");
            $input_name = 'file';
            $date_directory = $this->upload_model->create_directory_by_date('files');
            $name = $this->upload_model->generate_file_name($input_name);
            if (empty($name)) {
                $name = "file_" . uniqid();
            }
            $path = "uploads/files/" . $date_directory;
            $file_name = $this->aws_model->upload_file_direct($input_name, $path);
            if (!empty($file_name)) {
                $file_array = array(
                    'file_name' => $file_name,
                    'file_path' => $path . $file_name,
                    'user_id' => $this->auth_user->id,
                    'storage' => "aws_s3"
                );
            }
        } else {
            $file_array = $this->upload_model->upload_file('file');
            if (!empty($file_array)) {
                $file_array['user_id'] = $this->auth_user->id;
                $file_array['storage'] = "local";
            }
        }
        if (!empty($file_array)) {
            @$this->db->close();
            @$this->db->initialize();
            $this->db->insert('files', $file_array);
        }
    }

    //get files
    public function get_files($limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM files WHERE user_id = ? ORDER BY files.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM files ORDER BY files.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($limit)));
        }
        return $query->result();
    }

    //get file
    public function get_file($id)
    {
        $sql = "SELECT * FROM files WHERE files.id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get more files
    public function get_more_files($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM files WHERE files.id < ? AND user_id = ? ORDER BY files.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM files WHERE files.id < ? ORDER BY files.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($limit)));
        }
        return $query->result();
    }

    //search files
    public function search_files($search)
    {
        $like = '%' . $search . '%';
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM files WHERE user_id = ? AND files.file_name LIKE ? ORDER BY files.id DESC";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), $like));
        } else {
            $sql = "SELECT * FROM files WHERE files.file_name LIKE ? ORDER BY files.id DESC";
            $query = $this->db->query($sql, array($like));
        }
        return $query->result();
    }

    //delete file
    public function delete_file($id)
    {
        $file = $this->get_file($id);
        if (!empty($file)) {
            if ($file->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_file($file->file_path);
            } else {
                delete_file_from_server($file->file_path);
            }
            $this->db->where('id', $id);
            $this->db->delete('files');
        }
    }


    /*
    *------------------------------------------------------------------------------------------
    * VIDEOS
    *------------------------------------------------------------------------------------------
    */

    //upload video
    public function upload_video()
    {
        $this->load->model('upload_model');
        $data = null;
        if ($this->general_settings->storage == "aws_s3") {
            $this->load->model("aws_model");
            $input_name = 'file';
            $date_directory = $this->upload_model->create_directory_by_date('files');
            $name = $this->upload_model->generate_file_name($input_name);
            if (empty($name)) {
                $name = "video_" . uniqid();
            }
            $path = "uploads/videos/" . $date_directory;
            $file_name = $this->aws_model->upload_file_direct($input_name, $path);
            if (!empty($file_name)) {
                $data = array(
                    'video_name' => $file_name,
                    'video_path' => $path . $file_name,
                    'storage' => "aws_s3",
                    'user_id' => $this->auth_user->id
                );
            }
        } else {
            $data = $this->upload_model->upload_video('file');
            if (!empty($data)) {
                $data['storage'] = "local";
                $data['user_id'] = $this->auth_user->id;
            }
        }
        if (!empty($data)) {
            @$this->db->close();
            @$this->db->initialize();
            $this->db->insert('videos', $data);
        }
    }

    //get videos
    public function get_videos($limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM videos WHERE user_id = ? ORDER BY videos.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM videos ORDER BY videos.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($limit)));
        }
        return $query->result();
    }

    //get video
    public function get_video($id)
    {
        $sql = "SELECT * FROM videos WHERE videos.id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get more videos
    public function get_more_videos($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM videos WHERE videos.id < ? AND user_id = ? ORDER BY videos.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM videos WHERE videos.id < ? ORDER BY videos.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($limit)));
        }
        return $query->result();
    }

    //search videos
    public function search_videos($search)
    {
        $like = '%' . $search . '%';
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM videos WHERE user_id = ? AND videos.video_name LIKE ? ORDER BY videos.id DESC";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), $like));
        } else {
            $sql = "SELECT * FROM videos WHERE videos.video_name LIKE ? ORDER BY videos.id DESC";
            $query = $this->db->query($sql, array($like));
        }
        return $query->result();
    }

    //delete video
    public function delete_video($id)
    {
        $video = $this->get_video($id);
        if (!empty($video)) {
            if ($video->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_file($video->video_path);
            } else {
                delete_file_from_server($video->video_path);
            }
            $this->db->where('id', $video->id);
            $this->db->delete('videos');
        }
    }


    /*
   *------------------------------------------------------------------------------------------
   * AUDIOS
   *------------------------------------------------------------------------------------------
   */

    //upload audio
    public function upload_audio()
    {
        $this->load->model('upload_model');
        $data = null;
        if ($this->general_settings->storage == "aws_s3") {
            $this->load->model("aws_model");
            $input_name = 'file';
            $date_directory = $this->upload_model->create_directory_by_date('audios');
            $name = $this->upload_model->generate_file_name($input_name);
            if (empty($name)) {
                $name = "audio_" . uniqid();
            }
            $path = "uploads/audios/" . $date_directory;
            $file_name = $this->aws_model->upload_file_direct($input_name, $path);
            if (!empty($file_name)) {
                $data = array(
                    'audio_name' => $file_name,
                    'audio_path' => $path . $file_name,
                    'download_button' => $this->input->post('download_button', true),
                    'storage' => "aws_s3",
                    'user_id' => $this->auth_user->id
                );
            }
        } else {
            $audio = $this->upload_model->upload_audio('file');
            if (!empty($audio)) {
                $data = array(
                    'audio_name' => trim($audio['file_name']),
                    'audio_path' => $audio['file_path'],
                    'download_button' => $this->input->post('download_button', true),
                    'storage' => "local",
                    'user_id' => $this->auth_user->id
                );
            }
        }

        if (!empty($data)) {
            @$this->db->close();
            @$this->db->initialize();
            $this->db->insert('audios', $data);
        }
    }

    //get audios
    public function get_audios($limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM audios WHERE user_id = ? ORDER BY audios.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM audios ORDER BY audios.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($limit)));
        }
        return $query->result();
    }

    //get audio
    public function get_audio($id)
    {
        $sql = "SELECT * FROM audios WHERE audios.id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get more audios
    public function get_more_audios($last_id, $limit)
    {
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM audios WHERE audios.id < ? AND user_id = ? ORDER BY audios.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($this->auth_user->id), clean_number($limit)));
        } else {
            $sql = "SELECT * FROM audios WHERE audios.id < ? ORDER BY audios.id DESC LIMIT ?";
            $query = $this->db->query($sql, array(clean_number($last_id), clean_number($limit)));
        }
        return $query->result();
    }

    //search audios
    public function search_audios($search)
    {
        $like = '%' . $search . '%';
        if ($this->general_settings->file_manager_show_files != 1) {
            $sql = "SELECT * FROM audios WHERE user_id = ? AND audios.audio_name LIKE ? ORDER BY audios.id DESC";
            $query = $this->db->query($sql, array(clean_number($this->auth_user->id), $like));
        } else {
            $sql = "SELECT * FROM audios WHERE audios.audio_name LIKE ? ORDER BY audios.id DESC";
            $query = $this->db->query($sql, array($like));
        }
        return $query->result();
    }

    //delete audio
    public function delete_audio($id)
    {
        $audio = $this->get_audio($id);
        if (!empty($audio)) {
            if ($audio->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_file($audio->audio_path);
            } else {
                delete_file_from_server($audio->audio_path);
            }
            $this->db->where('id', $audio->id);
            $this->db->delete('audios');
        }
    }
}