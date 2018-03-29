<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\model\Retmsg;
if (!defined('WY_ROOT')) {
    exit;
}
/*
 *  api类：接受数据
 */
class api_yunjiedaifu extends Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->config['is_checkout_jump'] && $this->config['api_jump_url'] && $this->config['api_jump_url'] != $this->req->server('HTTP_HOST') && isset($_REQUEST)) {
            $urlstr = '';
            foreach ($_REQUEST as $key => $val) {
                $urlstr .= $urlstr ? '&' : '';
                $urlstr .= $key . '=' . $val;
            }
            header('location:http://' . $this->config['api_jump_url'] . '/apisubmit?' . $urlstr . '&fromurl=' . $this->req->server('HTTP_REFERER'));
            exit;
        }
        $this->ret = new Retmsg();//返回信息类实例
       // $version = '1.0';
        $cardnum = $this->req->request('cardnum');
        $customerid = $this->req->request('customerid');//商户号
        $orderId = $this->req->request('orderId'); //
        $timeStamp = $this->req->request('timeStamp');//提交金额
        $txnAmt = $this->req->request('txnAmt');
        $acctType = $this->req->request('acctType');/*回调地址*/
        $acctName = $this->req->request('acctName');
        $acctNo= $this->req->request('acctNo');
        $bankSettNo = $this->req->request('bankSettNo');/*来源地址*/
        $retUrl = $this->req->request('retUrl');
        $md5ConSec=$this->req->request('md5ConSec');//支付类型


        //$this->ret->put() : 是conFig.php的一个方法 用与错误信息的显示
        if (!isset($_REQUEST) || !$_REQUEST) {
            echo $this->ret->put('208', $cardnum ? true : false);
            exit;
        }
        if ( $customerid == '' || $txnAmt == '' || $orderId == '' || $acctType == '' || $md5ConSec == ''||$acctNo==''||$acctName=='') {
            echo $this->ret->put('200', $cardnum ? true : false);
            exit;
        }
        if (strlen($orderId) > 50) {
            echo $this->ret->put('203', $cardnum ? true : false);
            exit;
        }
        if ($txnAmt > 50000) {
            echo $this->ret->put('207', $cardnum ? true : false);
            exit;
        }

        if ($this->model()->select()->from('orders')->where(array('fields' => 'userid=? and sdorderno=?', 'values' => array($customerid, $orderId)))->count()) {
            echo $this->ret->put('205', $cardnum ? true : false);
            exit;
        }
        //通过用户编号获取用户信息
        $this->userData = $this->model()->select()->from('users')->where(array('fields' => 'id=?', 'values' => array($customerid)))->fetchRow();
        /*商户不存在*/
        if (!$this->userData) {
            echo $this->ret->put('001', $cardnum ? true : false);
            exit;
        }
        /*商户账号未审核*/
        if ($this->userData['is_state'] == '0') {
            echo $this->ret->put('002', $cardnum ? true : false);
            exit;
        }
        /*商户账号已停用*/
        if ($this->userData['is_state'] == '2') {
            echo $this->ret->put('003', $cardnum ? true : false);
            exit;
        }
        /*商户网站未绑定*/
        if ($this->userData['is_paysubmit'] == '0') {
            echo $this->ret->put('104', $cardnum ? true : false);
            exit;
        }
        /*交易网址错误*/


        $this->params = array(
            'customerid' => $customerid,
            'orderId' => $orderId,
            'timeStamp' =>$timeStamp,
            'txnAmt' => $txnAmt,
            'acctType' => $acctType,
            'acctName' => $acctName,
            'acctNo' => $acctNo,
            'md5ConSec' => $md5ConSec,
            'bankSettNo' => $bankSettNo,
            'retUrl'=>$retUrl,
            'paytype'=>'daifu'
           );
    }
}
?>
