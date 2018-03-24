<?php
use WY\app\libs\Http;
use WY\app\libs\Xml;
class Swiftpay{
    private $defaultData=array(
        'vsrsion'=>'2.0',
        'charset'=>'UTF-8',
        'sign_type'=>'MD5',
    );
    public $userid;
    public $userkey;
    public $gateUrl='https://pay.swiftpass.cn/pay/gateway';

    function __construct(){
        $this->xml=new Xml();
    }

    public function submitOrder($data){
        $data['service']='pay.alipay.native';
        $data['mch_id']=$this->userid;
        $data['time_start']='';
        $data['time_expire']='';
        $data['op_user_id']=$this->userid;
        $data['goods_tag']='';
        $data['product_id']='';
        $data['nonce_str']=mt_rand(time(),time()+mt_rand(10000,99999));
        $data['mch_create_ip']=$_SERVER['REMOTE_ADDR'];
        $data+=$this->defaultData;
        $data['sign']=$this->makeSign($data);

        $xml=$this->xml->toXml($data);

        $http=new Http($this->gateUrl,$xml);
        $http->toUrl();
        $resultXml=$http->getResContent();
        $ret=$this->xml->parseXml($resultXml);
        if($ret['status']=='0' && $ret['result_code']=='0'){
            return $ret['code_img_url'];
        }
        //self::write($data['out_trade_no'],json_encode($ret));
        return false;
    }

    public function notify(){
        $data=file_get_contents('php://input');
        $ret=$this->xml->parseXml($data);
        //self::write('notify',json_encode($ret));
        if($ret['status']=='0' && $ret['result_code']=='0'){
            if($ret['sign']==$this->makeSign($ret)){
                if($ret['pay_result']=='0'){
                    return array(
                        'orderid'=>$ret['out_trade_no'],
                        'total_fee'=>$ret['total_fee'],
                    );
                }
            }
        }
        return false;
    }

    public function queryOrder($data){
        $data['service']='unified.trade.query';
        $data['mch_id']=$this->userid;
        $data['nonce_str']=mt_rand(time(),time()+mt_rand(10000,99999));
        $data+=$this->defaultData;
        $data['sign']=$this->makeSign($data);

        $url='https://pay.swiftpass.cn/pay/gateway';

        $xml=$this->xml->toXml($data);
        $http=new Http($url,$xml);
        $http->toUrl();
        $resultXml=$http->getResContent();
        $ret=$this->xml->parseXml($resultXml);
        //self::write('queryOrder['.$data['out_trade_no'].']',json_encode($ret));

        if($ret['status']=='0'  && $ret['result_code']=='0'){
            if($ret['sign']==$this->makeSign($ret) && $ret['trade_state']=='SUCCESS'){
                return array(
                    'orderid'=>$ret['out_trade_no'],
                    'total_fee'=>$ret['total_fee'],
                );
            }
        }
        return false;

    }

    public function makeSign($data){
        ksort($data);
        $signstr='';
        foreach($data as $key=>$val){
            if($key!='sign' && $val!=''){
                $signstr.=$signstr ? '&' : '';
                $signstr.=$key.'='.$val;
            }
        }
        $sign=strtoupper(md5($signstr.'&key='.$this->userkey));
        //self::write('sign',json_encode(array('sign'=>$sign,'str'=>$signstr.'&key='.config::$userkey)));

        return $sign;
    }

    static function write($orderid,$message){
        $title=$orderid."\r\n";
        $filename='log_'.date('Y').date('m').date('d').'.txt';
        if(!file_exists($filename)) @touch($filename);
        $fp=@fopen($filename,'ab');
        @fwrite($fp,$title.$message."\r\n\r\n");
        @fclose($fp);
    }
}
?>
