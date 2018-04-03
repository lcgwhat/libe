<?php

/*快捷支付申请接口*/
require_once "inc.php";

$customerid=$userid;  //商户号

/*  --------------------------有些参数是非必须的  请看接口文档--------------------------*/

$orderId=$_REQUEST['orderId'];
$TradeType= $_REQUEST['TradeType'];
$timeStamp= date('YmdHis',time());
$tranType= $_REQUEST['tranType'];
$createIp= $_REQUEST['createIp'];
$txnAmt= number_format($_REQUEST['txnAmt'],2,'.','');
$retUrl= 'http://'.$_SERVER['HTTP_HOST'].'/demo/notify.php';
$returnurl='http://'.$_SERVER['HTTP_HOST'].'/demo/return.php';;
$merUrl= $_REQUEST['merUrl'];
$productName= $_REQUEST['productName'];


$md5ConSec =md5(  'customerid=' . $customerid .'&orderId=' . $orderId . '&tranType=' . $tranType . '&createIp=' . $createIp . '&txnAmt=' . $txnAmt . '&retUrl=' . $retUrl . '&merUrl=' . $merUrl  .'&' . $userkey);


?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://106.14.214.55/apisubmit_yunjie" method="post">
    <input type="hidden" name="is_qrcode" value="1">

    <input type="hidden" name="customerid" value="<?php echo $customerid?>">
    <input type="hidden" name="orderId" value="<?php echo $orderId?>">
    <input type="hidden" name="timeStamp" value="<?php echo $timeStamp?>">
    <input type="hidden" name="tranType" value="<?php echo $tranType?>">

    <input type="hidden" name="createIp" value="<?php echo $createIp?>">
    <input type="hidden" name="txnAmt" value="<?php echo $txnAmt?>">

    <input type="hidden" name="retUrl" value="<?php echo $retUrl?>">
    <input type="hidden" name="merUrl" value="<?php echo $merUrl?>">

    <input type="hidden" name="productName" value="<?php echo $productName?>">
    <input type="hidden" name="md5ConSec" value="<?php echo $md5ConSec?>">

</form>
</body>
</html>
