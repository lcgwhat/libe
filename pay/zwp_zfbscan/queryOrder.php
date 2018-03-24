<?php
require_once 'inc.php';
require_once 'wyapi.class.php';

$orderid=$_GET['orderid'];
if($orderid){
    $pay=new wyapi();
    $pay->userid=$userid;
    $pay->userkey=$userkey;
    if($ret=$pay->queryOrder($orderid)){
        //$handle=new handleorder_class($ret['orderid'],$ret['money']/100,1);
        //$handle->updateOrderStatusForBank();
        echo json_encode(array('status'=>'ok'));exit;
    }
}
echo json_encode(array('status'=>'notpay'));
?>
