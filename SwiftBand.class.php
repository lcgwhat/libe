<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/8
 * Time: 10:09
 */
use WY\app\libs\Http;
use WY\app\libs\Std3Des;
class SwiftBand
{
    const MERID = "2000007851";

    public $gateUrl='http://api.hzlcpay.com/pay/tiedCard.do'; //调借地址

    function band($data)
    {
        $des3Util=new Std3Des("4C012206573169BF8E9F36BCF5D4125E","12345678");
        $cardNo=$des3Util->encrypt($data['accNo']);//卡号
        $cardName=$des3Util->encrypt($data['settAccNoName']);//姓名
        $idCardNo=$des3Util->encrypt($data['idNo']);//身份证号
        $phoneNum=$des3Util->encrypt($data['settPhone']);//手机号
//        var_dump($data);exit;
        $sign_array_fields = Array(
            "merId",
            "merOrderId",
            "cardNo",
            "cardName",
            "idCardNo",
            "phoneNum",
            "version"
        );
        $sign_array = Array(
            "merId" =>self::MERID,
            "merOrderId" =>  $data['merOrderId'],
            "cardNo" =>  $cardNo,
            "cardName" =>  $cardName,
            "idCardNo" =>  $idCardNo,
            "phoneNum" =>  $phoneNum,
            "version" =>  '1.0'
        );
        $md5Key = "123456ADSEF";
        $sign0 = $this->sign($sign_array_fields, $sign_array, $md5Key);
// 将小写字母转成大写字母
        $sign1 = strtoupper($sign0);
        $post_data1 = array(
            "merId" => self::MERID,
            "merOrderId" => $data['merOrderId'],
            "cardNo" =>$cardNo,
            "cardName" =>  $cardName,
            "idCardNo" =>  $idCardNo,
            "phoneNum" =>  $phoneNum,
            "version" =>  '1.0',
            "sign" => $sign1
        );

        $http=new Http($this->gateUrl,$post_data1);
        $http->toUrl();
        $resultXml=$http->getResContent();
        return $resultXml;
    }
    function sign($sign_fields, $map, $md5_key)
    {
        $sign_src =$this->orgSignStr($sign_fields, $map, $md5_key);
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
}