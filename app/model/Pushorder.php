<?php
namespace WY\app\model;
use WY\app\libs\Http;
use WY\app\libs\Controller;
if (!defined('WY_ROOT')) {
    exit;
}
class Pushorder extends Controller
{
    public function __construct($orderid)
    {
        parent::__construct();
        $this->orderid = $orderid;
        $this->orders = $this->model()->select()->from('orders')->where(array('fields' => 'orderid=?', 'values' => array($this->orderid)))->fetchRow();
        if (!$this->orders) {
            exit;
        }
    }
    private function getOrderInfo()//得到订单信息
    {
        return $this->model()->select()->from('orderinfo')->where(array('fields' => 'id=?', 'values' => array($this->orders['orderinfoid'])))->fetchRow();
    }
    private function getApiKey()//得到平台给下家的apikey
    {
        $users = $this->model()->select('apikey')->from('users')->where(array('fields' => 'id=?', 'values' => array($this->orders['userid'])))->fetchRow();
        return $users['apikey'];
    }
    public function saltData($data)//签名数据准备给下游的数据
    {
        $apikey = $this->getApiKey();//得到平台给下家的apikey
        $signstr = 'customerid=' . $data['customerid'] . '&status=' . $data['status'] . '&sdpayno=' . $data['sdpayno'] . '&sdorderno=' . $data['sdorderno'] . '&total_fee=' . $data['total_fee'] . '&paytype=' . $data['paytype'] . '&' . $apikey;
        $data += array('sign' => md5($signstr));
        return $data;
    }
    public function notify()
    {
        $orderinfo = $this->getOrderInfo();
//

        $http = new Http($orderinfo['notifyurl'], $this->saltData($this->getParams($orderinfo)));
        $http->toUrl();
        $resCode = $http->getResCode();

        $resContent = substr(str_replace(' ', '', strip_tags($http->getResContent())), 0, 50);
        $errInfo = $http->getErrInfo();

        $is_notify = $resContent == 'success' ? 1 : 2;
        $ret = array('code' => $resCode, 'content' => $resContent, 'info' => $errInfo);
        $data = array('is_notify' => $is_notify);

        //是否给下游异步通知过数据更新
        $this->model()->from('orders')->updateSet($data)->where(array('fields' => 'orderid=?', 'values' => array($this->orderid)))->update();

        if ($ordernotify = $this->model()->select('times,nexts')->from('ordernotify')->where(array('fields' => 'orid=?', 'values' => array($this->orders['id'])))->fetchRow()) {

            $data = array('is_status' => $is_notify, 'retmsg' => json_encode($ret), 'times' => 1 + $ordernotify['times'], 'nexts' => 30 + $ordernotify['nexts']);
            $this->model()->from('ordernotify')->updateSet($data)->where(array('fields' => 'orid=?', 'values' => array($this->orders['id'])))->update();

        } else {
            $data = array('orid' => $this->orders['id'], 'is_status' => $is_notify, 'retmsg' => json_encode($ret), 'addtime' => time(), 'times' => 1, 'nexts' => 30);

            $this->model()->from('ordernotify')->insertData($data)->insert();
        }

        return true;
    }
    public function sync()//给下游的同步回调地址
    {
        $orderinfo = $this->getOrderInfo();
        $newData = $this->saltData($this->getParams($orderinfo));
        $params = '';
        foreach ($newData as $key => $val) {
            $params .= $params ? '&' : '';
            $params .= $key . '=' . $val;
        }
        $this->res->redirect($orderinfo['returnurl'] . '?' . $params);
    }
    public function getParams($orderinfo)//得到参数
    {
        $data = array('status' => $this->orders['is_state'], 'customerid' => $this->orders['userid'], 'sdpayno' => $this->orderid, 'sdorderno' => $this->orders['sdorderno'], 'total_fee' => $this->orders['realmoney'], 'paytype' => $orderinfo['paytype'], 'remark' => $orderinfo['remark']);
        return $data;
    }
}