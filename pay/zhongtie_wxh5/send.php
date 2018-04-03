<?php
require_once 'inc.php';
require_once 'Swiftpay.class.php';
use WY\app\libs\Std3Des;

$des3Util = new Std3Des("68b2dc377jlt0vewl4u9g4nc","12345678");
$submitdata = $_GET['submitdata'];
$dataStr = $des3Util->decrypt($submitdata);
$dataArr = json_decode($dataStr,true);

$account_no = $userid;  //商户号
$out_trade_no = $dataArr['sdorderno'];  //商户订单号
$order_name = $dataArr['goodname']; //商品描述
$goods_tag = $dataArr['goodtag'];  //商品标记
$radNum=rand(0.1,1)/10;
$total_amount =(string)($dataArr['total_fee']-$radNum);  //总金额
$spbill_create_ip = $dataArr['userip'] ;  //APP和网页支付提交用户端ip
$notify_url = 'http://jhzf.ilibei.com/pay/zhongtie_wxh5/notifyUrl.php'; //回调地址


?>
<!doctype html>
<html>
<head>
    <meta charset="utf8">
    <title>正在转到付款页</title>
</head>
<body onLoad="document.pay.submit()">
<form name="pay" action="http://net.zt717.com/core_system/Api/PingAnH5Pay/WechatH5" method="post">
    <input type="hidden" name="account_no" value="<?php echo $account_no?>">
    <input type="hidden" name="out_trade_no" value="<?php echo $out_trade_no?>">
    <input type="hidden" name="order_name" value="<?php echo $order_name?>">
    <input type="hidden" name="goods_tag" value="<?php echo $goods_tag?>">
    <input type="hidden" name="total_amount" value="<?php echo $total_amount?>">
    <input type="hidden" name="spbill_create_ip" value="<?php echo $spbill_create_ip?>">
    <input type="hidden" name="notify_url" value="<?php echo $notify_url?>">

    <?php
    /*
        签名方式(signature)：对请求的参数名进行按字母排序，
        将分配的key （明文） 和参数的值 （明文） 进行拼接后进行MD5加密得到签名结果。
    */
    $sign_array_fields = Array(
        "account_no",
        "out_trade_no",
        "order_name",
        "goods_tag",
        "total_amount",
        "spbill_create_ip",
        "notify_url"
    );

    $sign_array = Array(
        "account_no" => $account_no,
        "out_trade_no" => $out_trade_no,
        "order_name" => $order_name,
        "goods_tag" => $goods_tag,
        "total_amount" => $total_amount,
        "spbill_create_ip" => $spbill_create_ip,
        "notify_url" => $notify_url
    );

    $signature = sign($sign_array_fields, $sign_array, $userkey); //生成签名

    /* 构建签名原文 */
    function orgSignStr($sign_fields, $map, $md5_key)
    {
        // 排序-字段顺序
        sort($sign_fields);
        $sign_src = '';
        foreach ($sign_fields as $field) {
            $sign_src .=$field.'='.$map[$field].'&';
        }
        $sign_src=substr($sign_src,0,-1); //去掉末尾的&
        $sign_src .= $md5_key;
        return $sign_src;
    }

    /**
     * 计算md5签名 返回的是小写的，后面需转大写
     */
    function sign($sign_fields, $map, $md5_key)
    {
        $sign_src = orgSignStr($sign_fields, $map, $md5_key);
        return md5($sign_src);
    }
    ?>
    <input type="hidden" name="signature" value="<?php echo $signature?>">
</form>
</body>
</html>

