<?php
use WY\app\libs\Http;
class wyapi{
    public $debug=false;
    private $ver='v1.0';
    public $userid;
    public $userkey;
    public $gateUrl='http://pay.zhongweipay.net/api/core.php';

    public function submitOrder($data){
        $data+=array(
            'account_no'=>$this->userid,
            'method'=>'00000004',
            'version'=>$this->ver,
            'productId'=>'01',
            'nonce_str'=>mt_rand(time(),time()+mt_rand(10000,99999)),
            'pay_tool'=>'wxsmyj',
            'ex_field'=>'',
        );
        $data['signature']=$this->makeSign($data);
        $this->log('submitOrder',$data);

        $http=new Http($this->gateUrl,$data);
        $http->toUrl();
        $ret=json_decode($http->getResContent(),1);
        $this->log('submitOrderResult',$ret);

        if($ret['res_code']=='P000'){
            if($ret['signature']==$this->makeSign($ret)){
                return array('status'=>1,'url'=>$ret['codeUrl']);
            } else {
                return array('status'=>0,'msg'=>'验签失败');
            }
        }
        return array('status'=>0,'msg'=>$ret['res_code'].'|'.$ret['res_msg']);
    }

    public function notifyOrder(){
        $ret=isset($_POST) ? $_POST : '';
        $this->log('notifyOrder',$ret);

        if($ret['res_code']=='0000' && $ret['status']=='1' &&  $ret['signature']==$this->makeSign($ret)){
            $this->log('notifyOrder002','ok');
            return array(
                'orderid'=>$ret['order_sn'],
                'money'=>$ret['money'],
            );
        }
        return false;
    }

    public function queryOrder($orderid){
        $data=array(
            'version'=>$this->ver,
            'method'=>'00000003',
            'productId'=>'10',
            'account_no'=>$this->userid,
            'nonce_str'=>mt_rand(time(),time()+mt_rand(10000,99999)),
            'order_sn'=>$orderid,
            'order_type'=>'1',
            'ex_field'=>'',
        );
        $data['signature']=$this->makeSign($data);
        $this->log('queryOrder',$data);

        $http=new Http($this->gateUrl,$data);
        $http->toUrl();
        $ret=json_decode($http->getResContent(),1);
        $this->log('orderQueryResult',$ret);

        if($ret['res_code']=='0000' && $ret['status']=='4' && $ret['signature']==$this->makeSign($ret)){
            return array(
                'orderid'=>$ret['order_sn'],
                //'money'=>$ret['money'],
            );
        }
        return false;

    }

    public function makeSign($data){
        ksort($data);
        $str='';
        foreach($data as $key=>$val){
            if($key!='signature' && $val!==''){
                $str.=$str ? '&' : '';
                $str.=$key.'='.$val;
            }
        }
        $str.=$this->userkey;
        $sign=md5($str);
        $this->log('makeSign',array('sign'=>$sign,'str'=>$str));

        return $sign;
    }

    public function log($title,$data){
        if(!$this->debug) return false;
        $handler = @fopen(date('ymd').'.txt','a+');
        $content = "================".$title."===================\n";
        if(is_string($data) === true){
            $content .= $data."\n";
        }
        if(is_array($data) === true){
            $i=0;
            foreach($data as $k=>$v){
                $i+=1;
                $i2=$i<10 ? '0'.$i : $i;
                $content .= $i2.": ".$k." = ".$v."\n";
            }
        }
        $flag = @fwrite($handler,$content);
        @fclose($handler);
    }
}
?>
