<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/26
 * Time: 14:17
 */
require_once 'inc.php';
require_once 'Base1.class.php';
require_once 'dongtaimiyao.php';
use WY\app\libs\Std3Des;
$des3Util=new STD3Des("68b2dc377jlt0vewl4u9g4nc","12345678");
$base=new Base1();
$charCode= 'GBK'; //������k�뷽ʽ����ʱֻ֧��GBK��
$Version= '2.0.1'; //�ӿڰ汾�ţ�2.0.1��
$orderId='admin54455654';
$TradeType='0413'; //�������� ��ο�6.3ҵ��������
$timeStamp=date('YmdHis',time());

$submitdata=isset($_GET['submitdata'])?$_GET['submitdata']:'';
$STD3=new Std3Des("68b2dc377jlt0vewl4u9g4nc","12345678");
$data=$STD3->decrypt($submitdata);
$data=json_decode($data,true);




//$createIp ='106.14.214.55'; //�û���ip

$txnAmt =$data['txnAmt'];  //���׽���λ���֣�
$transCurrency = '156'; //���ױ��֣��̶���156

  $cardByName = '������';  //�ֿ�������
  $cardByNo ='';   //�ֿ�����
$cardType = '01';  //������    00 ���ǿ�    01 ��ǿ�   02 ׼���ǿ�
$retUrl = 'http://106.14.214.55/pay/yunjie_yunjie/notify.php';  //�첽֪ͨ��ַ
  $merUrl = 'http://106.14.214.55/pay/yunjie_yunjie/tongbuno.php';  //ҳ��֪ͨ��֧���ɹ�����ת��
$acctNo =$data['acctNo'];// '621485591431';
$bankSettNo = $data['bankSettNo'];//���к�
$acctName = $data['acctName'];
$cerNumber= $data['cerNumber'];//'350425199408140315'; //֤������
$mobile='18450087519';  //�ֻ���
$productName= '�ɿڿ���';  //��Ʒ����
//��̬��Կ����
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

//��̬��Կ
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
    'txnAmt'=>$txnAmt,
    'acctType'=>'1',
    'acctName'=>$acctName,
    'acctNo'=>$acctNo,
    'bankSettNo'=>$bankSettNo,
    'retUrl'=>'106.14.214.55/pay/yunjie_yunjie/dfnotify.php',
    'md5key'=>$md5key
);
//����������
$daifucaoz=$base->todo($pay,$resKey);
//var_dump('���'.json_encode($daifucaoz,JSON_UNESCAPED_UNICODE));
echo '<br/>';
$x= $daifucaoz->resultCode;
$y = $daifucaoz->resultDesc;
if($x!='00')
{

    $sd=array('resultCode'=>$x,'resultDesc'=>$y);
    echo json_encode($sd,JSON_UNESCAPED_UNICODE);
    exit;
}

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
$querencaoz=$base->todo($qurOr,$resKey);
var_dump('���'.json_encode($querencaoz,JSON_UNESCAPED_UNICODE));
if($querencaoz->resultCode!='00')
{
    $sd=array('resultCode'=>$daifucaoz->resultCode[0],'resultDesc'=>$daifucaoz->resultDesc[0]);
    echo json_encode($sd,JSON_UNESCAPED_UNICODE);
    exit;
}
$sd=array('resultCode'=>$daifucaoz->resultCode,'resultDesc'=>$daifucaoz->resultDesc);
echo json_encode($sd,JSON_UNESCAPED_UNICODE);
echo '<br/>';


//$orderinfo=array(
//    'charCode'=>$charCode,
//    'Version'=> $Version,
//    'TradeType'=>'0741',
//    'ChannelID'=>$ChannelID,
//    'bmMerId'=> $bmMerId,
//    'timeStamp'=>$timeStamp,
//    'orderId'=>$orderId,
//    'createIp'=>$createIp,
//    'txnAmt'=> $txnAmt,
//    'retUrl'=> $retUrl,
//    'merUrl'=> $merUrl,
//    'transCurrency'=> $transCurrency,
//    'cardByName'=> $cardByName,
//    'cardByNo'=> $cardByNo,
//    'cardType'=> $cardType,
//    'cerNumber'=> $cerNumber,
//    'mobile'=> $mobile,
//    'productName'=> $productName,
//    'md5key'=>$md5key
//);
//���֧������
//$kuaijieshengqi=$base->todo($orderinfo,$resKey);
//echo $kuaijieshengqi;