<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/21
 * Time: 17:42
 */
require_once 'inc.php';
require_once 'Dkey.class.php';
require_once 'Order.class.php';
require_once 'Payment.class.php';
$DK=new Dkey(); //d动态密钥
date_default_timezone_set('PRC');
//动态密钥
//$timeStamp=date('YmdHis',time());
$timeStamp = '20171126205102';
$order=array(
    'charCode'=>'GBK',
    'Version'=>'2.0.1',
    'TradeType'=>'0413',
    'ChannelID'=>'6600000000000232',
    'bmMerId'=> '6600000000000232',
    'timeStamp'=>$timeStamp,
    'orderId'=>'order111111154545'
);
$res=$DK->index($order);
var_dump($res.'第一次');echo "<br/>";
//统一下单
$oinfo=array(
    'timeStamp'=>$timeStamp,
    'orderId'=>'order111111154548',
    'createIp'=>'59.61.99.66',
    'txnAmt'=> '50000',
    'retUrl'=> 'https://www.baidu.com/',
    'merUrl'=> 'https://www.hao123.com/?tn=91544714_hao_pg',
    'productName'=> 'iPhoneX'
);
$ordermake=new Order();
$orderRes=$ordermake->index($oinfo,$res);
//申请支付
$orderId= 'order111111154545';
$createIp= '59.61.99.66'; //用户的ip
$txnAmt= '50000';  //交易金额（单位：分）
$retUrl= 'https://www.baidu.com/';  //异步通知地址
$merUrl= 'https://www.hao123.com/?tn=91544714_hao_pg';  //
$transCurrency= '156'; //交易币种，固定：156

$cardByName= '郑辉';  //持卡人姓名
$cardByNo= '622909111000656015';   //持卡卡号
$cardType= '01';  //卡类型    00 贷记卡    01 借记卡   02 准贷记卡
$cerNumber= '350104198902044916'; //证件号码
$mobile= '13675004392';  //手机号
$productName= 'iPhoneX';  //商品名称
$md5key='lpv3h8v8ymq19a0xchgve4esgpf1rlvx';

$orderinfo=array(
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
    'productName'=> $productName
);
$payment=new Payment();
$resr=$payment->index($orderinfo,$res);


var_dump('下单：'.$orderRes);
echo "<br>";
var_dump('动态密钥：'.$res);
echo "<br>";
var_dump('申请'.$resr);