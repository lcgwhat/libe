<?php
require_once 'inc.php';
require_once 'alipay.class.php';
header('location:http://pay.cd78.com/zfb/alipay_wap/send.php?orderid='.$_GET['orderid'].'&price='.$_GET['price']);exit;

$orderid=$_GET['orderid'];
$price=$_GET['price'];

$data['out_trade_no']=$orderid;
$data['total_fee']=$price;
$data['subject']=$orderid;
$data['body']='';
$data['show_url']='http://'.$_SERVER['HTTP_HOST'];
$data['notify_url']='http://'.$_SERVER['HTTP_HOST'].'/pay/alipay_wap/notifyUrl.php';
$data['return_url']='http://'.$_SERVER['HTTP_HOST'].'/pay/alipay_wap/returnUrl.php';

$alipay=new alipay();
$alipay->userid=$userid;
$alipay->userkey=$userkey;
echo $alipay->submitOrder($data);
?>
