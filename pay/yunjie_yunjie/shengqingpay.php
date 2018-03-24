<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 22:41
 */
require_once 'inc.php';
require_once 'Base1.class.php';
use WY\app\libs\Xml;
//申请支付
function shengqingpay($arr,$resKey)
{
    $base=new Base1();
    $res=new Xml();
    $orderinfo=$base->tomd5($arr);
    $pay1=$res->toXml($orderinfo);
    $data1=$base->encrypt($pay1,$resKey,$orderinfo['ChannelID']);
    $url1='http://47.100.1.24/UserAuth';
    $method1='POST';
    $payinfo=$base->curlRequest($url1,$method1,$data1);
    //var_dump($payinfo);
    $in=substr($payinfo,20);
    $rinfo=$base->decrypt($in,$resKey);
    //var_dump(file_put_contents('pay.txt',$rinfo));
    file_put_contents('pay.txt',$rinfo);
    libxml_disable_entity_loader(true);
    $re=simplexml_load_string($rinfo,'SimpleXMLElement',LIBXML_NOCDATA); //XML直接转为对象
//    echo '<br/>';
//    var_dump('申请支付'.json_encode($re,JSON_UNESCAPED_UNICODE));echo '<br/>';
    if($re->resultCode!='00')
    {
        return false;
    }
    return true;
}