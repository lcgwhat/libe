<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

global $userid,$userkey;
$app=woodyapp::getInstance(); //单例
$acp=new Payacp();
$acpData=$acp->get('swift');
extract($acpData);
?>
