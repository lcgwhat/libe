<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23
 * Time: 16:18
 */
require_once 'inc.php';
$charCode=isset( $_GET['charCode'])?$_GET['charCode']:'';
$Version=isset($_GET['Version'])?$_GET['Version']:'';
$ChannelID=isset($_GET['ChannelID'])?$_GET['ChannelID']:'';
$bmMerId=isset($_GET['bmMerId'])?$_GET['bmMerId']:'';
$timeStamp=isset($_GET['timeStamp'])?$_GET['timeStamp']:'';
$orderId=isset($_GET['orderId'])?$_GET['orderId']:'';
$platOrderId=isset($_GET['platOrderId'])?$_GET['platOrderId']:'';
$txnAmt=isset($_GET['txnAmt'])?$_GET['txnAmt']:'';
$resultCode=isset($_GET['resultCode'])?$_GET['resultCode']:'';
$resultDesc=isset($_GET['resultDesc'])?$_GET['resultDesc']:'';
$md5ConSec=isset($_GET['md5ConSec'])?$_GET['md5ConSec']:'';

$srt=$charCode.$Version.$ChannelID.$bmMerId.$timeStamp.$orderId.$platOrderId.$txnAmt.$resultCode.$resultDesc.$md5ConSec;

$data=file_get_contents('php://input');
file_put_contents('notify4.txt',$data);
file_put_contents('notify6.txt',$srt);