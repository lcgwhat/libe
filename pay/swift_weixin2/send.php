<?php
require_once 'inc.php';
require_once 'weixin.class.php';

$orderid=$_GET['orderid'];
$price=$_GET['price'];

$data=array();
$data['service']='pay.weixin.jswap';
$data['version']='1.0';
$data['charset']='UTF-8';
$data['sign_type']='MD5';
$data['mch_id']=$userid;
$data['groupno']='';
$data['out_trade_no']=$orderid;
$data['device_info']='';
$data['body']=$orderid;
$data['attach']='';
$data['total_fee']=$price*100;
$data['notify_url']='http://'.$_SERVER['HTTP_HOST'].'/pay/swift_wxh5/notifyUrl.php';
$data['callback_url']='http://'.$_SERVER['HTTP_HOST'].'/pay/swift_wxh5/returnUrl.php?orderid='.$orderid;
$data['mch_create_ip']=$_SERVER['REMOTE_ADDR'];
$data['time_start']=date('Y').date('m').date('d').date('H').date('i').date('s');
$data['time_expire']='';
$data['op_user_id']=$userid;
$data['op_shop_id']='';
$data['op_device_id']='';
$data['goods_tag']='';
$data['nonce_str']=md5(mt_rand(time(),time()+mt_rand(10000,99999)));

$weixin=new weixin();
$weixin->setPostData($data);
var_dump($weixin->setPostData($data));
$weixin->setKey($userkey);
$weixin->makeSign()->submitOrder();
$result=$weixin->getResContent();

if($result['status']==0 && $result['result_code']==0){
	$payUrl=$result['pay_info'];
} else {
	$weixin->logs('[status='.$result['status'].']',$result);
	$payUrl='';
}
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>微信wap手机支付</title>
	<style>
	*{padding:0;margin:0;}
	body{background-color:#f1f1f1;}
	#main{margin:0 20px 50px 20px;border:1px solid #ccc;border-radius:5px;background-color:#fff;}
	.content{padding:20px 15px}
	dl{margin:15px 0;}
	dl dd{padding:5px 0;color:#333;text-align:left;}
	dl dd span{font-size:1.3em;}
	.btn{display:block;background-color:#2BAD13;color:#fff;text-decoration:none;padding:8px 0;text-align:center;border-radius:3px;}
	#logo{margin-top:30px;background:url(weixin_pay.png) center center no-repeat;height:50px;}
	</style>
</head>

<body>
	<div id="logo"></div>
	<div id="main">
		<div class="content">
			<?php if($payUrl):?>
				<dl>
					<dd>订单号码：<span><?php echo $orderid?></span></dd>
					<dd>付款金额：<span><?php echo $price?></span>元</dd>
				</dl>
				<a href="<?php echo $payUrl?>" class="btn">立即支付</a>
			<?php else:?>
				<p style="text-align:center">支付出现错误，请重试或联系客服！</p>
			<?php endif?>
		</div>
	</div>
</body>
</html>
