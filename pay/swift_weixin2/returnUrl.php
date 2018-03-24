<?php
require_once 'inc.php';
require_once 'weixin.class.php';
use WY\app\model\Pushorder;
use WY\app\model\Handleorder;

$orderid=$_GET['orderid'];

$data=array();
$data['service']='unified.trade.query';
$data['version']='1.0';
$data['charset']='UTF-8';
$data['sign_type']='MD5';
$data['mch_id']=$userid;
$data['out_trade_no']=$orderid;
$data['transaction_id']='';
$data['nonce_str']=md5(mt_rand(100000,time()));

$weixin=new weixin();
$weixin->setPostData($data);
$weixin->setKey($userkey);
$weixin->makeSign()->submitOrder();
$result=$weixin->getResContent();

$weixin->setPostData($result);
$weixin->setKey($userkey);
$weixin->makeSign();
if(isset($result['message'])){
	$weixin->logs('[fail]message='.$result['message'],$result);
}

if($weixin->getSign()!=$result['sign']){
	$weixin->logs('[fail]notify='.$weixin->getSign(),$result);
}

if($result['status']==0 && $result['result_code']==0){
	if(strtolower($result['trade_state'])=='success'){
		$total_fee=$weixin->getParam('total_fee');

		$handle=@new Handleorder($orderid,$total_fee/100);
		$handle->updateUncard();

	} else {
		$weixin->logs('[trade_state='.$result['trade_state'].']queryOrder',$result);
	}
} else {
	$weixin->logs('[status='.$result['status'].']queryOrder',$result);
}

$push=new Pushorder($orderid);
$push->sync();
?>
