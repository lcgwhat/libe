<?php
/**
 * Created by LIU.
 * User: Administrator
 * Date: 2018/3/12
 * Time: 10:26
 */

require_once 'inc.php';
require_once 'Swiftpay.class.php';
use WY\app\model\Handleorder;

$pay=new Swiftpay();

$merchantCode=isset( $_POST['merchantCode'])?$_POST['merchantCode']:'';
$instructCode=isset($_POST['instructCode'])?$_POST['instructCode']:'';
$transType=isset($_POST['transType'])?$_POST['transType']:'';
$outOrderId=isset($_POST['outOrderId'])?$_POST['outOrderId']:'';
$transTime=isset($_POST['transTime'])?$_POST['transTime']:'';
$totalAmount=isset($_POST['totalAmount'])?$_POST['totalAmount']:'';
$sign=isset($_POST['sign'])?$_POST['sign']:'';
$fin=array('merchantCode'=>$merchantCode,
    'instructCode'=>$instructCode,
    'transType'=>$transType,
    'outOrderId'=>$outOrderId,
    'transTime'=>$transTime,
    'totalAmount'=>$totalAmount,
    'sign'=>$sign);
var_dump(json_encode($fin));
//$ret=array('orderid'=>$outOrderId,'total_fee'=>$totalAmount);
if($ret=$pay->notify($fin)){
    $ret=array('code'=>'00');
    echo json_encode($ret);
    $handle=new Handleorder($ret['orderid'],$ret['total_fee']/100);
    $handle->updateUncard();
} else {
    echo 'fail';
    //var_dump($ret['orderid']);
}

