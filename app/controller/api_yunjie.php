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

        //自定义支付方式为云捷支付
        $paytype = isset($_REQUEST['paytype'])?$_REQUEST['paytype']:'yunjie';

        $customerid = $this->req->request('customerid');//商户号
        $TradeType= $this->req->request('TradeType');
        $timeStamp= $this->req->request('timeStamp');
        $orderId= $this->req->request('orderId');
        $tranType= $this->req->request('tranType');
        $createIp= $this->req->request('createIp');

        $txnAmt= $this->req->request('txnAmt');
        $authCode= $this->req->request('authCode');
        $retUrl= $this->req->request('retUrl');
        $merUrl= $this->req->request('merUrl');
        $subMerNo= $this->req->request('subMerNo');

        $subMerName= $this->req->request('subMerName');
        $metaOption= $this->req->request('metaOption');
        $fileId1= $this->req->request('fileId1');
        $cardByName= $this->req->request('cardByName');
        $cardByNo= $this->req->request('cardByNo');

        $cardType= $this->req->request('cardType');
        $expireDate= $this->req->request('expireDate');
        $CVV= $this->req->request('CVV');
        $bankCode= $this->req->request('bankCode');
        $openBankName= $this->req->request('openBankName');

        $cerType= $this->req->request('cerType');
        $cerNumber= $this->req->request('cerNumber');
        $mobile= $this->req->request('mobile');
        $productName= $this->req->request('productName');
        $productDesc= $this->req->request('productDesc');

        $rcvName= $this->req->request('rcvName');
        $rcvMobile= $this->req->request('rcvMobile');
        $rcvAdress= $this->req->request('rcvAdress');
        $md5ConSec= $this->req->request('md5ConSec');
        $cardnum = $this->req->request('cardnum');

        $fromurl = $this->req->request('fromurl');/*来源地址*/

        //$this->ret->put() : 是conFig.php的一个方法 用与错误信息的显示
        if (!isset($_REQUEST) || !$_REQUEST) {
            echo $this->ret->put('208', $cardnum ? true : false);
            exit;
        }

        if ($customerid == '' ||$TradeType == ''||  $timeStamp == '' || $orderId == ''||  $createIp == '' || $txnAmt == '' || $retUrl == '' || $merUrl == '' || $cardByName == '' ||  $cardByNo == '' ||  $cerNumber == '' ||  $mobile == '' ||  $productName == '' || $cardType == '' || $md5ConSec == '') {
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
            'TradeType' => $TradeType, //交易类型（0413）
            'timeStamp' =>  $timeStamp, //时间
            'sdorderno' => $orderId,  //订单号
            'tranType' => $tranType, //业务类型：扫码支付:1  刷卡支付:2   WAP支付:3 （非必须）

            'createIp' => $createIp, //用户ip
            'txnAmt' => $txnAmt,  //交易金额
            'authCode' => $authCode,  //二维码内容 （非必须）
            'retUrl' => $retUrl, //异步通知地址
            'merUrl' => $merUrl, //同步通知地址

            'cardByName' => $cardByName, //持卡人姓名
            'cardByNo' => $cardByNo,    //卡号
            'cerNumber' => $cerNumber, //身份证号
            'mobile'=>$mobile,   //手机号码
            'productName' => $productName, //商品名称

            'productDesc' => $productDesc, //商品描述 （非必须）
            'subMerNo' => $subMerNo,  //商户识别id  (非必须)
            'subMerName' => $subMerName, //收款商户名称 (非必须)
            'metaOption' => $metaOption, //WAP此字段不能为空
            'fileId1' => $fileId1,  //备用字段 （非必须）

            'cardType' => $cardType,  //卡类型 00:贷记卡  01:借记卡  02:准贷记卡
            'expireDate' => $expireDate, //有效期 卡类型为00时必须
            'CVV' => $CVV,  //cardType为00 时必填
            'bankCode' => $bankCode, //银行编码（非必须）
            'openBankName' => $openBankName, //开户银行（非必须）

            'cerType' => $cerType, //证件类型 （非必须）
            'rcvName' => $rcvName, //收货人姓名 （非必须）
            'rcvMobile' => $rcvMobile, //收件人手机号（非必须）
            'rcvAdress' => $rcvAdress, //收件人地址（非必须）
            'md5ConSec' => $md5ConSec, //签名

            'cardnum' => $cardnum,
            'fromurl' => $fromurl
        );
        file_put_contents('api_log.txt',json_encode($this->params,JSON_UNESCAPED_UNICODE).PHP_EOL."\r",FILE_APPEND);
    }
}
?>
