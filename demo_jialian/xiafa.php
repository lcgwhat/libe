<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 21:58
 */
require_once 'inc.php';
$customerid='11269';
$sdorderno=$_POST['sdorderno'];
$name=$_POST['name'];
$bank=$_POST['bank'];
$dizhi=$_POST['dizhi'];
$tel=$_POST['tel'];
$account=$_POST['account'];
$apikey='47d89e4cdb03473b6a5c29caee87f4b147fa17be';
$str='customerid='.$customerid.'&sdorderno='.$sdorderno.'&name='.$name.'&account='.$account.'&bank='.$bank.'&dizhi='.$dizhi.'&tel='.$tel.'&'.$apikey;
$sign=md5($str);
?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://www.hzmhkj.com.com/apixiafa" method="post">
    <input type="hidden" name="customerid" value="<?php echo $customerid?>">
    <input type="hidden" name="sdorderno" value="<?php echo $sdorderno?>">
    <input type="hidden" name="name" value="<?php echo $name?>">
    <input type="hidden" name="account" value="<?php echo $account?>">
    <input type="hidden" name="bank" value="<?php echo $bank?>">
    <input type="hidden" name="dizhi" value="<?php echo $dizhi?>">
    <input type="hidden" name="tel" value="<?php echo $tel?>">

    <input type="hidden" name="sign" value="<?php echo $sign?>">

</form>
</body>
</html>


