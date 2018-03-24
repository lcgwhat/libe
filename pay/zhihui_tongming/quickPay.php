<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<title>quickPay</title>
</head>
<body onLoad="document.quickPay.submit();">
<?php
use WY\app\libs\Std3Des;
$des3Util=new Std3Des("4C012206573169BF8E9F36BCF5D4125E","12345678");
$version="1.0";
$merId="2000007851";
$merOrderId = "order100000010001";
$merUserId = "10004";
$amount="1001";
$productName = "productName";
$productDesc = "productDesc";
$merOrderTime="20170101111122";
$payTimeOut = "";
$fontUrl="http://www.baidu.com";
$backUrl="http://www.baidu.com";
$resv = "123123";
$txnType="0031";//0031-快捷支付（商旅）有积分    0032-快捷支付（缴费）无积分
$cct="CNY";  
//字段加密
$accNo=$des3Util->encrypt("545445411515151");
$phone=$des3Util->encrypt("18060486737");
$settAccNo=$des3Util->encrypt("545445411515151");
$settAccNoName=$des3Util->encrypt("黄事好");
$idNo=$des3Util->encrypt("350426199308033519");
$settPhone=$des3Util->encrypt("18060486737");
$orderUrl="http://vapi.hzlcpay.com/quickpay/tmplaceOrder.servlet";
?>

<form action="<?php echo ($orderUrl) ?>" name="quickPay"	method="post">
<input type="hidden"   placeholder="版本号" 	name="version" value="<?php echo($version) ?>" />
<input type="hidden"   placeholder="商户号" 	name="merId" value="<?php echo($merId) ?>" /> 
<input type="hidden"   placeholder="商户订单号"	name="merOrderId" value="<?php echo ($merOrderId)?>" />
<input type="hidden"   placeholder="商户用户编号" name="merUserId" value="<?php echo ($merUserId)?>" /> 
<input type="hidden"   placeholder="支付金额(单位:分)"  name="amount" value="<?php echo ($amount )?>" /> 
<input type="hidden"   placeholder="商品名称" name="productName" value="<?php echo ($productName)?>" /> 
<input type="hidden"   placeholder="商品描述" name="productDesc" value="<?php echo ($productDesc )?>">
<input type="hidden"   placeholder="商户订单时间" name="merOrderTime" value="<?php echo ($merOrderTime)?>" /> 
<input type="hidden"   placeholder="最晚支付时间"	name="payTimeOut" value="<?php echo ($payTimeOut)?>" /> 
<input type="hidden"   placeholder="商户取货地址"	name="fontUrl" value="<?php echo ($fontUrl) ?>" />
<input type="hidden"   placeholder="后台通知地址" name="backUrl" value="<?php echo( $backUrl) ?>"> 
<input type="hidden"   placeholder="扩展字段"	name="resv" value="<?php echo ($resv) ?>" />

<input type="hidden"   placeholder="支付类型" name="txnType" value="<?php echo ($txnType)?>" /> 
<input type="hidden"   placeholder="交易币种" name="cct" value="<?php echo ($cct)?>" /> 
<input type="hidden"   placeholder="支付卡号" name="accNo" value="<?php echo ($accNo)?>" /> 
<input type="hidden"   placeholder="支付卡银行预留手机号" name="phone" value="<?php echo ($phone)?>" /> 
<input type="hidden"   placeholder="结算银行卡号" name="settAccNo" value="<?php echo ($settAccNo)?>" /> 
<input type="hidden"   placeholder="结算卡持卡人姓名" name="settAccNoName" value="<?php echo ($settAccNoName)?>" /> 
<input type="hidden"   placeholder="结算卡持卡人身份证" name="idNo" value="<?php echo ($idNo)?>" /> 
<input type="hidden"   placeholder="结算卡银行预留手机号" name="settPhone" value="<?php echo ($settPhone)?>" /> 
<?php 
	$sign_array_fields = Array(
    "version",
    "merId",
    "merOrderId",
    "amount",
    "merOrderTime",
    "backUrl",
    "txnType",
	"cct",
	"accNo",
	"phone",
	"settAccNo",
	"settAccNoName",
	"idNo",
	"settPhone"
);
$sign_array = Array(

    "version" => $version,
    "merId" => $merId,
    "merOrderId" => $merOrderId,
    "amount"=>$amount ,
    "merOrderTime"=>$merOrderTime ,
    "backUrl"=>$backUrl ,
    "txnType"=>$txnType,
    "cct"=>$cct ,
    "accNo"=>$accNo ,
    "phone"=>$phone ,
    "settAccNo"=>$settAccNo ,
    "settAccNoName"=>$settAccNoName ,
    "idNo"=>$idNo,
    "settPhone"=>$settPhone
);
$md5Key = "123456ADSEF";
$sign0 = sign($sign_array_fields, $sign_array, $md5Key);

// 将小写字母转成大写字母
$sign1 = strtoupper($sign0);
function sign($sign_fields, $map, $md5_key)
{
    $sign_src = orgSignStr($sign_fields, $map, $md5_key);
    return md5($sign_src);
}
/* 构建签名原文 */
function orgSignStr($sign_fields, $map, $md5_key)
{
    // 排序-字段顺序
    sort($sign_fields);
    $sign_src = "";
    foreach ($sign_fields as $field) {
        $sign_src .= $field . "=" . $map[$field] . "&";
    }
    $sign_src .= "KEY=" . $md5_key;

    return $sign_src;
}
?>
<input type="hidden"   placeholder="签名字符串" name="sign" value="<?php echo($sign1) ?>" /> 
</form>
</body>
</html>

