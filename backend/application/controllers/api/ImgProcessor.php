<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
require_once('./application/libraries/REST_Controller.php');

/**
 * Shop API controller
 *
 * Validation is missign
 */
class ImgProcessor extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

    }

    public function index_get()
    {
        $this->response(array('status' => true, 'message' => "rest api is ok!!"), 200);
    }

    public function uploadImgData_post()
    {
        $request = $this->post();
        if(!isset($request['imageData'])) {
            $this->response(array('status' => false, 'data' => 'Image Data is none.'), 400);
            return;
        }
        $imgdata = $request['imageData'];
        $imgdata = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgdata));

        //$imgdata = base64_decode($data);

        $imageName = 'hlc' . time() . rand(1000, 9999) . '.png';
        if (file_put_contents('uploads/' . $imageName, $imgdata))
            $this->response(array('status' => true, 'data' => $imageName), 200);
        else
            $this->response(array('status' => false, 'data' => 'Image uploading failed.'), 400);
    }

    public function uploadAnyData_post($id = NULL)
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );
        $upload_path = 'uploads';
        $tt = time();
        $ext = explode(".", $_FILES[0]['name']);
        $len = count($ext);
        $nn = rand(1000, 9999);
        $filename = 'hlc' . $nn . $tt . '.' . $ext[$len - 1];
        $config['upload_path'] = './' . $upload_path;
        $config['allowed_types'] = 'jpg|png|gif|bmp';
        $config['file_name'] = $filename;
        $image_data = array();
        $is_file_error = FALSE;
        if (!$_FILES) {
            $is_file_error = TRUE;
        }
        if (!$is_file_error) {

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload(0)) {
            } else {
                //store the file info
                $image_data = $this->upload->data();
                $config['image_library'] = 'gd2';
                $config['source_image'] = $image_data['full_path']; //get original image
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 400;
                $config['height'] = 300;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $ret['data'] = $image_data['file_name'];
                $ret['status'] = 'success';
            }
        }
        //echo json_encode($ret);

        if ($ret['status'] == 'success') {
            $this->response(array('status' => true, 'file' => $filename, 'originfile' => $ret['data']), 200);
        } else {
            $this->response(array('status' => false, 'error_message' => 'There was an error uploading your files!'), 200);
        }
    }

    public function uploadLogoData_post($id = NULL)
    {
        $ret = array(
            'data' => '',
            'status' => 'fail'
        );
        $upload_path = 'uploads';
        $tt = time();
        $ext = explode(".", $_FILES[0]['name']);
        $len = count($ext);
        $nn = rand(1000, 9999);
        $filename = 'hlc' . $nn . $tt . '.' . $ext[$len - 1];
        $config['upload_path'] = './' . $upload_path;
        $config['allowed_types'] = 'jpg|png|gif|bmp';
        $config['file_name'] = $filename;
        $image_data = array();
        $is_file_error = FALSE;
        if (!$_FILES) {
            $is_file_error = TRUE;
        }
        if (!$is_file_error) {

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload(0)) {
            } else {
                //store the file info
                $image_data = $this->upload->data();
                $config['image_library'] = 'gd2';
                $config['source_image'] = $image_data['full_path']; //get original image
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 50;
                $config['height'] = 50;
                $this->load->library('image_lib', $config);
                $this->image_lib->resize();

                $ret['data'] = $image_data['file_name'];
                $ret['status'] = 'success';
            }
        }
        //echo json_encode($ret);

        if ($ret['status'] == 'success') {
            $this->response(array('status' => true, 'file' => $filename, 'originfile' => $ret['data']), 200);
        } else {
            $this->response(array('status' => false, 'error_message' => 'There was an error uploading your files!'), 200);
        }
    }
};

/* End of file Image_processor.php */
/* Location: ./application/controllers/api/Image_processor.php */

?>