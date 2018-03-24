<?php
require_once 'inc.php';
require_once 'Swiftpay.class.php';
use WY\app\model\Handleorder;

$pay=new Swiftpay();
$pay->userid=$userid;
$pay->userkey=$userkey;
if($ret=$pay->notify()){
    echo 'success';
    $handle=@new Handleorder($ret['orderid'],$ret['total_fee']/100);
    $handle->updateUncard();
} else {
    echo 'fail';
}
?>
