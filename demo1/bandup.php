<?php
require_once 'inc.php';
$version='1.0';
$customerid=$userid;
$sdorderno=$_REQUEST['sdorderno'];
$cardNo=$_REQUEST['cardNo'];
$cardName=$_REQUEST['cardName'];
$idCardNo=$_REQUEST['idCardNo'];
$phoneNum=$_REQUEST['phoneNum'];
$str='version='.$version.'&customerid='.$customerid.'&sdorderno='.$sdorderno.'&cardNo='.$cardNo.'&cardName='.$cardName.'&idCardNo='.$idCardNo.'&phoneNum='.$phoneNum.'&'.$userkey;
$sign=md5('version='.$version.'&customerid='.$customerid.'&sdorderno='.$sdorderno.'&cardNo='.$cardNo.'&cardName='.$cardName.'&idCardNo='.$idCardNo.'&phoneNum='.$phoneNum.'&'.$userkey);

?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://jhzf.ilibei.com/apishiming" method="post">
    <input type="hidden" name="version" value="<?php echo $version ?>">
    <input type="hidden" name="customerid" value="<?php echo $customerid?>">
    <input type="hidden" name="sdorderno" value="<?php echo $sdorderno?>">
    <input type="hidden" name="cardNo" value="<?php echo $cardNo?>">
    <input type="hidden" name="cardName" value="<?php echo $cardName?>">
    <input type="hidden" name="idCardNo" value="<?php echo $idCardNo?>">
    <input type="hidden" name="phoneNum" value="<?php echo $phoneNum?>">
    <input type="hidden" name="sign" value="<?php echo $sign?>">

</form>
</body>
</html>
