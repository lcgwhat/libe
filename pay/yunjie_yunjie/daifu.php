<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 14:17
 */
require_once 'inc.php';
require_once 'Dfu.php';
require_once 'dongtaimiyao.php';

$charCode= 'GBK'; //������k�뷽ʽ����ʱֻ֧��GBK��
$Version= '2.0.1'; //�ӿڰ汾�ţ�2.0.1��
$orderId='ad5min12q38p597999';
$TradeType='0413'; //�������� ��ο�6.3ҵ��������
$timeStamp=date('YmdHis',time());

$createIp ='106.14.214.55'; //�û���ip

$txnAmt ='10';  //���׽���λ���֣�
$transCurrency = '156'; //���ױ��֣��̶���156

$cardByName = '������';  //�ֿ�������
$cardByNo ='';   //�ֿ�����
$cardType = '01';  //������    00 ���ǿ�    01 ��ǿ�   02 ׼���ǿ�
$retUrl = 'http://106.14.214.55/pay/yunjie_yunjie/notify.php';  //�첽֪ͨ��ַ
$merUrl = 'http://106.14.214.55/pay/yunjie_yunjie/tongbuno.php';  //ҳ��֪ͨ��֧���ɹ�����ת��

$cerNumber= '350425199408140315'; //֤������
$mobile='18450087519';  //�ֻ���
$productName= '�ɿڿ���';  //��Ʒ����

$order=array(
    'charCode'=>$charCode,
    'Version'=> $Version,
    'TradeType'=>$TradeType,
    'ChannelID'=>$ChannelID,
    'bmMerId'=> $bmMerId,
    'timeStamp'=>$timeStamp,
    'orderId'=>$orderId,
    'md5key'=>$md5key
);

$resKeyarr=dongtaikey($order);
if($resKeyarr['0']!='00')
{
    echo array('resultCode'=>'01','resultDesc'=>$resKeyarr['1']);
    exit;
}
$resKey=$resKeyarr['1'];

$pay=array(
    'charCode'=>'GBK',
    'Version'=>'2.0.1',
    'TradeType'=>'0628',
    'ChannelID'=>$ChannelID,
    'bmMerId'=>$bmMerId,
    'timeStamp'=>date('YmdHis',time()),
    'orderId'=>$orderId,
    'txnAmt'=>'10',
    'acctType'=>'1',
    'acctName'=>'������',
    'acctNo'=>'6214855914319856',
    'bankSettNo'=>'308391026069',
    'retUrl'=>'106.14.214.55/pay/yunjie_yunjie/dfnotify.php',
    'md5key'=>$md5key
);
//����������
$daifucaoz=dfu($pay,$resKey);
echo $daifucaoz;echo '<br/>';
$qurOr=array(
    'charCode'=>'GBK',
    'Version'=>'2.0.1',
    'TradeType'=>'0630',
    'ChannelID'=>$ChannelID,
    'bmMerId'=>$bmMerId,
    'timeStamp'=>date('YmdHis',time()),
    'orderId'=>$orderId,
    'md5key'=>$md5key
);
//ȷ�ϴ�������
$querencaoz=dfu($qurOr,$resKey);
echo $querencaoz;echo '<br/>';

$orderinfo=array(
    'charCode'=>$charCode,
    'Version'=> $Version,
    'TradeType'=>'0741',
    'ChannelID'=>$ChannelID,
    'bmMerId'=> $bmMerId,
    'timeStamp'=>$timeStamp,
    'orderId'=>$orderId,
    'createIp'=>$createIp,
    'txnAmt'=> $txnAmt,
    'retUrl'=> $retUrl,
    'merUrl'=> $merUrl,
    'transCurrency'=> $transCurrency,
    'cardByName'=> $cardByName,
    'cardByNo'=> $cardByNo,
    'cardType'=> $cardType,
    'cerNumber'=> $cerNumber,
    'mobile'=> $mobile,
    'productName'=> $productName,
    'md5key'=>$md5key
);
//���֧������
//$kuaijieshengqi=dfu($orderinfo,$resKey);
//echo $kuaijieshengqi;