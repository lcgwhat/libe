<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\libs\Std3Des;
if (!defined('WY_ROOT')) {
    exit;
}
/*
 * class：apisubmit
 *
 **/
class apisubmit_wxh5 extends api_wxh5
{
    public function index()
    {
        extract($this->params); //extract()函数从数组中将变量导入到当前的符号表
        if ($this->userData['is_verify_siteurl']) {
            $fromurl = $fromurl ? $fromurl : $this->req->server('HTTP_REFERER');
            $userinfo = $this->model()->select('siteurl')->from('userinfo')->where(array('fields' => 'userid=?', 'values' => array($this->userData['id'])))->fetchRow();

            if ($fromurl == '' || !strpos($fromurl, $userinfo['siteurl'])) {
                echo $this->ret->put('206', $cardnum ? true : false);
                exit;
            }
        }
        //签名校验
        $mysign=md5('customerid='.$customerid.'&sdorderno='.$sdorderno.'&goodname='.$goodname.'&goodtag='.$goodtag.'&total_fee='.$total_fee.'&userip='.$userip.'&notifyurl='.$notifyurl.'&'.$this->userData['apikey']);
        if ($sign != $mysign) {
            echo $this->ret->put('201', $cardnum ? true : false);
            exit;
        }

        switch ($paytype) {
            case 'bank':

            case 'alipay':

            case 'tenpay':

            case 'weixin':

            case 'qqrcode':

            case 'tenpaywap':

            case 'alipaywap':

            case 'qqwallet':

            case 'gzhpay':

            case 'tongming':

            case 'yunjie':

            case 'wxh5':
                $this->submit();
                break;
            default:
                echo $this->ret->put('106', $cardnum ? true : false);
                exit;
        }
    }
    /*
     * 参数说明：$paytype   --  支付方式
     * */

    protected function submit()
    {
        extract($this->params);

        $bankcode = $paytype == 'bank' ? $bankcode : $paytype;

        /* 判断是否订单重复*/
        if ($this->model()->select()->from('orders')->where(array('fields' => 'userid=? and sdorderno=?', 'values' => array($this->userData['id'], $sdorderno)))->count()) {
            echo $this->ret->put('205', $cardnum ? true : false);
            exit;
        }
        /* 通用网关*/
        $acw = $this->model()->select('id')->from('acw')->where(array('fields' => 'code=?', 'values' => array($paytype)))->fetchRow();

        /* 判断网关是否存在 */
        if (!$acw) {
            echo $this->ret->put('500', $cardnum ? true : false);
            exit;
        }

        $acc = $this->model()->select('a.id,a.acpcode,a.gateway,a.is_state,b.is_state as is_state_acc,b.channelid')->from('acc a')->left('userprice b')->on('b.channelid=a.id')->join()->where(array('fields' => 'b.userid=? and a.acwid=?', 'values' => array($customerid, $acw['id'])))->fetchRow();

        /* 判断这个用户是否开通这个支付通道*/
        if (!$acc) {
            echo $this->ret->put('103', $cardnum ? true : false);
            exit;
        }
        if ($acc['is_state'] == '1') {  //通道状态 开启：0，关闭：1
            echo $this->ret->put('100', $cardnum ? true : false);
            exit;
        }
        if ($acc['is_state_acc'] == '1') { //通道状态 开启：0，关闭：1
            echo $this->ret->put('102', $cardnum ? true : false);
            exit;
        }

        $channelid = $acc['channelid']; //支付通道的ID
        $acpcode = $acc['acpcode'];  //支付商的编号
        $gateway = $acc['gateway'];  //支付的方式

        $orderid = $this->res->getOrderID(); //生成订单号
        $addtime = time();

        /*用户提交上来的信息*/
        $orderinfo = array(
            'userid' => $customerid,
            'paytype' => $paytype,
            'bankcode' => isset($bankcode)?$bankcode:'',
            'notifyurl' => $notifyurl,
            'returnurl' => isset($returnurl)?$returnurl:'',
            'remark' => isset($remark)?$remark:'',
            'addtime' => $addtime,
            'settAccNoName'=> '',
            'settAccNo'=>'',
            'phone' =>'',
            'accNo' =>'',
            'idNo' =>'',
            'settPhone'=>''
        );
        // var_dump($orderinfo);exit;

        /*在数据库中记录用户提交上来订单信息*/

        if (!($orderinfoid = $this->model()->from('orderinfo')->insertData($orderinfo)->insert())) {
            echo $this->ret->put('209', $cardnum ? true : false);
            exit;
        }

        /*平台订单信息*/
        $orderdata = array('userid' => $customerid, 'agentid' => $this->userData['superid'], 'orderid' => $orderid, 'sdorderno' => $sdorderno, 'total_fee' => $total_fee, 'channelid' => $channelid, 'addtime' => $addtime, 'lastime' => $addtime, 'is_paytype' => 0, 'orderinfoid' => $orderinfoid);

        /*在数据库中生成平台订单*/
        if (!($orid = $this->model()->from('orders')->insertData($orderdata)->insert())) {
            echo $this->ret->put('210', $cardnum ? true : false);
            exit;
        }
        $ordernotify = array('orid' => $orid, 'addtime' => $addtime);

        /*数据库记录订单回调信息*/
        if (!$this->model()->from('ordernotify')->insertData($ordernotify)->insert()) {
            echo $this->ret->put('211', $cardnum ? true : false);
            exit;
        }

        /*重定向地址*/
        $submit_data_old = array(
            'sdorderno' => $orderid,  //我们平台生成的订单号
            'goodname' => $goodname,
            'goodtag' => $goodtag,
            'total_fee' => $total_fee,
            'userip' => $userip
        );

        $submit_data_json=json_encode($submit_data_old);  //将数组转为json格式
        $des3Util=new STD3Des("68b2dc377jlt0vewl4u9g4nc","12345678");
        $submit_data=$des3Util->encrypt($submit_data_json);
        $submitdata=urlencode($submit_data);

        $url = 'http://' . $this->req->server('HTTP_HOST') . '/pay/' . $acpcode . '_' . $gateway . '/send.php';
        $url .= '?submitdata=' . $submitdata;
        $this->res->redirect($url);  // 订单确认无误后，重定向到发送请求页面
    }
}
?>
