<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 21:34
 */
require_once 'inc.php';
require_once 'Base1.class.php';
use WY\app\libs\Xml;
function dongtaikey($arr)
{
    $tomd5=new Base1();
    $base=new Base1();
    $res=new Xml();
    $order=$tomd5->tomd5($arr);
    $pay=$res->toXml($order);
    $data=$base->encrypt($pay,'68b2dc377jlt0vewl4u9g4nc',$order['ChannelID']);
    $url='http://47.100.1.24/SwitchDynamicPassword';
    $method='POST';
    $resArr=$base->curlRequest($url,$method,$data);

    $info=substr($resArr,20);  //截取前面20为再解密

    $resinfo=$base->decrypt($info,'68b2dc377jlt0vewl4u9g4nc');
    file_put_contents('dongtainiyao.txt',$resinfo);
    libxml_disable_entity_loader(true);
    $resf=simplexml_load_string($resinfo,'SimpleXMLElement',LIBXML_NOCDATA); //XML直接转为对象
//var_dump($resf->random);  //用箭头获取属性值
    if($resf->resultCode!='00')
    {
        return array('0'=>$resf->resultCode,'1'=>$resf->resultCode);
    }
    $key=md5('68b2dc377jlt0vewl4u9g4nc'.$resf->random);  //md5(3des秘钥+随机数)=32位
//var_dump($key);
    $resKey=substr($key,4,-4);  //去掉前4位和后4位得到中间24位则为动态秘钥

    echo '<br/>';
    return  array('0'=>$resf->resultCode,'1'=>$resKey);
}

/*$order=array(
    'charCode'=>'GBK',
    'Version'=> "2.0.1",
    'TradeType'=>"0413",
    'ChannelID'=>"6600000000000232",
    'bmMerId'=> "6600000000000232",
    'timeStamp'=>"20180322220247",
    'orderId'=>"order111111154545",
    'md5key'=>'lpv3h8v8ymq19a0xchgve4esgpf1rlvx'
);
dongtaikey($order);*/

