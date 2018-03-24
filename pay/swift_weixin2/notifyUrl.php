<?php
require_once 'inc.php';
require_once 'weixin.class.php';
use WY\app\model\Handleorder;

$weixin=new weixin();
$weixin->setKey($userkey);

if($weixin->verifySign()){
	$result_code=$weixin->getParam('result_code');
	$status=$weixin->getParam('status');
	$pay_result=$weixin->getParam('pay_result');
	$out_trade_no=$weixin->getParam('out_trade_no');
	$total_fee=$weixin->getParam('total_fee');

	if($result_code==0 && $status==0){
		if($pay_result==0){
			echo 'success';
			
			$handle=@new Handleorder($out_trade_no,$total_fee/100);
	        $handle->updateUncard();

		} else {
			$data=$weixin->getPostData();
			$weixin->logs('[fail1]notify',$data);
			echo 'fail1';
		}
	} else {
		$data=$weixin->getPostData();
		$weixin->logs('[fail2]notify',$data);
		echo 'fail2';
	}
} else {
	$data=$weixin->getPostData();
	$weixin->logs('[fail3]notify='.$weixin->getSign(),$data);
	echo 'fail3';
}
?>
