<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 11:11
 */
/*交易申请*/
require_once 'Base.php';
require_once 'Des3.class.php';
use WY\app\libs\Xml;

class Payment extends Base
{
    private $TradeType='0707';
    public function index($arr,$des3Key){
//签名
            $md5Con=md5($this->charCode.$this->Version.$this->TradeType.$this->ChannelID.$this->bmMerId.$arr['timeStamp'].$arr['orderId'].$arr['createIp'].$arr['txnAmt'].$arr['retUrl'].$arr['merUrl'].$arr['transCurrency'].$arr['cardByName'].$arr['cardByNo'].$arr['cardType'].$arr['cerNumber'].$arr['mobile'].$arr['productName'].$this->md5key);
        $md5ConSec=strtolower($md5Con);

        $orderinfo=array(
            'charCode'=>$this->charCode,
            'Version'=> $this->Version,
            'TradeType'=>$this->TradeType,
            'ChannelID'=>$this->ChannelID,
            'bmMerId'=> $this->bmMerId,
            'timeStamp'=>$arr['timeStamp'],
            'orderId'=>$arr['orderId'],
            'createIp'=>$arr['createIp'],
            'txnAmt'=> $arr['txnAmt'],
            'retUrl'=> $arr['retUrl'],
            'merUrl'=> $arr['merUrl'],
            'transCurrency'=> $arr['transCurrency'],
            'cardByName'=> $arr['cardByName'],
            'cardByNo'=>$arr['cardByNo'],
            'cardType'=>$arr['cardType'],
            'cerNumber'=> $arr['cerNumber'],
            'mobile'=>$arr['mobile'],
            'productName'=> $arr['productName'],
            'md5ConSec'=>$md5ConSec
        );
        $res=new Xml();
        $des3=new Des3($des3Key,'123456');
        $pay1=$res->toXml( $orderinfo);
        $data1=$des3->encrypt($pay1,$this->ChannelID);
        $url1='http://47.100.1.24/UserAuth';
        $method1='POST';
        $payinfo=$this->curlRequest($url1,$method1,$data1);

        echo "<br/>";
        var_dump($payinfo);
        $in=substr($payinfo,20);
        $rinfo=$des3->decrypt($in);
        var_dump(file_put_contents('pay12.txt','支付生气'.$rinfo));
        libxml_disable_entity_loader(true);
        $re=simplexml_load_string($rinfo,'SimpleXMLElement',LIBXML_NOCDATA); //XML直接转为对象
        var_dump($re);
    }
}