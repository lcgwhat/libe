<?php
require_once 'inc.php';
require_once 'Swiftpay.class.php';
use WY\app\model\Handleorder;

$orderid=$_POST['orderid'];
if($orderid){
    $data=array(
        'out_trade_no'=>$orderid,
    );
    $pay=new Swiftpay();
    $pay->userid=$userid;
    $pay->userkey=$userkey;
    if($ret=$pay->queryOrder($data)){
        $handle=@new Handleorder($ret['orderid'],$ret['total_fee']/100);
        $handle->updateUncard();
        echo 'ok';exit;
    }
}
echo 'err';
?>
