<?php
$sign='4e596c59fe10c4aca11f6409c4af8e54';
$charCode='GBK';
$Version='2.0.1';
$ChannelID='6600000000000232';
$bmMerId='6600000000000232';
$timeStamp='20180323164645';
$orderId='ane2018032316462484685';
$platOrderId='30102018032310904566MNZB';
$txnAmt='1';
$resultCode='00';
$resultDesc='交易成功';
$srt=$charCode.$Version.$ChannelID.$bmMerId.$timeStamp.$orderId.$platOrderId.$txnAmt.$resultCode.$resultDesc.'68b2dc377jlt0vewl4u9g4nc';
echo strtolower(md5($srt));