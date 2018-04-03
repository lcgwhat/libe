<?php
require_once 'inc.php';
require_once 'Swiftpay.class.php';
use WY\app\model\Handleorder;

// $out_trade_no = $_POST['out_trade_no']; //原支付请求的商户订单号
// $trade_no = $_REQUEST['trade_no'];  //原支付请求的平台订单号
// $total_amount = $_REQUEST['total_amount']; //发起交易的支付金额
// $bank_type = $_REQUEST['bank_type'];  //银行类型，采用字符串类型的银行标识
// $cash_fee = $_REQUEST['cash_fee'];  //现金支付金额订单现金支付金额
// $fee_type = $_REQUEST['fee_type']; //货币类型，符合ISO4217标准的三位字 母代码，默认人民币：CNY
// $is_subscribe = $_REQUEST['is_subscribe']; //用户是否关注公众账号，Y-关注，N-未 关注，仅在公众账号类型支付有效
// $openid = $_REQUEST['openid']; //用户在商户appid下的唯一标识
// $pay_time = $_REQUEST['pay_time']; //支付完成时间，格式为 yyyyMMddHHmmss
// $trade_type = $_REQUEST['trade_type']; //JSAPI、NATIVE、APP

$data=file_get_contents('php://input');
//file_put_contents('result.txt',$data);
$resArr=json_decode($data,true);

//上游的接口文档没有进行验签

//echo 'success';
$handle=@new Handleorder('ane2018032615390012801','1.22');
$handle->updateUncard();
?>
