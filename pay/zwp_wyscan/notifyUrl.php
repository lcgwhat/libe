<?php
require_once 'inc.php';
require_once 'wyapi.class.php';
use WY\app\model\Handleorder;
if($_GET['notifly']){
	
	
	if($res==0){
		echo 'fail';
		die;
	}
}
$pay=new wyapi();
$pay->userid=$userid;
$pay->userkey=$userkey;
if($ret=$pay->notifyOrder()){
    echo 'success';
    $handle=new Handleorder($ret['orderid'],$ret['money']);
    $handle->updateUncard();
} else {
    echo 'fail';
}
?>
