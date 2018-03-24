<!--下单-->
<?php
use WY\app\libs\Xml;
require_once 'Des3.class.php';
 class Order extends Base {
     private $TradeType='0707';
    function index($arr,$resKey)
    {
//统一下单签名
        $md5Con=md5($this->charCode.$this->Version.$this->TradeType.$this->ChannelID.$this->bmMerId.$arr['timeStamp'].$arr['orderId'].$arr['createIp'].$arr['txnAmt'].$arr['retUrl'].$arr['merUrl'].$arr['productName'].$this->md5key);

        $md5ConSec=strtolower($md5Con);
  //统一下单接口
        $oinfo2=array(
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
            'productName'=>$arr['productName'],
            'md5ConSec'=>$md5ConSec
        );

        $des3=new Des3($resKey,'123456');
        $url='http://47.100.1.24/UserAuth';
        $res=new Xml();
        $pay2=$res->toXml($oinfo2);
        $data2=$des3->encrypt($pay2,$this->ChannelID);
        $method2='POST';
        $payinfo2=$this->curlRequest( $url,$method2,$data2);
        var_dump($resKey);
        echo "<br/>";
        var_dump($payinfo2);
        var_dump('密钥'.$resKey);
        $in2=substr($payinfo2,20);
        $rinfo2=$des3->decrypt($in2);
        var_dump(file_put_contents('pay245.txt',$rinfo2));
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


 }
?>