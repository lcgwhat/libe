<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/8
 * Time: 9:47
 */
require_once 'inc.php';
require_once 'SwiftBand.class.php';
use WY\app\libs\Std3Des;
$std=new Std3Des($STD3Key,$STD3value);
$portband=$std->decrypt($_GET['data']) ;

$resband=json_decode($portband,true);

$band=new SwiftBand();
$fin=array('settAccNo'=>$resband['cardNo'],
    'settAccNoName'=>$resband['cardName'],
    'idNo'=>$resband['idCardNo'],
    'settPhone'=>$resband['phoneNum'],
    'merOrderId'=>$resband['sdorderno']);

$reband=$band->band($fin);

$red=json_decode($reband,true);

if($red['Data']['merId'])
{
    $red['Data']['merId']=$resband['customerid'];
    $red['Data']['sign']='';
    echo json_encode($red,JSON_UNESCAPED_UNICODE);
}
else
{
    echo $reband;
}