<?php
require_once 'inc.php';
require_once 'SwiftBand.class.php';
use WY\app\libs\Std3Des;
$STD=new Std3Des($STD3Key,$STD3value);
$orderid=$_GET['orderid'];
$price=$_GET['price']*100;
$remark=$_GET['remark'];
$data=$_GET['msg'];
$productName=$_GET['productName'];
$productDesc=$_GET['productDesc'];
$returnUrl=$_GET['returnUrl'];
$data2=$STD->decrypt($data);
$data3=json_decode($data2,true);

$now=date('YmdHis',time());
  //yyyyMMddHHmmss  2018 0313 144745  2017 0101 111122
$orderarr=array('merOrderId'=>$orderid);
$fin=array_merge($orderarr,$data3);
$band=new SwiftBand();

$reband=$band->band($fin);
$dataR=json_decode($reband,true);
//var_dump($reband);exit;
$version='1.0';

$merOrderId="$orderid";
$amount=$price;

$merOrderTime=$now;
$fontUrl=$returnUrl;//同步回调地址
$backUrl='http://'.$_SERVER['HTTP_HOST'].'/pay/swift_tongming/notifyUrl.php';//异步回调地址
$txnType=$data3['txnType'];
$cct=$data3['cct'];

$accNo=$STD->encrypt($data3['accNo']);//支付卡
$phone=$STD->encrypt($data3['phone']);//支付卡银行预留手机号
$settAccNo=$STD->encrypt($data3['settAccNo']);//结算银行卡
$settAccNoName=$STD->encrypt($data3['settAccNoName']);
$idNo=$STD->encrypt($data3['idNo']);//结算卡持卡人身份证
$settPhone=$STD->encrypt($data3['settPhone']);//结算卡银行预留手机号
$orderUrl="http://vapi.hzlcpay.com/quickpay/tmplaceOrder.servlet";
?>

<!doctype html>
<html>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<title>支付跳转中</title>
<head></head>
<body onLoad="document.quickPay.submit()">
<form action="<?php echo ($orderUrl) ?>" name="quickPay"	method="post">
    <input type="hidden"   placeholder="版本号" 	name="version" value="<?php echo($version) ?>" />
    <input type="hidden"   placeholder="商户号" 	name="merId" value="<?php echo($merId) ?>" />
    <input type="hidden"   placeholder="商户订单号"	name="merOrderId" value="<?php echo ($merOrderId)?>" />
    <input type="hidden"   placeholder="商户用户编号" name="merUserId" value="<?php echo ($merUserId)?>" />
    <input type="hidden"   placeholder="支付金额(单位:分)"  name="amount" value="<?php echo ($amount )?>" />
    <input type="hidden"   placeholder="商品名称" name="productName" value="<?php echo ($productName)?>" />
    <input type="hidden"   placeholder="商品描述" name="productDesc" value="<?php echo ($productDesc )?>">
    <input type="hidden"   placeholder="商户订单时间" name="merOrderTime" value="<?php echo ($merOrderTime)?>" />
    <input type="hidden"   placeholder="最晚支付时间"	name="payTimeOut" value="<?php echo ($now)?>" />
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

    $md5Key = $Md5Key;
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



