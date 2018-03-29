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
class apisubmit_yunjie extends api_yunjie
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
        $signStr = 'customerid=' . $customerid .'&orderId=' . $sdorderno . '&tranType=' . $tranType . '&createIp=' . $createIp . '&txnAmt=' . $txnAmt . '&retUrl=' . $retUrl . '&merUrl=' . $merUrl  .'&' . $this->userData['apikey'];

        $mysign = md5($signStr);
        if ($md5ConSec != $mysign) {
            echo $this->ret->put('201', $cardnum ? true : false);
            exit;
        }

        switch ($paytype) {

            case 'yunjie':
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
        if ($this->model()->select()->from('orders')->where(array('fields' => 'userid=? and sdorderno=?', 'values' => array($this->userData['id'], $orderId)))->count()) {
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

        $orderid = $this->res->getOrderID();
        $addtime = time();

        /*用户提交上来的信息*/
        $orderinfo = array(
            'userid' => $customerid,
            'paytype' => $paytype,
            'bankcode' => isset($bankcode)?$bankcode:'',
            'notifyurl' => $retUrl,
            'returnurl' => $merUrl,
            'remark' => isset($remark)?$remark:'',
            'addtime' => $addtime,
            'settAccNoName'=> $cardByName,
            'settAccNo'=>$cardByNo,
            'phone' =>$mobile,
            'accNo' =>$cardByNo,
            'idNo' =>$cerNumber,
            'settPhone'=>$mobile
        );
        // var_dump($orderinfo);exit;

        /*在数据库中记录用户提交上来订单信息*/

        if (!($orderinfoid = $this->model()->from('orderinfo')->insertData($orderinfo)->insert())) {
            echo $this->ret->put('209', $cardnum ? true : false);
            exit;
        }

        /*平台订单信息*/
        $orderdata = array('userid' => $customerid, 'agentid' => $this->userData['superid'], 'orderid' => $orderid, 'sdorderno' => $sdorderno, 'total_fee' => $txnAmt, 'channelid' => $channelid, 'addtime' => $addtime, 'lastime' => $addtime, 'is_paytype' => 0, 'orderinfoid' => $orderinfoid);

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
            'TradeType' => $TradeType,
            'timeStamp' =>  $timeStamp,
            'orderId' => $orderid,  //订单号
            'tranType' => $tranType, //交易类型
            'createIp' => $createIp, //用户ip

            'txnAmt' => $txnAmt,  //交易金额
            'authCode' => $authCode,
            'retUrl' => $retUrl, //异步通知地址
            'merUrl' => $merUrl, //同步通知地址
            'cardByName' => $cardByName, //持卡人姓名

            'cardByNo' => $cardByNo,    //卡号
            'cerNumber' => $cerNumber, //身份证号
            'mobile'=>$mobile,   //手机号码
            'productName' => $productName,
            'productDesc' => $productDesc,

            'subMerNo' => $subMerNo,
            'subMerName' => $subMerName,
            'metaOption' => $metaOption,
            'fileId1' => $fileId1,
            'cardType' => $cardType,

            'expireDate' => $expireDate,
            'CVV' => $CVV,
            'bankCode' => $bankCode,
            'openBankName' => $openBankName,
            'cerType' => $cerType,
            'rcvName' => $rcvName,
            'rcvMobile' => $rcvMobile,
            'rcvAdress' => $rcvAdress
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
