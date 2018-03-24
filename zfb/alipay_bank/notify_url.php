<?php
require_once 'inc.php';
require_once 'alipay.config.php';
require_once 'lib/alipay_notify.class.php';
require_once 'Http.php';

$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {

    $out_trade_no	= $_POST['out_trade_no'];
    $trade_no		= $_POST['trade_no'];
    $total_fee		= $_POST['total_fee'];

    if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS') {
		echo "success";
		$url='http://www.vftpay.com/pay/alipay_bank/callback.php';
		$data=array(
			'orderid'=>$out_trade_no,
			'money'=>$total_fee,
			'sign'=>md5($out_trade_no.$total_fee.$userkey),
		);
		$http=new Http($url,$data);
		$http->toUrl();
		$ret=$http->getResContent();
    }
    else {
        echo "success";
    }
}
?>
