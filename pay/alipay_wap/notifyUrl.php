<?php
require_once 'inc.php';
require_once 'alipay.class.php';
use WY\app\model\Handleorder;

$alipay=new alipay();
$alipay->userid=$userid;
$alipay->userkey=$userkey;
if($ret=$alipay->isNotify()){
    echo 'success';

    $handle=new Handleorder($ret['orderid'],$ret['total_fee']);
    $handle->updateUncard();
} else {
    echo 'fail';
}
?>
