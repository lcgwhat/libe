<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';
$p2_Order= $_POST['orderid'];
$p3_Amt= $_POST['price'];
$p4_verifyAmt= "false";
$p5_Pid= "cardpay";
$p6_Pcat= "cardpay";
$p7_Pdesc= "cardpay";
$p8_Url= "http://".$_SERVER['HTTP_HOST']."/pay/ebao_card/woodyapp_callback.php";
$pa_MP= '';
$pa7_cardAmt=$_POST['cardvalue'];
$pa8_cardNo=$_POST['cardnum'];
$pa9_cardPwd=$_POST['cardpwd'];
$pd_FrpId= $_POST['gateway'];
$pr_NeedResponse= "1";
$pz_userId= $_POST['orderid'];
$pz1_userRegTime= date("Y-m-d")." 00:00:00";

annulCard($p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pz_userId,$pz1_userRegTime);
?>
