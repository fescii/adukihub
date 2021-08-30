<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Require the Composer autoloader.
require APPPATH . 'third_party/aws-sdk/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Aws_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->key = $this->general_settings->aws_key;
        $this->secret = $this->general_settings->aws_secret;
        $this->bucket = $this->general_settings->aws_bucket;
        $this->region = $this->general_settings->aws_region;

        $credentials = new Aws\Credentials\Credentials($this->key, $this->secret);
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region' => $this->region,
            'credentials' => $credentials
        ]);
    }

    //upload file
    public function upload_file($path)
    {
        if (!empty($path) && file_exists(FCPATH . $path)) {
            $this->put_file_object($path, FCPATH . $path);
            delete_file_from_server($path);
        }
    }

    //upload file direct
    public function upload_file_direct($input_name, $path)
    {
        if (!empty($input_name) && !empty($path)) {
            $name = $_FILES[$input_name]['name'];
            $this->put_object_direct($path . $name, $_FILES[$input_name]['tmp_name']);
            return $name;
        }
    }

    //put file object
    public function put_file_object($path)
    {
        if (!empty($path)) {
            $this->put_object($path, FCPATH . $path);
        }
    }

    //dowbload file
    function download_file($file_path)
    {
        $file_name = pathinfo($file_path, PATHINFO_BASENAME);
        header('Content-Disposition: attachment; filename=' . $file_name);
        readfile($this->aws_base_url . $file_path);
        exit();
    }

    //delete file
    public function delete_file($path)
    {
        if (!empty($path)) {
            $this->delete_object($path);
        }
    }

    //put object
    public function put_object($key, $temp_path)
    {
        if (file_exists($temp_path)) {
            try {
                $file = fopen($temp_path, 'r');
                $this->s3->putObject([
                    'Bucket' => $this->bucket,
                    'Key' => $key,
                    'Body' => $file,
                    'ACL' => 'public-read'
                ]);
                fclose($file);
                return true;
            } catch (S3Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    //put object direct
    public function put_object_direct($key, $temp_path)
    {
        $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Body' => fopen($temp_path, 'rb'),
            'ACL' => 'public-read'
        ]);
    }

    //delete object
    public function delete_object($key)
    {
        if (!empty($key)) {
            try {
                $this->s3->deleteObject([
                    'Bucket' => $this->bucket,
                    'Key' => $key
                ]);
            } catch (S3Exception $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

}
