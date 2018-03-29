<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\model\Retmsg;
if (!defined('WY_ROOT')) {
    exit;
}
/*
 *  api类：接受数据
 * */
class api_yunjie extends Controller
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
        $cardnum = $this->req->request('cardnum');
        //自定义支付方式为云捷支付
        $paytype = isset($_REQUEST['paytype'])?$_REQUEST['paytype']:'yunjie';

        $customerid = $this->req->request('customerid');//商户号
        $orderId= $this->req->request('orderId');
        $tranType= $this->req->request('tranType');
        $createIp= $this->req->request('createIp');
        $txnAmt= $this->req->request('txnAmt');
        $retUrl= $this->req->request('retUrl');
        $merUrl= $this->req->request('merUrl');
        $productName= $this->req->request('productName');
        $md5ConSec = $this->req->request('md5ConSec'); //签名

        //$this->ret->put() : 是conFig.php的一个方法 用与错误信息的显示
        if (!isset($_REQUEST) || !$_REQUEST) {
            echo $this->ret->put('208', $cardnum ? true : false);
            exit;
        }

        if ($customerid == '' ||  $orderId == ''||  $createIp == '' || $txnAmt == '' || $retUrl == '' || $merUrl == '' ||   $productName == '' ) {
            echo $this->ret->put('200', $cardnum ? true : false);
            exit;
        }

        if (strlen($orderId) > 50) {
            echo $this->ret->put('203', $cardnum ? true : false);
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
        if ($this->userData['is_verify_siteurl']) {
            $userInfo = $this->model()->select('siteurl')->from('userinfo')->where(array('fields' => 'userid=?', 'values' => array($customerid)))->fetchRow();
            if ($userInfo) {
                $fromUrl = $this->req->server('HTTP_REFERER');
                if (strpos($fromUrl, $userInfo['siteurl']) === false) {
                    echo $this->ret->put('206', $cardnum ? true : false);
                    exit;
                }
            }
        }

        if ($paytype == 'bank') {
            if ($bankCode == '') { //空的银行编号
                echo $this->ret->put('501', $cardnum ? true : false);
                exit;
            }
            //非法的银行编号
            if (!($acb = $this->model()->select()->from('acb')->where(array('fields' => 'code=?', 'values' => array($bankCode)))->fetchRow())) {
                echo $this->ret->put('503', $cardnum ? true : false);
                exit;
            }
            //银行接口维护
            if ($acb['is_state'] == '1') {
                echo $this->ret->put('502', $cardnum ? true : false);
                exit;
            }
        }

        $this->params = array(
            'paytype' => $paytype, //交易方式
            'customerid' => $customerid,//下游商户号
            'timeStamp' =>  $timeStamp, //时间
            'sdorderno' => $orderId,  //订单号
            'tranType' => $tranType, //业务类型：扫码支付:1  刷卡支付:2   WAP支付:3 （非必须）

            'createIp' => $createIp, //用户ip
            'txnAmt' => $txnAmt,  //交易金额
            'retUrl' => $retUrl, //异步通知地址
            'merUrl' => $merUrl, //同步通知地址

            'productName' => $productName, //商品名称
            'bankCode' => $bankCode, //银行编码（非必须）

            'md5ConSec' => $md5ConSec, //签名
            'fromurl' => $fromurl
        );
        file_put_contents('api_log.txt',json_encode($this->params,JSON_UNESCAPED_UNICODE).PHP_EOL."\r",FILE_APPEND);
    }
}
?>
