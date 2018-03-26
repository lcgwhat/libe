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
use WY\app\libs\Std3Des;
if (!defined('WY_ROOT')) {
    exit;
}
$submitdata=isset($_GET['submitdata'])?$_GET['submitdata']:'';
$STD3=new Std3Des("68b2dc377jlt0vewl4u9g4nc","12345678");
$data=$STD3->decrypt($submitdata);
$data=json_decode($data,true);



$charCode= 'GBK'; //参数编k码方式，暂时只支持GBK。
$Version= '2.0.1'; //接口版本号（2.0.1）
$TradeType='0413'; //交易类型 请参考6.3业务类型码
$TradeType1='0707';
$TradeType2='0707';


$ChannelID= '6600000000000232'; //渠道号（同商户编号）
$bmMerId  = '6600000000000232';  //商户编号
$timeStamp= date('YmdHis',time()); //时间戳，当前接口调用时间，具体格式: yyyyMMddHHmmss
$orderId  = $data['orderId'].'as';  //商户订单号，商户系统唯一
$orderId1 = $data['orderId'];

//$tranType='1';  //业务类型
$createIp = $data['createIp']; //用户的ip

$txnAmt =(string)($data['txnAmt']*100);  //交易金额（单位：分）

$retUrl = 'http://106.14.214.55/pay/yunjie_yunjie/notify.php';  //异步通知地址
$merUrl = 'http://106.14.214.55/pay/yunjie_yunjie/tongbuno.php';  //页面通知，支付成功后跳转到该地址，参数详见页面通知
$transCurrency = '156'; //交易币种，固定：156

$cardByName = $data['cardByName'];  //持卡人姓名
$cardByNo =(string)($data['cardByNo']);   //持卡卡号
$cardType = $data['cardType'];  //卡类型    00 贷记卡    01 借记卡   02 准贷记卡



$cerNumber= (string)($data['cerNumber']); //证件号码
$mobile=(string)($data['mobile']);  //手机号
$productName= $data['productName'];  //商品名称
$md5key='lpv3h8v8ymq19a0xchgve4esgpf1rlvx';
/*----------------------------------统一下单参数------------------------------*/


/*----------------------------------动态秘钥签名------------------------------*/



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
    'md5key'=>$md5key
);

$resKeyarr=dongtaikey($order);
if($resKeyarr['0']!='00')
{
    echo array('resultCode'=>'01','resultDesc'=>$resKeyarr['1']);
    exit;
}
$resKey=$resKeyarr['1'];
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
    'md5key'=>$md5key
);

$url=tongyixiadan($oinfo,$resKey);
if(!$url)
{
    echo json_encode(array('resultCode'=>'01','resultDesc'=>'统一下单错误'),JSON_UNESCAPED_UNICODE);
    exit;
}
else{
    echo json_encode(array('resultCode'=>'00','resultDesc'=>'交易成功','qcode'=>urlencode($url)),JSON_UNESCAPED_UNICODE);

}
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
    'md5key'=>$md5key
);

//$res=shengqingpay($orderinfo,$resKey);
//if(!$res)
//{
//    echo json_encode(array('resultCode'=>'02','resultDesc'=>'申请下单错误'),JSON_UNESCAPED_UNICODE);
//    exit;
//}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>


</body>
</html>

<!doctype html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>QQ支付</title>
    <style>
        *{margin:0;padding:0;}
        body{background:url(image/bg.jpg) repeat;}
        #main{background-color:#fff;padding:1px;width:500px;margin:100px auto;text-align:center;border-radius:3px;box-shadow:5px 5px 30px #333;}
        #content{padding:30px;}
        #title{color:#333;font-size:14px;background-color:#e8e8e8;border-bottom:1px solid #ccc;line-height:60px;}
        #title span{color:#fb180a;font-size:16px;font-weight:bold;}
        #QRmsg{color:#149696;background-color:#e8e8e8;border-top:1px solid #ccc;line-height:28px;padding:20px 0;font-size:16px;}
        .qr_default{background:url(image/icon_pay.png) no-repeat 150px -63px;}
        .qr_succ, .pay_succ{background:url(image/icon_pay.png) no-repeat 150px -3px;}
        .pay_error{background:url(image/icon_pay.png) no-repeat 150px -120px;}
        #msgContent p{text-align:left;padding-left:220px;}
        #msgContent p a{color:#149696;font-weight:bold;}
    </style>
    <script type="text/javascript" src="/static/common/jquery.min.js"></script>
</head>

<body>
<div id="main">
    <div id="title">订单号：<span id="orderid"><?php echo $orderId1?></span>&nbsp;&nbsp;&nbsp;&nbsp;金额：<span><?php echo number_format($txnAmt/100,2,'.','') ?></span> 元</div>
    <div id="content">


<!--        --><?php
//        include('phpqrcode.php');
//        // 二维码数据
//        // 生成的文件名
//        // 纠错级别：L、M、Q、H
//        $errorCorrectionLevel = 'L';
//        // 点的大小：1到10
//        $matrixPointSize = 4;
//        $filename = 'filename.png';
//        QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize,2);
//
//        ?>


        <?php if($url):?>
            <form name="frm1" id="frm1" method="post" action="<?php echo $url?>"></form><script type="text/javascript" language="javascript">setTimeout("document.getElementById('frm1').submit();",100);</script>
            <div id="QRimg"> <span style="font-size:200px;"><img src="./filename.png" alt=""></span>
                <?php
                include('phpqrcode.php');
                // 二维码数据
                // 生成的文件名
                // 纠错级别：L、M、Q、H
                $errorCorrectionLevel = 'L';
                // 点的大小：1到10
                $matrixPointSize = 4;
                $filename = 'filename.png';
                QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize,2);
                ?>
            </div>

        <?php else:?>
            <div>获取二维码失败！</div>

        <?php endif;?>


    </div>
    <div id="QRmsg"><div id="msgContent" class="qr_default"><p>请使用支付宝扫描<br/>二维码以完成支付</p></div></div>
</div>





</body>
</html>
<script>
    function oderquery(t){
        var orderid='<?php echo $orderid ?>';
        $.post('queryOrder.php',{orderid:orderid},function(ret){
            if(ret=='ok'){
                $('#msgContent p').html('请稍候<br>正在处理付款结果...');
                window.location.href='returnUrl.php?orderid='+orderid;
            }
        });

        t=t+1;
        setTimeout('oderquery('+t+')',5000);
    }

    setTimeout('oderquery(1)',5000);
</script>










