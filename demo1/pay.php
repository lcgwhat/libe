<?php

/*同名支付*/
require_once "inc.php";
$version = $_REQUEST['version'];
$customerid=$userid;
$sdorderno=time()+mt_rand(1000,9999);//商户订单号
$total_fee=number_format($_REQUEST['total_fee'],2,'.','');
$paytype=$_REQUEST['paytype']; //支付方式
$bankcode=isset($_REQUEST['bankcode'])?$_REQUEST['bankcode']:'';//银行编号
$notifyurl='http://'.$_SERVER['HTTP_HOST'].'/demo/notify.php';//异步回调地址
$returnurl='http://'.$_SERVER['HTTP_HOST'].'/demo/return.php';//同步回调地址
$remark=''; //备注信息
$get_code=$_REQUEST['get_code']; //

$txnType=$_REQUEST['txnType'];//支付类型
$cct = $_REQUEST['cct']; //支付币种
$accNo = $_REQUEST['accNo']; //支付卡号
$phone = $_REQUEST['phone'];//支付卡银行预留手机号
$settAccNo = $_REQUEST['settAccNo'];//结算银行卡h号
$settAccNoName = $_REQUEST['settAccNoName'];//结算卡持卡人姓名
$idNo = $_REQUEST['idNo'];
$settPhone = $_REQUEST['settPhone'];//结算卡银行预留手机号
$sign=md5('version='.$version.'&customerid='.$customerid.'&total_fee='.$total_fee.'&sdorderno='.$sdorderno.'&notifyurl='.$notifyurl.'&returnurl='.$returnurl.'&'.$userkey);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://localhost/apisubmit" method="post">
    <input type="hidden" name="is_qrcode" value="1">

    <input type="hidden" name="version" value="<?php echo $version?>">
    <input type="hidden" name="customerid" value="<?php echo $customerid?>">
    <input type="hidden" name="sdorderno" value="<?php echo $sdorderno?>">
    <input type="hidden" name="total_fee" value="<?php echo $total_fee?>">
    <input type="hidden" name="paytype" value="<?php echo $paytype?>">
    <input type="hidden" name="bankcode" value="<?php echo $bankcode?>">
    <input type="hidden" name="notifyurl" value="<?php echo $notifyurl?>">
    <input type="hidden" name="returnurl" value="<?php echo $returnurl?>">
    <input type="hidden" name="remark" value="<?php echo $remark?>">
    <input type="hidden" name="get_code" value="<?php echo $get_code?>">

    <input type="hidden" name="txnType" value="<?php echo $txnType?>">
    <input type="hidden" name="cct" value="<?php echo $cct?>">
    <input type="hidden" name="accNo" value="<?php echo $accNo?>">
    <input type="hidden" name="phone" value="<?php echo $phone?>">
    <input type="hidden" name="settAccNo" value="<?php echo $settAccNo?>">
    <input type="hidden" name="settAccNoName" value="<?php echo $settAccNoName?>">
    <input type="hidden" name="idNo" value="<?php echo $idNo?>">
    <input type="hidden" name="settPhone" value="<?php echo $settPhone?>">

    <input type="hidden" name="sign" value="<?php echo $sign?>">

</form>
</body>
</html>
