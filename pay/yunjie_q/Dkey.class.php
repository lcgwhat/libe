<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/21
 * Time: 17:08
 */




use WY\app\libs\Xml;
use WY\app\libs\Http;
require_once 'Base.php';
include_once 'Des3.class.php';
/*
 * Dkey作用：得到动态密钥
 */
class Dkey extends Base {


    private $TradeType='0413';
    function index($arr){

        $des3=new Des3($this->std3key,'123456');
        $charCode=$this->charCode;
        $Version=$this->Version;
        $TradeType=$this->TradeType;
        $ChannelID=$this->ChannelID;
        $bmMerId=$this->bmMerId;
        $timeStamp=$arr['timeStamp'];
        $orderId=$arr['orderId'];
//获取签名
        $md5C=md5($charCode.$Version.$TradeType.$ChannelID.$bmMerId.$timeStamp.$orderId.$this->md5key);
        $md5ConS=strtolower($md5C);
//获取数据数组
        $order=array(
            'charCode'=>$charCode,
            'Version'=> $Version,
            'TradeType'=>$TradeType,
            'ChannelID'=>$ChannelID,
            'bmMerId'=> $bmMerId,
            'timeStamp'=>$timeStamp,
            'orderId'=>$orderId,
            'md5ConSec'=>$md5ConS
        );
        $res=new Xml();
//数据转xml
        $pay=$res->toXml($order);
        $data=$des3->encrypt($pay,$ChannelID);
        $url='http://47.100.1.24/SwitchDynamicPassword';
        $method='POST';
        $resArr=$this->curlRequest($url,$method,$data);
//截取前面20位之后，再解密
        $info=substr($resArr,20);
 //解密返回的密文
        $resinfo=$des3->decrypt($info);
        file_put_contents('test.txt',$resinfo);
 //XML转obj
        libxml_disable_entity_loader(true);
        $resf=simplexml_load_string($resinfo,'SimpleXMLElement',LIBXML_NOCDATA); //XML直接转为对象

        $dongtai=md5($this->md5key.$resf->random);
        $miya = substr($dongtai,4,-4);
        return $miya;
    }


}