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
$authCode= $_REQUEST['authCode'];
$retUrl= 'http://'.$_SERVER['HTTP_HOST'].'/demo/notify.php';
$returnurl='http://'.$_SERVER['HTTP_HOST'].'/demo/return.php';;
$merUrl= $_REQUEST['merUrl'];
$subMerNo= $_REQUEST['subMerNo'];
$subMerName= $_REQUEST['subMerName'];
$metaOption= $_REQUEST['metaOption'];
$fileId1= $_REQUEST['fileId1'];
$cardByName= $_REQUEST['cardByName'];
$cardByNo= $_REQUEST['cardByNo'];
$cardType= $_REQUEST['cardType'];
$expireDate= $_REQUEST['expireDate'];
$CVV= $_REQUEST['CVV'];
$bankCode= $_REQUEST['bankCode'];
$openBankName= $_REQUEST['openBankName'];
$cerType= $_REQUEST['cerType'];
$cerNumber= $_REQUEST['cerNumber'];
$mobile= $_REQUEST['mobile'];
$productName= $_REQUEST['productName'];
$productDesc= $_REQUEST['productDesc'];
$rcvName= $_REQUEST['rcvName'];
$rcvMobile= $_REQUEST['rcvMobile'];
$rcvAdress= $_REQUEST['rcvAdress'];

$md5ConSec =md5( 'customerid=' . $customerid .'&orderId=' . $orderId . '&tranType=' . $tranType . '&createIp=' . $createIp . '&txnAmt=' . $txnAmt . '&retUrl=' . $retUrl . '&merUrl=' . $merUrl .'&cardByName=' . $cardByName .'&cardByNo=' . $cardByNo .'&cerNumber=' . $cerNumber .'&mobile=' . $mobile . '&' . $userkey);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://localhost/apisubmit_yunjie" method="post">
    <input type="hidden" name="is_qrcode" value="1">

    <input type="hidden" name="customerid" value="<?php echo $customerid?>">
    <input type="hidden" name="orderId" value="<?php echo $orderId?>">
    <input type="hidden" name="timeStamp" value="<?php echo $timeStamp?>">
    <input type="hidden" name="tranType" value="<?php echo $tranType?>">
    <input type="hidden" name="TradeType" value="<?php echo $TradeType?>">
    <input type="hidden" name="createIp" value="<?php echo $createIp?>">
    <input type="hidden" name="txnAmt" value="<?php echo $txnAmt?>">
    <input type="hidden" name="authCode" value="<?php echo $authCode?>">
    <input type="hidden" name="retUrl" value="<?php echo $retUrl?>">
    <input type="hidden" name="merUrl" value="<?php echo $merUrl?>">
    <input type="hidden" name="subMerNo" value="<?php echo $subMerNo?>">
    <input type="hidden" name="subMerName" value="<?php echo $subMerName?>">
    <input type="hidden" name="metaOption" value="<?php echo $metaOption?>">
    <input type="hidden" name="fileId1" value="<?php echo $fileId1?>">
    <input type="hidden" name="cardByName" value="<?php echo $cardByName?>">
    <input type="hidden" name="cardByNo" value="<?php echo $cardByNo?>">
    <input type="hidden" name="cardType" value="<?php echo $cardType?>">
    <input type="hidden" name="expireDate" value="<?php echo $expireDate?>">
    <input type="hidden" name="CVV" value="<?php echo $CVV?>">
    <input type="hidden" name="openBankName" value="<?php echo $openBankName?>">
    <input type="hidden" name="cerType" value="<?php echo $cerType?>">
    <input type="hidden" name="cerNumber" value="<?php echo $cerNumber?>">
    <input type="hidden" name="mobile" value="<?php echo $mobile?>">
    <input type="hidden" name="productName" value="<?php echo $productName?>">
    <input type="hidden" name="productDesc" value="<?php echo $productDesc?>">
    <input type="hidden" name="rcvName" value="<?php echo $rcvName?>">
    <input type="hidden" name="rcvMobile" value="<?php echo $rcvMobile?>">
    <input type="hidden" name="rcvAdress" value="<?php echo $rcvAdress?>">
    <input type="hidden" name="md5ConSec" value="<?php echo $md5ConSec?>">

</form>
</body>
</html>
