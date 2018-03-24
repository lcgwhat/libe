<?php
require_once 'inc.php';
require_once 'wyapi.class.php';

$orderid=$_GET['orderid'];
$price=$_GET['price'];

$data=array(
    'order_sn'=>$orderid,
    'body'=>$orderid,
    'money'=>$price,
	'bankcode'=>$bankcode,
    'notify'=>'http://'.$_SERVER['HTTP_HOST'].'/pay/zwp_wyscan/notifyUrl.php',
);

$pay=new wyapi();
$pay->userid=$userid;
$pay->userkey=$userkey;
$ret=$pay->submitOrder($data);
?>
<!doctype html>

<html>
	<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	</head>
	<body>

		<h2 style="text-align:center;">网关支付Demo</h2>
		<div action="" method="post">
			交易金额：<input type="text" id="money"/><br/>
			<input type="button" id="btn_ok" value="确定"/>
		</div>
		<p id="res"></p>
<script src="http://code.jquery.com/jquery-2.1.1.min.js"></script>
	
	<script>
		$(function(){
			$("#btn_ok").click(function(){
			$("#res").text("");
			var money  = $("#money").val();
			$.post("/demo/gateway_pay/index.php",{'money':money},function(data){
				$("body").append(data);
			});
		});
		});
	</script>
	</body>
</html>


