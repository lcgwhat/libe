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
class apisubmit_jialian extends api_jialian
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

        $signStr = 'version=' . $version . '&customerid=' . $customerid . '&total_fee=' . $total_fee . '&sdorderno=' . $sdorderno . '&notifyurl=' . $notifyurl . '&returnurl=' . $returnurl . '&' . $this->userData['apikey'];


        $mysign = md5($signStr);
        if ($sign != $mysign) {
            echo $this->ret->put('201', $cardnum ? true : false);
            exit;
        }
        switch ($paytype) {
            case 'bank':

            case 'alipay':

            case 'weixin':

            case 'qqrcode':

            case 'alipaywap':

            case 'kuaijie':

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

        /* 判断支付方式是否存在 */
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
            'bankcode' => $bankcode,
            'notifyurl' => $notifyurl,
            'returnurl' => $returnurl,
            'remark' => $remark,
            'addtime' => $addtime,
            'settAccNoName'=> $settAccNoName,
            'settAccNo'=>$settAccNo,
            'phone' =>$phone,
            'accNo' =>$accNo,
            'idNo' =>$idNo,
            'settPhone'=>$settPhone
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
        $tongbuURL=$this->req->request('returnurl');
        $txnType=$this->req->request('txnType');//支付类型
        $cct = $this->req->request('cct'); //支付币种
        $accNo = $this->req->request('accNo'); //支付卡号
        $phone = $this->req->request('phone');//支付卡银行预留手机号
        $settAccNo = $this->req->request('settAccNo');//结算银行卡h号
        $settAccNoName = $this->req->request('settAccNoName');//结算卡持卡人姓名
        $idNo =$this->req->request('idNo');
        $settPhone = $this->req->request('settPhone');//结算卡银行预留手机号
        $data=array('txnType'=>$txnType,
            'cct'=>$cct,
            'accNo'=>$accNo,
            'phone'=>$phone,
            'settAccNo'=>$settAccNo,
            'settAccNoName'=>$settAccNoName,
            'idNo'=>$idNo,
            'settPhone'=>$settPhone
        );
        $data1=json_encode($data);
        $STD = new Std3Des("D8BE3EC1527EF151A144C164F2678C39","12345678");
        $data2= $STD->encrypt($data1);

        $url = 'http://' . $this->req->server('HTTP_HOST') . '/pay/' . $acpcode . '_' . $gateway . '/send.php';
        $url .= '?orderid=' . $orderid . '&price=' . $total_fee . '&bankcode=' . $bankcode . '&remark=' . $remark.'&productName='.$productName.'&productDesc='.$productDesc.'&msg='.urlencode($data2).'&returnUrl='.$tongbuURL.'&paytype='.$paytype;
        $this->res->redirect($url);
    }
}
?>
