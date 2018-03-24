<?php
require_once 'inc.php';
require_once 'Swiftpay.class.php';

$orderid=$_GET['orderid'];
$price=$_GET['price'];

$data=array(
    'out_trade_no'=>$orderid,
    'device_info'=>'',
    'body'=>$orderid,
    'attach'=>'',
    'total_fee'=>$price*100,
    'notify_url'=>'http://'.$_SERVER['HTTP_HOST'].'/pay/swift_weixin/notifyUrl.php',
);

$pay=new Swiftpay();
$pay->userid=$userid;
$pay->userkey=$userkey;
$result=$pay->submitOrder($data);
?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>微信支付(<?php echo $orderid ?>)</title>
<style>
*{margin:0;padding:0;}
body{background:url(image/bg.jpg) repeat;}
#main{background-color:#fff;padding:1px;width:500px;margin:100px auto;text-align:center;border-radius:3px;box-shadow:5px 5px 30px #333;}
#content{padding:30px;}
#title{color:#333;font-size:14px;background-color:#e8e8e8;border-bottom:1px solid #ccc;line-height:60px;}
#title span{color:#fb180a;font-size:16px;font-weight:bold;}
#QRmsg{color:#149696;background-color:#e8e8e8;border-top:1px solid #ccc;line-height:28px;padding:20px 0;font-size:16px;}
.qr_default{background:url(image/icon_pay.png) no-repeat 150px -63px;}
.qr_succ, .pay_succ{background:url(image/icon_pay.png) no-repeat 150px -3px;}
.pay_error{background:url(image/icon_pay.png) no-repeat 150px -120px;}
#msgContent p{text-align:left;padding-left:220px;}
#msgContent p a{color:#149696;font-weight:bold;}
</style>
	<script type="text/javascript" src="/static/common/jquery.min.js"></script>
</head>

<body>
    <div id="main">
        <div id="title">订单号：<span id="orderid"><?php echo $orderid?></span>&nbsp;&nbsp;&nbsp;&nbsp;金额：<span><?php echo number_format($price,2,'.','') ?></span> 元</div>
        <div id="content">
            <?php if($result):?>
                <div id="QRimg"><img src="<?php echo $result?>"></div>
            <?php else:?>
                <div>获取二维码失败！</div>
            <?php endif;?>
        </div>
        <div id="QRmsg"><div id="msgContent" class="qr_default"><p>请使用微信扫描<br/>二维码以完成支付</p></div></div>
    </div>
</body>
</html>
<script>
function oderquery(t){
    var orderid='<?php echo $orderid ?>';
    $.post('queryOrder.php',{orderid:orderid},function(ret){
        if(ret=='ok'){
			$('#msgContent p').html('请稍候<br>正在处理付款结果...');
            window.location.href='returnUrl.php?orderid='+orderid;
        }
    });

    t=t+1;
    setTimeout('oderquery('+t+')',5000);
}

setTimeout('oderquery(1)',5000);
</script>
