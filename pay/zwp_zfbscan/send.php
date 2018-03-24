<?php
require_once 'inc.php';
require_once 'wyapi.class.php';

$orderid=$_GET['orderid'];
$price=$_GET['price'];

$data=array(
    'order_sn'=>$orderid,
    'body'=>$orderid,
    'money'=>$price,
    'notify'=>'http://'.$_SERVER['HTTP_HOST'].'/pay/zwp_zfbscan/notifyUrl.php',
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
	<script type="text/javascript" src="/static/common/jquery.min.js"></script>
</head>

<body>
    <div id="logo"></div>
    <div id="main">
        <div class="content">
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
