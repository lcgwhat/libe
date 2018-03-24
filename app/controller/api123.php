<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 16:12
 */
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\model\Retmsg;
if (!defined('WY_ROOT')) {
    exit;
}
class api123 extends Controller
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
        $cardNo  = $this->req->request('cardNo'); /*银行卡号码*/
        $cardName = $this->req->request('cardName'); /*持卡人姓名*/
        $idCardNo = $this->req->request('idCardNo');/*持卡人身份证号码*/
        $phoneNum = $this->req->request('phoneNum');//*银行预留手机号*/
        $sign = $this->req->request('sign');/*签名信息*/
        $cardnum = $this->req->request('cardnum');

        //$this->ret->put() : 是conFig.php的一个方法 用与错误信息的显示
        if (!isset($_REQUEST) || !$_REQUEST) {
            echo $this->ret->put('208', $cardnum ? true : false);
            exit;
        }
        if ($version == '' || $customerid == '' || $cardNo == '' || $sdorderno == '' || $idCardNo == '' || $cardName == '' || $phoneNum == '' || $sign == '') {
            echo $this->ret->put('200', $cardnum ? true : false);
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

        $this->params = array('version' => $version,
            'customerid' => $customerid,
            'sdorderno' => $sdorderno,
            'cardNo'=>$cardNo,
            'cardName'=>$cardName,
            'idCardNo'=>$idCardNo,
            'phoneNum'=>$phoneNum,
            'sign'=>$sign);

    }
}