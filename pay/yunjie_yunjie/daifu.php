<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 14:17
 */
require_once 'inc.php';
require_once 'Base1.class.php';
require_once 'dongtaimiyao.php';
use WY\app\libs\Std3Des;
$des3Util=new STD3Des("68b2dc377jlt0vewl4u9g4nc","12345678");
$base=new Base1();
$charCode= 'GBK'; //参数编k码方式，暂时只支持GBK。
$Version= '2.0.1'; //接口版本号（2.0.1）
$orderId='admin54455654';
$TradeType='0413'; //交易类型 请参考6.3业务类型码
$timeStamp=date('YmdHis',time());

$submitdata=isset($_GET['submitdata'])?$_GET['submitdata']:'';
$STD3=new Std3Des("68b2dc377jlt0vewl4u9g4nc","12345678");
$data=$STD3->decrypt($submitdata);
$data=json_decode($data,true);




//$createIp ='106.14.214.55'; //用户的ip

$txnAmt =$data['txnAmt'];  //交易金额（单位：分）
$transCurrency = '156'; //交易币种，固定：156

  $cardByName = '柳传庚';  //持卡人姓名
  $cardByNo ='';   //持卡卡号
$cardType = '01';  //卡类型    00 贷记卡    01 借记卡   02 准贷记卡
$retUrl = 'http://106.14.214.55/pay/yunjie_yunjie/notify.php';  //异步通知地址
  $merUrl = 'http://106.14.214.55/pay/yunjie_yunjie/tongbuno.php';  //页面通知，支付成功后跳转到
$acctNo =$data['acctNo'];// '621485591431';
$bankSettNo = $data['bankSettNo'];//联行号
$acctName = $data['acctName'];
$cerNumber= $data['cerNumber'];//'350425199408140315'; //证件号码
$mobile='18450087519';  //手机号
$productName= '可口可乐';  //商品名称
//动态密钥参数
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

//动态密钥
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
    'txnAmt'=>$txnAmt,
    'acctType'=>'1',
    'acctName'=>$acctName,
    'acctNo'=>$acctNo,
    'bankSettNo'=>$bankSettNo,
    'retUrl'=>'106.14.214.55/pay/yunjie_yunjie/dfnotify.php',
    'md5key'=>$md5key
);
//做代付操作
$daifucaoz=$base->todo($pay,$resKey);
//var_dump('结果'.json_encode($daifucaoz,JSON_UNESCAPED_UNICODE));
echo '<br/>';
$x= $daifucaoz->resultCode;
$y = $daifucaoz->resultDesc;
if($x!='00')
{

    $sd=array('resultCode'=>$x,'resultDesc'=>$y);
    echo json_encode($sd,JSON_UNESCAPED_UNICODE);
    exit;
}

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
$querencaoz=$base->todo($qurOr,$resKey);
var_dump('结果'.json_encode($querencaoz,JSON_UNESCAPED_UNICODE));
if($querencaoz->resultCode!='00')
{
    $sd=array('resultCode'=>$daifucaoz->resultCode[0],'resultDesc'=>$daifucaoz->resultDesc[0]);
    echo json_encode($sd,JSON_UNESCAPED_UNICODE);
    exit;
}
$sd=array('resultCode'=>$daifucaoz->resultCode,'resultDesc'=>$daifucaoz->resultDesc);
echo json_encode($sd,JSON_UNESCAPED_UNICODE);
echo '<br/>';


//$orderinfo=array(
//    'charCode'=>$charCode,
//    'Version'=> $Version,
//    'TradeType'=>'0741',
//    'ChannelID'=>$ChannelID,
//    'bmMerId'=> $bmMerId,
//    'timeStamp'=>$timeStamp,
//    'orderId'=>$orderId,
//    'createIp'=>$createIp,
//    'txnAmt'=> $txnAmt,
//    'retUrl'=> $retUrl,
//    'merUrl'=> $merUrl,
//    'transCurrency'=> $transCurrency,
//    'cardByName'=> $cardByName,
//    'cardByNo'=> $cardByNo,
//    'cardType'=> $cardType,
//    'cerNumber'=> $cerNumber,
//    'mobile'=> $mobile,
//    'productName'=> $productName,
//    'md5key'=>$md5key
//);
//快捷支付申请
//$kuaijieshengqi=$base->todo($orderinfo,$resKey);
//echo $kuaijieshengqi;