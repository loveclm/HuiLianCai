<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once('./application/libraries/REST_Controller.php');

/**
 * Shop API controller
 *
 * Validation is missign
 */
class Shops extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('shop_model');
        $this->load->model('auth_model');
    }

    public function index_get()
    {
        $this->response($this->shop_model->get_all());
    }

    public function find_post($key = '')
    {
        $this->response($this->shop_model->findAreas($key));
    }

    public function edit_post($id = NULL)
    {
        if (!$id) {
            $this->response(array('status' => false, 'error_message' => 'No ID was provided.'), 400);
        }

        $this->response($this->shop_model->getAreaById($id));
    }

    public function save_post($id = NULL)
    {

        if (!$id) {
            $new_id = $this->shop_model->add($this->post());
            $this->response(array('status' => true, 'id' => $new_id, 'message' => sprintf('Area #%d has been created.', $new_id)), 200);
        } else {
            $this->shop_model->update($this->post(), $id);
            $this->response(array('status' => true, 'message' => sprintf('Area #%d has been updated.', $id)), 200);
        }
    }

    public function remove_post($id = NULL)
    {
        if ($this->shop_model->delete($id)) {
            $this->response(array('status' => true, 'message' => sprintf('Area #%d has been deleted.', $id)), 200);
        } else {
            $this->response(array('status' => false, 'error_message' => 'This Area does not exist!'), 404);
        }
    }

    public function generateAuth_post($id = NULL)
    {
        $authInfo = $this->post();

        $date = new DateTime();
        $init['code'] = $this->auth_model->getOrderCount($authInfo['shopid']) + 1;
        $init['num'] = $this->auth_model->getCount() + 1;
        $authItem = array(
            "shopid" => $authInfo['shopid'],
            "type" => $authInfo['type'],
            "targetid" => $authInfo['targetid'],
            "status" => $authInfo['status'],
            "price" => '0',
            "codecount" => $authInfo['codecount'],
            "created_time" => $date->format('Y-m-d H:i:s')
        );
        $authid = $this->shop_model->addAuth($authItem);
        for ($i = 0; $i < $authInfo['codecount']; $i++) {
            $date = new DateTime();
            $authOrderItem = array(
                "authid" => sprintf("%d", $authid),
                "value" => sprintf("%'.02d%'.08d", '12', $init['num'] + $i),
                "userphone" => '0',
                "areaid" => $authInfo['targetid'],
                "status" => '0',
                "code" => sprintf("%'.03d%'.03d%'.05d", $authInfo['shopid'], $authInfo['targetid'], $init['code'] + $i),
                "ordered_time" => $date->format('Y-m-d H:i:s'),
                "ordertype" => '2'
            );

            $this->shop_model->addAuthOrder($authOrderItem);
        }
        $this->response(array('status' => true, 'message' => sprintf('Authcode #%d has been created.', $authInfo['codecount'])), 200);
    }

    public function generateQR_post($id = NULL)
    {

        $authInfo = $this->post();

        $date = new DateTime();
        $authItem = array(
            "shopid" => $authInfo['shopid'],
            "type" => $authInfo['type'],
            "targetid" => $authInfo['targetid'],
            "created_time" => $date->format('Y-m-d H:i:s'),
            "data" => $authInfo['data']
        );

        $this->shop_model->addQR($authItem);
        $this->response(array('status' => true, 'message' => sprintf('QRcode has been created.')), 200);
    }

////////////////////// External Rest APIs

    public function getMyOrderInfos_post()
    {
        $request = $this->post();
        $id = -1;
        if ($id == -1) $this->response(array('status' => false, 'Orders' => $id), 200);
        else $this->response(array('status' => true, 'Orders' => $id), 200);
    }


}

/* End of file Shops.php */
/* Location: ./application/controllers/api/Shops.php */