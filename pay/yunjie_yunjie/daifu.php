<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 14:17
 */
require_once 'inc.php';
require_once 'Dfu.php';
require_once 'dongtaimiyao.php';

$charCode= 'GBK'; //参数编k码方式，暂时只支持GBK。
$Version= '2.0.1'; //接口版本号（2.0.1）
$orderId='ad5min12q38p597999';
$TradeType='0413'; //交易类型 请参考6.3业务类型码
$timeStamp=date('YmdHis',time());

$createIp ='106.14.214.55'; //用户的ip

$txnAmt ='10';  //交易金额（单位：分）
$transCurrency = '156'; //交易币种，固定：156

$cardByName = '柳传庚';  //持卡人姓名
$cardByNo ='';   //持卡卡号
$cardType = '01';  //卡类型    00 贷记卡    01 借记卡   02 准贷记卡
$retUrl = 'http://106.14.214.55/pay/yunjie_yunjie/notify.php';  //异步通知地址
$merUrl = 'http://106.14.214.55/pay/yunjie_yunjie/tongbuno.php';  //页面通知，支付成功后跳转到

$cerNumber= '350425199408140315'; //证件号码
$mobile='18450087519';  //手机号
$productName= '可口可乐';  //商品名称

$order=array(
    'charCode'=>$charCode,
    'Version'=> $Version,
    'TradeType'=>$TradeType,
    'ChannelID'=>$ChannelID,
    'bmMerId'=> $bmMerId,
    'timeStamp'=>$timeStamp,
    'orderId'=>$orderId,
    'md5key'=>$md5key
);

$resKeyarr=dongtaikey($order);
if($resKeyarr['0']!='00')
{
    echo array('resultCode'=>'01','resultDesc'=>$resKeyarr['1']);
    exit;
}
$resKey=$resKeyarr['1'];

$pay=array(
    'charCode'=>'GBK',
    'Version'=>'2.0.1',
    'TradeType'=>'0628',
    'ChannelID'=>$ChannelID,
    'bmMerId'=>$bmMerId,
    'timeStamp'=>date('YmdHis',time()),
    'orderId'=>$orderId,
    'txnAmt'=>'10',
    'acctType'=>'1',
    'acctName'=>'柳传庚',
    'acctNo'=>'6214855914319856',
    'bankSettNo'=>'308391026069',
    'retUrl'=>'106.14.214.55/pay/yunjie_yunjie/dfnotify.php',
    'md5key'=>$md5key
);
//做代付操作
$daifucaoz=dfu($pay,$resKey);
echo $daifucaoz;echo '<br/>';
$qurOr=array(
    'charCode'=>'GBK',
    'Version'=>'2.0.1',
    'TradeType'=>'0630',
    'ChannelID'=>$ChannelID,
    'bmMerId'=>$bmMerId,
    'timeStamp'=>date('YmdHis',time()),
    'orderId'=>$orderId,
    'md5key'=>$md5key
);
//确认代付操作
$querencaoz=dfu($qurOr,$resKey);
echo $querencaoz;echo '<br/>';

$orderinfo=array(
    'charCode'=>$charCode,
    'Version'=> $Version,
    'TradeType'=>'0741',
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
    'md5key'=>$md5key
);
//快捷支付申请
//$kuaijieshengqi=dfu($orderinfo,$resKey);
//echo $kuaijieshengqi;