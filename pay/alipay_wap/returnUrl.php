<?php
require_once 'inc.php';
require_once 'alipay.class.php';
use WY\app\model\Handleorder;
use WY\app\model\Pushorder;

$alipay=new alipay();
$alipay->userid=$userid;
$alipay->userkey=$userkey;
if($ret=$alipay->isReturn()){
    $handle=new Handleorder($ret['orderid'],$ret['total_fee']);
    $handle->updateUncard();
}

$push=new Pushorder($_GET['out_trade_no']);
$push->sync();
?>
