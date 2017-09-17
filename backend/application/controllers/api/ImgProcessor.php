<?php defined('BASEPATH') OR exit('No direct script access allowed');

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
        $error = false;
        $files = array();
        $uploaddir = 'uploads/';
        $tt = time();
        $ext = explode(".", $_FILES[0]['name']);
        $nn = rand(1000, 9999);
        $filename = 'ayoubc' . $nn . $tt . '.' . $ext[1];
        foreach ($_FILES as $file) {
//            if (move_uploaded_file($file['tmp_name'], $uploaddir . (basename($file['name'])))) {
            if (move_uploaded_file($file['tmp_name'], $uploaddir . $filename)) {
//                $files[] = $file['name'];
                $files[] = $file['name'];
            } else {
                $error = true;
            }
            break;
        }
        if (!$error) {
//            $this->response(array('status' => true, 'file' => $files[0]), 200);
            $this->response(array('status' => true, 'file' => $filename, 'originfile' => $files[0]), 200);
        } else {
            $this->response(array('status' => false, 'error_message' => 'There was an error uploading your files!'), 404);
        }
    }

}

;

/* End of file Image_processor.php */
/* Location: ./application/controllers/api/Image_processor.php */

?>