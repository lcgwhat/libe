<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/23
 * Time: 15:24
 */
require_once 'inc.php';
use WY\app\model\Handleorder;
use WY\app\model\Pushorder;
$data=file_get_contents('php://input');
file_put_contents('notify.txt',$data);

libxml_disable_entity_loader(true);
$resf=simplexml_load_string($data,'SimpleXMLElement',LIBXML_NOCDATA);
$sss=json_encode($resf,JSON_UNESCAPED_UNICODE);
file_put_contents('notify88.txt',$sss);

$charCode=$resf->charCode;
$Version=$resf->Version;
$ChannelID=$resf->ChannelID;
$bmMerId=$resf->bmMerId;
$timeStamp=$resf->timeStamp;
$orderId=$resf->orderId;
$platOrderId=$resf->platOrderId;
$txnAmt=$resf->txnAmt;
$resultCode=$resf->resultCode;
$resultDesc=$resf->resultDesc;
$md5ConSec=$resf->md5ConSec;

if($resultCode=='00')
{
    $handle=@new Handleorder($orderId,$txnAmt/100);
    $handle->updateUncard();
    echo 'SUCCESS';
}
else
{
    echo 'FAIL';
}

