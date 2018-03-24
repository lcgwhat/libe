<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/22
 * Time: 11:17
 */
use WY\app\libs\Xml;
require_once 'Des3.class.php';
class Base{
    public $charCode = 'GBK';   //编码方式
    public $Version = '2.0.1';   //版本号
    public $transCurrency = '156'; //交易币种
    public $ChannelID= '6600000000000232'; //渠道号（同商户编号）
    public $bmMerId= '6600000000000232';  //商户编号
    public $md5key='lpv3h8v8ymq19a0xchgve4esgpf1rlvx';  //MD5 key
    public $std3key='68b2dc377jlt0vewl4u9g4nc';   //std3 key

    protected function curlRequest($url,$method,$data){
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