<?php
use WY\app\libs\Http;
use WY\app\libs\Controller;

class Swiftpay extends Controller
{

    public $version = '1.0';
    public $userid = '2000007996';  //商户号
    //public $userkey = '';
    public $userOrderNum = '';
    public $md5Key = "44d3ebcf-882d-48f7-9449-e986c8441360"; //md5key 具体值由商户入网时生成
    const MERID = '2000007996';
    public $gateUrl = 'http://api.hzlcpay.com/pay/orderQuery.do'; //调借地址

    


    function sign($sign_fields, $map, $md5_key)
    {
        $sign_src = $this->orgSignStr($sign_fields, $map, $md5_key);
        return md5($sign_src);
    }

    /* 构建签名原文 */
    function orgSignStr($sign_fields, $map, $md5_key)
    {
        // 排序-字段顺序
        sort($sign_fields);
        $sign_src = "";
        foreach ($sign_fields as $field) {
            $sign_src .= $field . "=" . $map[$field] . "&";
        }
        $sign_src .= "KEY=" . $md5_key;

        return $sign_src;
    }

    function notify($res)
    {
	   $data=file_get_contents('php://input');
	    file_put_contents('note.txt',$data);
        file_put_contents('ress.txt',json_encode($res));
        $merchantCode = $res['merchantCode'];
        $instructCode = $res['instructCode'];
        $transType = $res['transType'];
        $outOrderId = $res['outOrderId'];
        $transTime = $res['transTime'];
        $totalAmount = $res['totalAmount'];
        $resSign = $res['sign'];//返回签名
        $sign_array_fields = Array(
            "merchantCode",
            "instructCode",
            "transType",
            "outOrderId",
            "transTime",
            "totalAmount",
        );
        $sign_array = Array(
            "merchantCode"=>$merchantCode,
            "instructCode"=>$instructCode,
            "transType"=>$transType,
            "outOrderId"=> $outOrderId,
            "transTime"=>$transTime,
            "totalAmount"=>$totalAmount
        );
        $md5Key =$this->md5Key;
        $sign0 = $this->sign($sign_array_fields, $sign_array, $md5Key);
      
        // 将小写字母转成大写字母

        $sign1 = strtoupper($sign0);
        if($resSign!=$sign1) //签名验证
        {
            exit;
        }
        return array('orderid'=>$outOrderId,'total_fee'=>$totalAmount);
    }


}
