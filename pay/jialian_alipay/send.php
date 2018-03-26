<?php
require_once 'inc.php';
$orderid=$_GET['orderid'];
$version='1.0';
$customerid=$userid;
$sdorderno=$orderid;
$total_fee=(float)number_format($_GET['price'],2,'.','');
$paytype=$_GET['paytype'];
$bankcode=$_GET['bankcode'];
$notifyurl='http://'.$_SERVER['HTTP_HOST'].'/pay/jialian_jialian/notifyUrl.php';
$returnurl='http://'.$_SERVER['HTTP_HOST'].'/pay/jialian_jialian/returnUrl.php';
$remark='';
$str='version='.$version.'&customerid='.$customerid.'&total_fee='.$total_fee.'&sdorderno='.$sdorderno.'&notifyurl='.$notifyurl.'&returnurl='.$returnurl.'&'.$userkey;


$sign=md5('version='.$version.'&customerid='.$customerid.'&total_fee='.$total_fee.'&sdorderno='.$sdorderno.'&notifyurl='.$notifyurl.'&returnurl='.$returnurl.'&'.$userkey);


?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://www.hzmhkj.com/apisubmit" method="post">
    <input type="hidden" name="version" value="<?php echo $version?>">
    <input type="hidden" name="customerid" value="<?php echo $customerid?>">
    <input type="hidden" name="sdorderno" value="<?php echo $sdorderno?>">
    <input type="hidden" name="total_fee" value="<?php echo $total_fee?>">
    <input type="hidden" name="paytype" value="<?php echo $paytype?>">
    <input type="hidden" name="notifyurl" value="<?php echo $notifyurl?>">
    <input type="hidden" name="returnurl" value="<?php echo $returnurl?>">
    <input type="hidden" name="remark" value="<?php echo $remark?>">
    <input type="hidden" name="bankcode" value="<?php echo $bankcode?>">
    <input type="hidden" name="sign" value="<?php echo $sign?>">
    <input type="hidden" name="get_code" value="<?php echo $get_code?>">
</form>
</body>
</html>


