<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');

require_once('./application/libraries/REST_Controller.php');

/**
 * Tourist Area API controller
 *
 * Validation is missign
 */
class Areas extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
        $this->load->model('order_model');
        $this->load->model('auth_model');
    }

    public function test_post()
    {
        $variable = $this->post();
        $variable = $variable['pos'];
        $this->response(array('status' => true, 'id' => $variable), 200);
        //$this->response($this->area_model->findAreas());
    }

    public function index_get()
    {
        $this->response($this->area_model->get_all());
        //$this->response($this->area_model->findAreas());
    }

    public function find_post($key = '')
    {
        $this->response($this->area_model->findAreas($key));
    }

    public function edit_post($id = NULL)
    {
        if (!$id) {
            $this->response(array('status' => false, 'error_message' => 'No ID was provided.'), 400);
        }

        $this->response($this->area_model->getAreaById($id));
    }

    public function save_post($id = NULL)
    {
        if (! $id)
        {
            $new_id = $this->area_model->addNewArea($this->post());
            $this->response(array('status' => true, 'id' => $new_id, 'message' => sprintf('Area #%d has been created.', $new_id)), 200);
        }
        else
        {
            $this->area_model->update($this->post(), $id);
            $this->response(array('status' => true, 'message' => sprintf('Area #%d has been updated.', $id)), 200);
        }
    }

    public function remove_post($id = NULL)
    {
        if ($this->area_model->delete($id)) {
            $this->response(array('status' => true, 'message' => sprintf('Area #%d has been deleted.', $id)), 200);
        } else {
            $this->response(array('status' => false, 'error_message' => 'This Area does not exist!'), 404);
        }
    }

    public function upload_post($id = NULL)
    {
        $error = false;
        $files = array();
        $uploaddir = 'uploads/';
        //var_dump($_FILES.','.$id);
        foreach ($_FILES as $file) {
            if (move_uploaded_file($file['tmp_name'], $uploaddir . basename($file['name']))) {
                $files[] = $file['name'];
            } else {
                $error = true;
            }
            break;
        }
        if (!$error) {
            $this->response(array('status' => true, 'file' => $files[0]), 200);
        } else {
            $this->response(array('status' => false, 'error_message' => 'There was an error uploading your files!'), 404);
        }
    }

///////////////////////////////////////////////////////////
////////////////??????  External APIs
///////////////////////////////////////////////////////////

    public function getAreaIdByPosition_post()
    {
        $request = $this->post();
        $lng = $request['pos'][0];
        $lat = $request['pos'][1];
        $all_areas = $this->area_model->getAreas('', 'all', 0);
        $id = -1;
        if (count($all_areas) > 0) {
            foreach ($all_areas as $item) {
                $pos = json_decode($item->info);
                $pos = $pos->position;
                $lng1 = $pos[0][0];
                $lng2 = $pos[1][0];
                $lat1 = $pos[0][1];
                $lat2 = $pos[1][1];
                if ($lng < $lng1) continue;
                if ($lng > $lng2) continue;
                if ($lat < $lat1) continue;
                if ($lat > $lat2) continue;
                $id = $item->id;
                $name = $item->name;
                break;
            }
        }
        if ($id == -1) $this->response(array('status' => false, 'id' => $id), 200);
        else $this->response(array('status' => true, 'id' => $id, 'name' => $name), 200);
    }

    public function getAllCourseInfos_post()
    {
        $request = $this->post();
        $all_courses = $this->area_model->getCourses('all', 0);
        if (count($all_courses) == 0) {
            $this->response(array('status' => false, 'Courses' => '-1'), 200);
        } else {
            $i = 0;
            $course_list = array();
            foreach ($all_courses as $item) {
                $all_areas = json_decode($item->point_list);
                $courseInfo = json_decode($item->info);
                $j = 0;
                $name = '';
                $areas = array();
                if (count($all_areas) > 0) {
                    foreach ($all_areas as $areaItem) {
                        $areaData = $this->area_model->getAreaById($areaItem->id);
                        $j++;
                        if ($j == 1) $name = $areaData->name;
                        else $name = $name . ' - ' . $areaData->name;
                        array_push(
                            $areas,
                            array(
                                'id' => $areaData->id,
                                'name' => $areaData->name,
                                'attractionCnt' => count(json_decode($areaData->point_list))
                            )
                        );
                    }
                }
                $i++;
                $cour_array =
                    array_push(
                        $course_list,
                        array(
                            'id' => $item->id,
                            'name' => $name,    //  $item->name || $name
                            'image' => base_url() . 'uploads/' . $courseInfo->overay,
                            'cost' => $item->price,
                            'discount_rate' => $item->discount_rate,
                            'scenic_areas' => $areas
                        )
                    );
            }
            $this->response(array('status' => true, 'Courses' => $course_list), 200);
        }
    }

    public function getAllAreaInfos_post()
    {
        $request = $this->post();
        $all_areas = $this->area_model->getAreas('', 'all', 0);
        if (count($all_areas) == 0) {
            $this->response(array('status' => false, 'Areas' => '-1'), 200);
        } else {
            $i = 0;
            $areas = array();
            foreach ($all_areas as $item) {
                $i++;
                array_push(
                    $areas,
                    array(
                        'id' => $item->id,
                        'name' => $item->name
                    )
                );
            }
            $this->response(array('status' => true, 'Areas' => $areas), 200);
        }
    }

    public function getMyOrderInfos_post()
    {
        $request = $this->post();
        //$this->response(array('status' => true, 'Orders' => '1'), 200);
        $mobile = $request['phone'];
        $orders = $this->order_model->getMyOrderInfos($mobile);
        if ($orders == '-1') {
            $this->response(array('status' => false, 'Orders' => $orders), 200);
        } else {
            $this->response(array('status' => true, 'Orders' => $orders['Auths']), 200);
        }
    }

    public function getMyAreaInfos_post()
    {
        $request = $this->post();
        $mobile = $request['phone'];
        $orders = $this->order_model->getOrdersByUser($mobile);
        if (count($orders) == 0) {
            $this->response(array('status' => false, 'MyAreas' => '-1'), 200);
        } else {
            $i = 0;
            $Auths = array();
            foreach ($orders as $item) {
                $i++;
                if ($item->areaid == '0') {// auth orders
                    $areaitem = $this->area_model->getAreaByAuthId($item->authid);
                    $area_info = json_decode($areaitem->info);
                    array_push(
                        $Auths,
                        array(
                            'id' => $areaitem->id,
                            'name' => $areaitem->name,
                            'cost' => $areaitem->price,//$auth_area_info->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'order_time' => $item->ordered_time,
                            'state' => $item->status,
                            'type' => ($item->status == '1') ? 1 : 2
                        )
                    );
                } else { //buy orders
                    $areaitem = $this->area_model->getAreaById($item->areaid);
                    $attritem = json_decode($areaitem->point_list);
                    $area_info = json_decode($areaitem->info);
                    array_push(
                        $Auths,
                        array(
                            'id' => ($item->attractionid == '0') ? $item->areaid : $item->attractionid,
                            'name' => $areaitem->name,
                            'cost' => $areaitem->price,//$auth_area_info->price,
                            'discount_rate' => $areaitem->discount_rate,
                            'image' => base_url() . 'uploads/' . $area_info->overay,
                            'order_time' => $item->ordered_time,
                            'state' => $item->status,
                            'type' => ($item->status == '1') ? 1 : 2
                        )
                    );
                }
            }
            $this->response(array('status' => true, 'MyAreas' => $Auths), 200);
        }
    }

    public function getAreaInfoById_post()
    {
        $request = $this->post();
        $id = $request['id'];
        $phone = $request['phone'];
        $item = $this->area_model->getAreaById($id);
        if (count($item) == 0) {
            $this->response(array('status' => false, 'CurArea' => '-1'), 200);
        } else if ($item->type == 2) {
            $this->response(array('status' => false, 'CurArea' => '-1'), 200);
        } else {
            $curDate = date_create(date("Y-m-d"));
            date_modify($curDate, "-15 days");
            $itemInfo = json_decode($item->info);
            $attractions = json_decode($item->point_list);
            $i = 0;
            $attractionList = array();
            if (count($attractions) > 0) {
                foreach ($attractions as $atts) {
                    $i++;
                    array_push(
                        $attractionList,
                        array(
                            'id' => $atts->id,
                            'name' => $atts->name,
                            'position' => json_decode($atts->position),
                            'cost' => $atts->price,
                            'discount_rate' => $atts->discount_rate,
                            'buy_state' => $atts->trial == '1' ? 1 : 3,
                            'audio_files' => [
                                base_url() . 'uploads/' . $atts->audio_1,
                                base_url() . 'uploads/' . $atts->audio_2,
                                base_url() . 'uploads/' . $atts->audio_3,
                            ],
                            'image' => base_url() . 'uploads/' . $atts->image
                        )
                    );
                }
            }
            $scenic_area = [
                'id' => $item->id,
                'name' => $item->name,
                'position' => [($itemInfo->position[0][0] + $itemInfo->position[1][0]) / 2,
                    ($itemInfo->position[0][1] + $itemInfo->position[1][1]) / 2],
                'top_right' => ($itemInfo->position[1]),
                'bottom_left' => ($itemInfo->position[0]),
                'overlay' => base_url() . 'uploads/' . $itemInfo->overay,
                'image' => base_url() . 'uploads/' . $itemInfo->overay,
                'zoom' => '2',
                'cost' => $item->price,
                'discount_rate' => $item->discount_rate,
                'attractionCnt' => count($attractionList),
                'attractions' => $attractionList
            ];
            $this->response(array('status' => true, 'CurArea' => $scenic_area), 200);
        }
    }

    public function setAreaBuyOrder_post()
    {
        $request = $this->post();
        $areaid = $request['id'];
        $phone = $request['phone'];
        $cost = $request['cost'];
        $type = $request['type'];

        $init['num'] = $this->auth_model->getCount() + 1;
        $date = new DateTime();
        if ($phone == '' || $type == '') {
            $this->response(array('status' => false, 'result' => '-1'), 200);
        } else {
            $user = $this->user_model->getOrderUserByPhone($phone);
            if (count($user)==0){
                $userInfo = [
                    'mobile'=>$phone
                ];
                $this->user_model->addNewOrderUser($userInfo);
            }
            if ($type == '1' || $type == '2') {
                $authOrderItem = [
                    "value" => sprintf("%'.02d%'.08d", '12', $init['num']),
                    "code" => $cost,
                    "userphone" => $phone,
                    "ordertype" => '1',
                    "status" => '2',
                    "areaid" => $areaid,
                    "ordered_time" => $date->format('Y-m-d H:i:s'),
                    "paid_time" => $date->format('Y-m-d H:i:s')
                ];
                $this->order_model->AddBuyOrder($authOrderItem);
            } else if ($type == '3') {
                $area = explode('_', $areaid);
                $authOrderItem = [
                    "value" => sprintf("%'.02d%'.08d", '12', $init['num']),
                    "code" => $cost,
                    "userphone" => $phone,
                    "ordertype" => '1',
                    "status" => '2',
                    "areaid" => $area[0],
                    "attractionid" => $areaid,
                    "ordered_time" => $date->format('Y-m-d H:i:s'),
                    "paid_time" => $date->format('Y-m-d H:i:s')
                ];
                $this->order_model->AddBuyOrder($authOrderItem);
            } else {
                $authOrderItem = [
                    "code" => $cost,
                    "userphone" => $phone,
                    "status" => '1',
                    "areaid" => $areaid,
                    "ordertype" => '1',
                    "paid_time" => $date->format('Y-m-d H:i:s')
                ];
                if(!$this->order_model->AddAuthOrder($authOrderItem))
                    $this->response(array('status' => false, 'result' => '-1'), 200);
            }
            $this->response(array('status' => true, 'result' => '1'), 200);
        }
    }

}

/* End of file Areas.php */
/* Location: ./application/controllers/api/Areas.php */