<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/12
 * Time: 15:29
 */


$tt=array("merchantCode"=>"2000007851",
    "instructCode"=>"2018030400000579949",
    "transType"=>"00200",
    "outOrderId"=> "quHDsTzYkgtRcjYA1002",
    "transTime"=>"20180304105013",
    'totalAmount'=>'850050',
    "sign"=>"256EFEC674522E16BC69269626DD1E9D",
);


$url='http://106.14.214.55/pay/swift_tongming/notifyUrl.php';
$url4='http://localhost/pay/swift_tongming/notifyUrl.php';
$url2='https://www.yyrain.com/pay/tomipay_toming/notifyUrl.php';
$method="POST";
$rd=curl($tt,$url,$method);
var_dump($rd);
function curl($data,$url,$method)
{
    $curl=curl_init(); //$.ajax()
    curl_setopt($curl,CURLOPT_URL,$url);//$.ajax的url参数
    curl_setopt($curl,CURLOPT_POSTFIELDS,$data);   //$.ajax的data参数
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$method);//$.ajax的type参数
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,true); //将服务器返回的数据原样输出
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
    $returnData=curl_exec($curl); //执行
    curl_close($curl);
    return $returnData;

}
?>
