<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 10/8/2017
 * Time: 10:01 AM
 * This class is the class that check the order status.
 *      if a activity reached at start time then the activity is started.
 *      And a activity reached at end time then the activity is ended.
 */

class cron_controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('user_model');
        $this->load->model('news_model');
        $this->load->model('transaction_model');
        $this->load->model('activity_model');
        $this->isLoggedIn();
    }

    /**
     * This function used to load the first screen of the user
     */
    public function index(){
        $this->chkActivityStarting();
        $this->chkActivityEnding();

        $this->chkOrderCanceling();
        $this->chkOrderCompleting();
    }

    /** this function is used to check activity start status
     * In this function, now the activity's status that reached at start grouping time is changed to grouping status
     * So, this activity jump to activity sale status
     */
    function chkActivityStarting(){
        $activities = $this->activity_model->getActivitiesForStarting();
        foreach ($activities as $activity){
            $activity_info = array(
                'id' => $activity->id,
                'status' => 2,
            );
            $this->activity_model->update($activity_info, $activity->id);
            // add activity starting message to message list
        }
    }

    /** this function is used to check activity end status
     *  In this function, check whether  activity grouping is ended or not.
     *  If activity reached at end time, by comparing the number of grouping members and grouping products,
     *    if they is above to setting value then activity jump to grouping success status and order jump to shipping ready status.
     */
    function chkActivityEnding(){
        $activities = $this->activity_model->getActivitiesForEnding();
        foreach ($activities as $activity){
            // check grouping count and product amount
            $users = explode( ',', $activity->users);
            $nums = explode(',', $activity->nums);
            $status = 3;
            if( intval($activity->man_cnt) > count($users)) $status = 4;

            $cnt = 0;
            foreach ($nums as $num){
                $cnt += intval($num);
            }
            if($cnt < intval($activity->group_cnt)) $status = 4;

            // change activity's status
            $activity_info = array(
                'id' => $activity->id,
                'status' => $status,
            );
            $this->activity_model->update($activity_info, $activity->id);
            // change order's status
            $order = $this->order_model->getOrderByActivityId($activity->id);
            if($order == NULL) continue;

            $order_info = array(
                'status' => 3,
                'isSuccess' => $status - 2,
                'success_time' => date('Y-m-d H:i:s')
            );
            if($status == 3){
                // refund extra money
                $money = number_format(floatval($order->origin_cost) - floatval($order->group_cost), 2,'.','');
                $order_info['refund_cost'] = $money;
                $order_info['refund_time'] = date('Y-m-d H:i:s');

                //add transaction to message list
                $transaction_info = array(
                    'order_id' => $order->id,
                    'provider_id' => $order->provider,
                    'shop_id' => $order->shop,
                    'money' => '-' . $money,
                    'pay_type' => 1,
                    'note' => '退还差额（拼团成功）'
                );
                $this->transaction_model->add($transaction_info);
            }
            $this->order_model->update($order_info, $order->id);

            // add activity starting message to message list
            foreach ($users as $user) {
                $news_data = array(
                    'sender' => $user,
                    'receiver' => $order->provider,
                    'type' => '拼团活动消息',
                    'message' => '（'. $activity->name . (($status == 3) ? '）拼团成功，按拼团价结算，系统已自动退还终端便利店差价。' : '）拼团失败，该拼团活动订单按原价结算。')

                );
                $this->news_model->add($news_data);
            }
        }

    }

    /** this function is used to check order's payment status
     *  in this function, check payment status of unpaid orders.
     *  for 3 hours, the activities that is unpaid status jump to order fail status.
     */
    function chkOrderCanceling(){
        $orders = $this->order_model->getOrdersForCanceling();
        foreach ($orders as $order){
            $order_info = array(
                'status' => 5,
                'cancel_time' => date('Y-m-d H:i:s')
            );

            $this->order_model($order_info, $order->id);
        }
    }

    // this function is used to complete order that past 3 days from shipping time
    function chkOrderCompleting(){
        $orders = $this->order_model->getOrdersForCompleting();
        foreach ($orders as $order){
            $order_info = array(
                'status' => 4,
                'complete_time' => date('Y-m-d H:i:s')
            );
            $this->order_model($order_info, $order->id);
        }
    }

    // this function is used for message notification
    function chkMessages(){
        $logined_id = $_POST['id'];
        $msg_cnt = 0;
        $user = $this->user_model->getUserInfo($logined_id);
        if($user == NULL)
            $user_id = 0;
        else
            $user_id = $this->user_model->getIdByUserId($user->userid);

        $result = $this->news_model->getNewItems($user_id);
        $msg_cnt = count($result);

        echo $msg_cnt;
    }

}