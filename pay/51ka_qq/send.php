<?php
require_once 'inc.php';

$customerid=$userid;
$sdcustomno=$_GET['orderid'];
$orderAmount=$_GET['price'];
$orderAmount=$orderAmount*100;
$cardno=32;
$noticeurl='http://'.$_SERVER['HTTP_HOST'].'/pay/51ka_qq/notify_url.php';
$backurl='http://'.$_SERVER['HTTP_HOST'].'/pay/51ka_qq/return_url.php';
$mark=$sdcustomno;

$signstr='customerid='.$customerid.'&sdcustomno='.$sdcustomno.'&orderAmount='.$orderAmount.'&cardno='.$cardno.'&noticeurl='.$noticeurl.'&backurl='.$backurl;
$sign=strtoupper(md5($signstr.$userkey));

$postUrl='http://www.51card.cn/gateway/qqpay/qqpay.asp';

header('location:'.$postUrl.'?'.$signstr.'&sign='.$sign.'&mark='.$mark);
?>