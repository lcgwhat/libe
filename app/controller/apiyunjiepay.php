<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\libs\Xml;
use WY\app\libs\Std3Des;

if (!defined('WY_ROOT')) {
    exit;
}
/*
 *  api类：发送数据
 * */
class apiyunjiepay extends Controller
{

    public function index(){

        $charCode= 'GBK'; //参数编k码方式，暂时只支持GBK。
        $Version= '2.0.1'; //接口版本号（2.0.1）
        $TradeType='0413'; //交易类型 请参考6.3业务类型码
        $ChannelID= '6600000000000232'; //渠道号（同商户编号）
        $bmMerId= '6600000000000232';  //商户编号
        $timeStamp= date('YmdHms',time()); //时间戳，当前接口调用时间，具体格式: yyyyMMddHHmmss
        $orderId= 'order111111154545';  //商户订单号，商户系统唯一

        $createIp= '59.61.99.66'; //用户的ip

        $txnAmt= '50000';  //交易金额（单位：分）

        $retUrl= 'https://www.baidu.com/';  //异步通知地址
        $merUrl= 'https://www.hao123.com/?tn=91544714_hao_pg';  //页面通知，支付成功后跳转到该地址，参数详见页面通知
        $transCurrency= '156'; //交易币种，固定：156

        $cardByName= '郑辉';  //持卡人姓名
        $cardByNo= '622909111000656015';   //持卡卡号
        $cardType= '01';  //卡类型    00 贷记卡    01 借记卡   02 准贷记卡

//中间签名非需要
        $expireDate= $_REQUEST['version'];
        $CVV= $_REQUEST['version'];
        $bankCode= $_REQUEST['version'];
        $openBankName= $_REQUEST['version'];
        $cerType= $_REQUEST['version'];
//

        $cerNumber= '350104198902044916'; //证件号码
        $mobile= '13675004392';  //手机号
        $productName= 'iPhoneX';  //商品名称
        $md5key='lpv3h8v8ymq19a0xchgve4esgpf1rlvx';

//后面签名非需要
        $productDesc= $_REQUEST['version'];
        $rcvName= $_REQUEST['version'];
        $rcvMobile= $_REQUEST['version'];
        $rcvAdress= $_REQUEST['version'];
        $fileId1= $_REQUEST['version'];
        $fileId1= $_REQUEST['version'];
        $fileId1= $_REQUEST['version'];
//
        $md5Con=md5($charCode.$Version.$TradeType.$ChannelID.$bmMerId.$timeStamp.$orderId.$createIp.$txnAmt.$retUrl.$merUrl.$transCurrency.$cardByName.$cardByNo.$cardType.$cerNumber.$mobile.$productName.$md5key);
        $md5ConSec=strtolower($md5Con);

        $orderinfo=array(
            'charCode'=>$charCode,
            'Version'=> $Version,
            'TradeType'=>$TradeType,
            'ChannelID'=>$ChannelID,
            'bmMerId'=> $bmMerId,
            'timeStamp'=>$timeStamp,
            'orderId'=>$orderId,
            'createIp'=>$createIp,
            'txnAmt'=> $txnAmt,
            'retUrl'=> $retUrl,
            'merUrl'=> $merUrl,
            'transCurrency'=> $transCurrency,
            'cardByName'=> $cardByName,
            'cardByNo'=> $cardByNo,
            'cardType'=> $cardType,
            'cerNumber'=> $cerNumber,
            'mobile'=> $mobile,
            'productName'=> $productName,
            'md5ConSec'=>$md5ConSec
        );
       // var_dump($orderinfo);

        $md5C=md5($charCode.$Version.$TradeType.$ChannelID.$bmMerId.$timeStamp.$orderId.$md5key);

        $md5ConS=strtolower($md5C);

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
        $pay=$res->toXml($order);
        $data=$this->encrypt($pay,'68b2dc377jlt0vewl4u9g4nc',$ChannelID);
        $url='http://47.100.1.24/SwitchDynamicPassword';
        $method='POST';
        $resArr=$this->curlRequest($url,$method,$data);
        //var_dump($resArr);
        $info=substr($resArr,20);

        //var_dump($info);
        $resinfo=$this->decrypt($info,'68b2dc377jlt0vewl4u9g4nc');
        //var_dump($resinfo);
        libxml_disable_entity_loader(true);
        $resf=simplexml_load_string($resinfo,'SimpleXMLElement',LIBXML_NOCDATA); //XML直接转为对象
        var_dump($resf->random);  //用箭头获取属性值



//        $resXml=$res->parseXml($resinfo);
//        $arr=$res->getXmlEncode($resinfo);
//        var_dump($arr);

        //var_dump(file_put_contents('test.txt',$resinfo));

//        $res=new Xml();
//        var_dump($res);
//        $pay=$res->toXml($orderinfo);
//        $data=$des->encrypt($pay);
//
//        //file_put_contents('txt.txt',$pay);
//        $url='http://47.100.1.24/UserAuth';
//        $method='POST';
//        var_dump($this->curlRequest($url,$method,$data));


    }

    public function encrypt ($input,$key,$ChannelID){
        $size = mcrypt_get_block_size(MCRYPT_3DES,'ecb');
        $input = $this->pkcs5_pad($input,$size);
        $td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
        @mcrypt_generic_init($td,$key,$iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data=sprintf("%04d",strlen($data)+16).$ChannelID.base64_encode($data);
        return $data;
    }


    public function decrypt($encrypted,$key){
        $encrypted = base64_decode($encrypted); //如需转换二进制可改成  bin2hex 转换

        $td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');//3DES加密将MCRYPT_DES改为MCRYPT_3DES

        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $y=$this->pkcs5_unpad($decrypted);
        return $y;
    }

    public function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public function pkcs5_unpad($text){
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }



    /*curl函数请求数据*/
    public function curlRequest($url,$method,$data){
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$method);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        $returnData=curl_exec($curl);
        curl_close($curl);
        return $returnData;
    }
}
?>

