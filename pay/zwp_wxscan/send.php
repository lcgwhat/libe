<?php
require_once 'inc.php';
require_once 'wyapi.class.php';

$orderid=$_GET['orderid'];
$price=$_GET['price'];

$data=array(
    'order_sn'=>$orderid,
    'body'=>$orderid,
    'money'=>$price,
    'notify'=>'http://'.$_SERVER['HTTP_HOST'].'/pay/zwp_wxscan/notifyUrl.php',
);

$pay=new wyapi();
$pay->userid=$userid;
$pay->userkey=$userkey;
$ret=$pay->submitOrder($data);
?>
<!doctype html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
<title>微信支付(<?php echo $orderid ?>)</title>
<style>
*{padding:0;margin:0;}
body{background-color:#f1f1f1;}
#main{margin:0 auto 50px auto;border:1px solid #ccc;border-radius:5px;background-color:#fff;width:400px;}
.content{padding:20px 15px}
dl{margin:15px 0;}
dl dd{padding:5px 0;color:#333;text-align:left;}
dl dd span{font-size:1.3em;}
.btn{display:block;background-color:#2BAD13;color:#fff;text-decoration:none;padding:8px 0;text-align:center;border-radius:3px;}
#logo{margin-top:30px;background:url(logo.png) center center no-repeat;height:50px;}
</style>
  <style>
            .container{width: 100%; max-width: 600px;}
            .mtm{margin-top: 10px;}
            #QRcode .pay-top{padding: 15px 0px;background: #FAFAFA;border: 2px #009900 dashed;margin: 20px 0px;font-family: 微软雅黑;}
        </style>
		<script type="text/javascript" src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script type="text/javascript" src="/static/common/jquery.min.js"></script>
</head>

<body>
    <div id="logo"></div>
    <div id="main">
        <div class="content">
		 <div class="text-center pay-top"><p class="text-danger">请使用微信客户端扫描二维码以完成支付</p><p>由于微信临时商户限制,您可以使用以下方式完成支付。<br>
			1.保存二维码发送给好友手机，在次扫码手机上二维码。<br>
			2.使用电脑扫码支付。商户投诉时间：09:30-18:30</div>
			 <div class="panel panel-default">
                <div class="panel-heading clearfix">
            <?php if($ret['status']):?>
                <dl>
                    <dd>订单号码：<span><?php echo $orderid?></span></dd>
                    <dd>付款金额：<span><?php echo $price?></span>元</dd>
                    <dd style="text-align:center"><img src="http://qr.liantu.com/api.php?&w=200&text=<?php echo $ret['url'];?>"></dd>
                    <dd style="text-align:center" id="msgContent">请使用手机微信扫描二维码支付。</dd>
                </dl>
            <?php else:?>
                <p style="text-align:center"><?php echo $ret['msg']?></p>
            <?php endif?>
        </div>
    </div>
</body>
</html>
<?php if($ret['status']):?>
<script>
function oderquery(t){
    var orderid='<?php echo $orderid ?>';
    $.get('queryOrder.php',{orderid:orderid},function(ret){
        if(ret.status=='ok'){
            window.location.href='returnUrl.php?orderid='+orderid;
        }
    },'json');

    t=t+1;
    setTimeout('oderquery('+t+')',5000);
}

setTimeout('oderquery(1)',10000);
</script>
<?php endif;?>
