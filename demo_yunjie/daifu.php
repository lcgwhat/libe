<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/27
 * Time: 10:56
 */

/*快捷支付申请接口*/
require_once "inc.php";

$customerid=$userid;  //商户号

/*  --------------------------有些参数是非必须的  请看接口文档--------------------------*/

$orderId=$_REQUEST['orderId'];



$txnAmt= number_format($_REQUEST['txnAmt'],2,'.','');

$acctType= $_REQUEST['acctType'];

$acctName= $_REQUEST['acctName'];

$acctNo= $_REQUEST['acctNo'];

$bankSettNo= $_REQUEST['bankSettNo'];

$retUrl= $_REQUEST['retUrl'];


$md5ConSec =md5( 'customerid=' . $customerid .'&orderId=' . $orderId . '&txnAmt=' . $txnAmt . '&acctType=' . $acctType . '&acctName=' . $acctName . '&acctNo=' . $acctNo .'&bankSettNo=' . $bankSettNo .'&retUrl=' . $retUrl  . '&' . $userkey);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://localhost/apisubmit_yjdf" method="post">

    <input type="hidden" name="is_qrcode" value="1">

    <input type="hidden" name="customerid" value="<?php echo $customerid?>">

    <input type="hidden" name="orderId" value="<?php echo $orderId?>">

    <input type="hidden" name="txnAmt" value="<?php echo $txnAmt?>">

    <input type="hidden" name="acctType" value="<?php echo $acctType?>">

    <input type="hidden" name="acctName" value="<?php echo $acctName?>">
    <input type="hidden" name="acctNo" value="<?php echo $acctNo?>">

    <input type="hidden" name="bankSettNo" value="<?php echo $bankSettNo?>">

    <input type="hidden" name="retUrl" value="<?php echo $retUrl?>">

    <input type="hidden" name="md5ConSec" value="<?php echo $md5ConSec?>">

</form>
</body>
</html>
