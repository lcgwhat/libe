<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 22:20
 */
require_once 'inc.php';
require_once 'Base1.class.php';
use WY\app\libs\Xml;
//统一下单
function tongyixiadan($arr,$resKey)
{
    $base=new Base1();
    $res=new Xml();
    $oinfo=$base->tomd5($arr);
    $pay2=$res->toXml($oinfo);
    $data2=$base->encrypt($pay2,$resKey,$oinfo['ChannelID']);
    $url2='http://47.100.1.24/UserAuth';
    $method2='POST';
    $payinfo2=$base->curlRequest($url2,$method2,$data2);
//    var_dump($payinfo2);
    $in2=substr($payinfo2,20);
    $rinfo2=$base->decrypt($in2,$resKey);
    echo '<br/>';
    file_put_contents('pay2.txt',$rinfo2);
    libxml_disable_entity_loader(true);
    $re=simplexml_load_string($rinfo2,'SimpleXMLElement',LIBXML_NOCDATA);
    $urlw=urldecode($re->qrcode);
    var_dump($urlw); echo '<br/>';
    var_dump('统一下单'.json_encode($re,JSON_UNESCAPED_UNICODE));
    echo '<br/>';
    return $urlw;

}
/*$oinfo=array(
    'charCode'=>"GBK",
    'Version'=>"2.0.1",
    'TradeType'=>"0707",
    'ChannelID'=>"6600000000000232",
    'bmMerId'=> "6600000000000232",
    'timeStamp'=>"20180322222747",
    'orderId'=>"order111111154548",
    'createIp'=>"59.61.99.66" ,
    'txnAmt'=> "50000",
    'retUrl'=> "https://www.baidu.com/",
    'merUrl'=> "https://www.hao123.com/?tn=91544714_hao_pg" ,
    'productName'=>  "iPhoneX",
    'md5key'=>'lpv3h8v8ymq19a0xchgve4esgpf1rlvx'
);*/