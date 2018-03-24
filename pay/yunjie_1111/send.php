<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 16:47
 */
require_once 'inc.php';
require_once 'Base1.class.php';
require_once 'dongtaimiyao.php';
require_once 'tongyixiadan.php';
require_once 'shengqingpay.php';
use WY\app\libs\Xml;
if (!defined('WY_ROOT')) {
    exit;
}
$charCode= 'GBK'; //参数编k码方式，暂时只支持GBK。
$Version= '2.0.1'; //接口版本号（2.0.1）
$TradeType='0413'; //交易类型 请参考6.3业务类型码
$TradeType1='0707';
$TradeType2='0707';


$ChannelID= '6600000000000232'; //渠道号（同商户编号）
$bmMerId  = '6600000000000232';  //商户编号
$timeStamp= date('YmdHis',time()); //时间戳，当前接口调用时间，具体格式: yyyyMMddHHmmss
$orderId  = 'ordqr3o0545';  //商户订单号，商户系统唯一
$orderId1 = 'orde211p55548';

//$tranType='1';  //业务类型
$createIp = '59.61.99.66'; //用户的ip

$txnAmt = '50000';  //交易金额（单位：分）

$retUrl = 'https://www.baidu.com/';  //异步通知地址
$merUrl = 'https://www.hao123.com/?tn=91544714_hao_pg';  //页面通知，支付成功后跳转到该地址，参数详见页面通知
$transCurrency = '156'; //交易币种，固定：156

$cardByName = '郑辉';  //持卡人姓名
$cardByNo = '622909111000656015';   //持卡卡号
$cardType = '01';  //卡类型    00 贷记卡    01 借记卡   02 准贷记卡

//中间签名非需要
//$expireDate= $_REQUEST['version'];
//$CVV= $_REQUEST['version'];
//$bankCode= $_REQUEST['version'];
//$openBankName= $_REQUEST['version'];
//$cerType= $_REQUEST['version'];


$cerNumber= '350104198902044916'; //证件号码
$mobile= '13675004392';  //手机号
$productName= 'iPhoneX';  //商品名称
$md5key='lpv3h8v8ymq19a0xchgve4esgpf1rlvx';

//后面签名非需要
//$productDesc= $_REQUEST['version'];
//$rcvName= $_REQUEST['version'];
//$rcvMobile= $_REQUEST['version'];
//$rcvAdress= $_REQUEST['version'];
//$fileId1= $_REQUEST['version'];
//$fileId1= $_REQUEST['version'];
//$fileId1= $_REQUEST['version'];

//

// var_dump($orderinfo);


/*----------------------------------统一下单参数------------------------------*/


/*----------------------------------动态秘钥签名------------------------------*/

$md5C=md5($charCode.$Version.$TradeType.$ChannelID.$bmMerId.$timeStamp.$orderId.$md5key);

$md5ConS=strtolower($md5C);

/*----------------------------------动态秘钥参数------------------------------*/
/*-------------------------------获取动态秘钥----------------------------------*/

$order=array(
    'charCode'=>$charCode,
    'Version'=> $Version,
    'TradeType'=>$TradeType,
    'ChannelID'=>$ChannelID,
    'bmMerId'=> $bmMerId,
    'timeStamp'=>$timeStamp,
    'orderId'=>$orderId,
    'md5key'=>'lpv3h8v8ymq19a0xchgve4esgpf1rlvx'
);
$resKey=dongtaikey($order);
/*--------------------------------统一下单接口---------------------------------*/

/*-----------------------------------------------------------------*/
$oinfo=array(
    'charCode'=>$charCode,
    'Version'=> $Version,
    'TradeType'=>$TradeType2,
    'ChannelID'=>$ChannelID,
    'bmMerId'=> $bmMerId,
    'timeStamp'=>$timeStamp,
    'orderId'=>$orderId1,
    'createIp'=>$createIp,
    'txnAmt'=> $txnAmt,
    'retUrl'=> $retUrl,
    'merUrl'=> $merUrl,
    'productName'=> $productName,
    'md5key'=>'lpv3h8v8ymq19a0xchgve4esgpf1rlvx'
);
$url=tongyixiadan($oinfo,$resKey);
/*----------------------------------申请支付签名------------------------------*/
/*----------------------------------申请支付参数------------------------------*/

$orderinfo=array(
    'charCode'=>$charCode,
    'Version'=> $Version,
    'TradeType'=>$TradeType1,
    'ChannelID'=>$ChannelID,
    'bmMerId'=> $bmMerId,
    'timeStamp'=>$timeStamp,
    'orderId'=>$orderId,
    'createIp'=>$createIp,
    'txnAmt'=> $txnAmt,
    'retUrl'=> $retUrl,
    'merUrl'=> $merUrl,
    'transCurrency'=> $transCurrency,
    'cardByName'=> $cardByName,
    'cardByNo'=> $cardByNo,
    'cardType'=> $cardType,
    'cerNumber'=> $cerNumber,
    'mobile'=> $mobile,
    'productName'=> $productName,
    'md5key'=>'lpv3h8v8ymq19a0xchgve4esgpf1rlvx'
);
shengqingpay($orderinfo,$resKey);

