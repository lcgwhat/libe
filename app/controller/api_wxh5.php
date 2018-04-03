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
class api_wxh5 extends Controller
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
        //自定义支付方式为中铁支付（微信H5）
        $paytype = 'wxh5';
        $customerid = $this->req->request('customerid');//商户号
        $sdorderno= $this->req->request('sdorderno');
        $goodname= $this->req->request('goodname');
        $goodtag= $this->req->request('goodtag');
        $total_fee= $this->req->request('total_fee');
        $userip= $this->req->request('userip');
        $notifyurl= $this->req->request('notifyurl');
        $sign= $this->req->request('sign');

        $cardnum = $this->req->request('cardnum');
        $fromurl = $this->req->request('fromurl');/*来源地址*/

        //$this->ret->put() : 是conFig.php的一个方法 用与错误信息的显示
        if (!isset($_REQUEST) || !$_REQUEST) {
            echo $this->ret->put('208', $cardnum ? true : false);
            exit;
        }

        if ($customerid == '' ||$sdorderno == ''||  $goodname == '' || $goodtag == ''||  $total_fee == '' || $userip == '' || $notifyurl == '' || $sign == '') {
            echo $this->ret->put('200', $cardnum ? true : false);
            exit;
        }
        if (strlen($sdorderno) > 50) {
            echo $this->ret->put('203', $cardnum ? true : false);
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
            'paytype'=>$paytype, //交易类型
            'customerid' => $customerid,//下游商户号
            'sdorderno' => $sdorderno,  //订单号
            'goodname' => $goodname,
            'goodtag' => $goodtag,
            'total_fee' => $total_fee,
            'userip' => $userip,
            'notifyurl' => $notifyurl,
            'sign' => $sign,
            'cardnum' => $cardnum,
            'fromurl' => $fromurl
        );
    }
}
?>
