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
class api extends Controller
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
        $version = '1.0';
        $customerid = $this->req->request('customerid');//商户号
        $sdorderno = $this->req->request('sdorderno'); //
        $total_fee = $this->req->request('total_fee');//提交金额
        $paytype = $this->req->request('paytype');/*支付方式*/
        $notifyurl = $this->req->request('notifyurl'); /*回调地址*/
        $bankcode = $this->req->request('bankcode'); /*银行编号*/
        $returnurl = $this->req->request('returnurl');/*回调地址*/
        $remark = $this->req->request('remark');/*其他信息*/
        $sign = $this->req->request('sign');/*签名信息*/
        $cardnum = $this->req->request('cardnum');
        $fromurl = $this->req->request('fromurl');/*来源地址*/

        $txnType=$this->req->request('txnType');//支付类型
        $cct = $this->req->request('cct'); //支付币种
        $accNo = $this->req->request('accNo'); //支付卡号
        $phone = $this->req->request('phone');//支付卡银行预留手机号
        $settAccNo = $this->req->request('settAccNo');//结算银行卡h号
        $settAccNoName = $this->req->request('settAccNoName');//结算卡持卡人姓名
        $idNo =$this->req->request('idNo');
        $settPhone = $this->req->request('settPhone');//结算卡银行预留手机号



        //$this->ret->put() : 是conFig.php的一个方法 用与错误信息的显示
        if (!isset($_REQUEST) || !$_REQUEST) {
            echo $this->ret->put('208', $cardnum ? true : false);
            exit;
        }
        if ($version == '' || $customerid == '' || $total_fee == '' || $sdorderno == '' || $paytype == '' || $notifyurl == '' || $sign == '') {
            echo $this->ret->put('200', $cardnum ? true : false);
            exit;
        }
        if (strlen($sdorderno) > 50) {
            echo $this->ret->put('203', $cardnum ? true : false);
            exit;
        }
        if ($total_fee > 50000) {
            echo $this->ret->put('207', $cardnum ? true : false);
            exit;
        }
        if ($remark && strlen($remark) > 50) {
            echo $this->ret->put('204', $cardnum ? true : false);
            exit;
        }
        if ($this->model()->select()->from('orders')->where(array('fields' => 'userid=? and sdorderno=?', 'values' => array($customerid, $sdorderno)))->count()) {
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
            if ($bankcode == '') { //空的银行编号
                echo $this->ret->put('501', $cardnum ? true : false);
                exit;
            }
            //非法的银行编号
            if (!($acb = $this->model()->select()->from('acb')->where(array('fields' => 'code=?', 'values' => array($bankcode)))->fetchRow())) {
                echo $this->ret->put('503', $cardnum ? true : false);
                exit;
            }
            //银行接口维护
            if ($acb['is_state'] == '1') {
                echo $this->ret->put('502', $cardnum ? true : false);
                exit;
            }
        }

        $this->params = array('version' => $version, 'customerid' => $customerid, 'sdorderno' => $sdorderno, 'total_fee' => number_format($total_fee, 2, '.', ''), 'paytype' => $paytype, 'bankcode' => $bankcode, 'notifyurl' => $notifyurl, 'returnurl' => $returnurl, 'remark' => $remark, 'sign' => $sign, 'cardnum' => $cardnum, 'fromurl' => $fromurl,'txnType'=>$txnType,'cct'=>$cct,'accNo'=>$accNo,'phone'=>$phone,'settAccNo'=>$settAccNo,'settAccNoName'=>$settAccNoName,'idNo'=>$idNo,'settPhone'=>$settPhone);
        file_put_contents('api_log.txt',json_encode($this->params,JSON_UNESCAPED_UNICODE).PHP_EOL."\r",FILE_APPEND);
    }
}
?>
