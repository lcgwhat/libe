<?php
require_once 'merchantProperties.php';
use WY\app\libs\Http;
function getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime)
{

	global $p1_MerId,$merchantKey,$reqURL_SNDApro;
		$sbOld		=	"";
	$sbOld		=	$sbOld.$p0_Cmd;
	$sbOld		=	$sbOld.$p1_MerId;
	$sbOld		=	$sbOld.$p2_Order;
	$sbOld		=	$sbOld.$p3_Amt;
	$sbOld		=	$sbOld.$p4_verifyAmt;
	$sbOld		=	$sbOld.$p5_Pid;
	$sbOld		=	$sbOld.$p6_Pcat;
	$sbOld		=	$sbOld.$p7_Pdesc;
	$sbOld		=	$sbOld.$p8_Url;
	$sbOld 		= $sbOld.$pa_MP;
	$sbOld 		= $sbOld.$pa7_cardAmt;
	$sbOld		=	$sbOld.$pa8_cardNo;
	$sbOld		=	$sbOld.$pa9_cardPwd;
	$sbOld		=	$sbOld.$pd_FrpId;
	$sbOld		=	$sbOld.$pr_NeedResponse;
	$sbOld		=	$sbOld.$pz_userId;
	$sbOld		=	$sbOld.$pz1_userRegTime;

	//logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey),$merchantKey);
	return HmacMd5($sbOld,$merchantKey);

}


function annulCard($p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pz_userId,$pz1_userRegTime)
{

	global $p1_MerId,$merchantKey,$reqURL_SNDApro;

	$p0_Cmd					= "ChargeCardDirect";

	$pr_NeedResponse	= "1";

	$hmac	= getReqHmacString($p0_Cmd,$p2_Order,$p3_Amt,$p4_verifyAmt,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pa7_cardAmt,$pa8_cardNo,$pa9_cardPwd,$pd_FrpId,$pr_NeedResponse,$pz_userId,$pz1_userRegTime);

	$params = array(
		'p0_Cmd'						=>	$p0_Cmd,
		'p1_MerId'					=>	$p1_MerId,
		'p2_Order' 					=>	$p2_Order,
		'p3_Amt'						=>	$p3_Amt,
		'p4_verifyAmt'						=>	$p4_verifyAmt,
		'p5_Pid'						=>	$p5_Pid,
		'p6_Pcat'						=>	$p6_Pcat,
		'p7_Pdesc'						=>	$p7_Pdesc,
		'p8_Url'						=>	$p8_Url,
		'pa_MP'					  	=> 	$pa_MP,
		'pa7_cardAmt'				=>	$pa7_cardAmt,
		'pa8_cardNo'				=>	$pa8_cardNo,
		'pa9_cardPwd'				=>	$pa9_cardPwd,
		'pd_FrpId'					=>	$pd_FrpId,
		'pr_NeedResponse'		=>	$pr_NeedResponse,
		'hmac' 							=>	$hmac,
		'pz_userId'			=>	$pz_userId,
		'pz1_userRegTime' 		=>	$pz1_userRegTime
		);
        $http=new Http($reqURL_SNDApro, $params,1);
		$http->toUrl();
        $pageContents=$http->getResContent();
	//echo "pageContents:".$pageContents;exit;
	$result 				= explode("\n",$pageContents);

	$r0_Cmd				=	"";
	$r1_Code			=	"";
	$r2_TrxId			=	"";
	$r6_Order			=	"";
	$rq_ReturnMsg	=	"";
	$hmac					=	"";
  $unkonw				= "";

	for($index=0;$index<count($result);$index++){
		$result[$index] = trim($result[$index]);
		if (strlen($result[$index]) == 0) {
			continue;
		}
		$aryReturn		= explode("=",$result[$index]);
		$sKey					= $aryReturn[0];
		$sValue				= $aryReturn[1];
		if($sKey			=="r0_Cmd"){
			$r0_Cmd				= $sValue;
		}elseif($sKey == "r1_Code"){
			$r1_Code			= $sValue;
		}elseif($sKey == "r2_TrxId"){
			$r2_TrxId			= $sValue;
		}elseif($sKey == "r6_Order"){
			$r6_Order			= $sValue;
		}elseif($sKey == "rq_ReturnMsg"){
			$rq_ReturnMsg	= $sValue;
		}elseif($sKey == "hmac"){
			$hmac 				= $sValue;
		} else{
			return $result[$index];
		}
	}


	$sbOld="";
	$sbOld = $sbOld.$r0_Cmd;
	$sbOld = $sbOld.$r1_Code;
	$sbOld = $sbOld.$r6_Order;
	$sbOld = $sbOld.$rq_ReturnMsg;
	$sNewString = HmacMd5($sbOld,$merchantKey);
 // logstr($r6_Order,$sbOld,HmacMd5($sbOld,$merchantKey),$merchantKey);

	if($sNewString==$hmac) {
		if($r1_Code=="1"){
			echo "ok";
		      return;
		} else if($r1_Code=="2"){
		      echo "支付卡密无效";
		      return;
		} else if($r1_Code=="7"){
		      echo "支付卡密无效";
		      return;
		} else if($r1_Code=="11"){
		      echo "订单号重复";
		      return;
		} else{
		      echo "提交失败";
		      return;
		}
	} else{
		echo "交易签名无效";
		exit;
	}
}

function generationTestCallback($p2_Order,$p3_Amt,$p8_Url,$pa7_cardNo,$pa8_cardPwd,$pa_MP,$pz_userId,$pz1_userRegTime)
{

	global $p1_MerId,$merchantKey,$reqURL_SNDApro;
 	include_once 'HttpClient.class.php';

	$p0_Cmd					= "AnnulCard";

	$pr_NeedResponse	= "1";

	$reqURL_SNDApro		= "http://tech.yeepay.com:8080/robot/generationCallback.action";

	$params = array(
		'p0_Cmd'						=>	$p0_Cmd,
		'p1_MerId'					=>	$p1_MerId,
		'p2_Order' 					=>	$p2_Order,
		'p3_Amt'						=>	$p3_Amt,
		'p8_Url'						=>	$p8_Url,
		'pa7_cardNo'				=>	$pa7_cardNo,
		'pa8_cardPwd'				=>	$pa8_cardPwd,
		'pd_FrpId'					=>	$pd_FrpId,
		'pr_NeedResponse'		=>	$pr_NeedResponse,
		'pa_MP'							=>	$pa_MP,
		'pz_userId'			=>	$pz_userId,
		'pz1_userRegTime' 		=>	$pz1_userRegTime);

	$pageContents	= HttpClient::quickPost($reqURL_SNDApro, $params);
	return $pageContents;
}

function getCallbackHmacString($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,
$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct)
{

	global $p1_MerId,$merchantKey,$reqURL_SNDApro;

	$sbOld="";
	$sbOld = $sbOld.$r0_Cmd;
	$sbOld = $sbOld.$r1_Code;
	$sbOld = $sbOld.$p1_MerId;
	$sbOld = $sbOld.$p2_Order;
	$sbOld = $sbOld.$p3_Amt;
	$sbOld = $sbOld.$p4_FrpId;
	$sbOld = $sbOld.$p5_CardNo;
	$sbOld = $sbOld.$p6_confirmAmount;
	$sbOld = $sbOld.$p7_realAmount;
	$sbOld = $sbOld.$p8_cardStatus;
	$sbOld = $sbOld.$p9_MP;
	$sbOld = $sbOld.$pb_BalanceAmt;
	$sbOld = $sbOld.$pc_BalanceAct;

 // logstr($p2_Order,$sbOld,HmacMd5($sbOld,$merchantKey),$merchantKey);
	return HmacMd5($sbOld,$merchantKey);

}


function getCallBackValue(&$r0_Cmd,&$r1_Code,&$p1_MerId,&$p2_Order,&$p3_Amt,&$p4_FrpId,&$p5_CardNo,&$p6_confirmAmount,&$p7_realAmount,
&$p8_cardStatus,&$p9_MP,&$pb_BalanceAmt,&$pc_BalanceAct,&$hmac)
{

$r0_Cmd = isset($_REQUEST['r0_Cmd']) ? $_REQUEST['r0_Cmd'] : '';
$r1_Code = isset($_REQUEST['r1_Code']) ? $_REQUEST['r1_Code'] : '';
$p1_MerId = isset($_REQUEST['p1_MerId']) ? $_REQUEST['p1_MerId'] : '';
$p2_Order = isset($_REQUEST['p2_Order']) ? $_REQUEST['p2_Order'] : '';
$p3_Amt = isset($_REQUEST['p3_Amt']) ? $_REQUEST['p3_Amt'] : '';
$p4_FrpId = isset($_REQUEST['p4_FrpId']) ? $_REQUEST['p4_FrpId'] : '';
$p5_CardNo = isset($_REQUEST['p5_CardNo']) ? $_REQUEST['p5_CardNo'] : '';
$p6_confirmAmount = isset($_REQUEST['p6_confirmAmount']) ? $_REQUEST['p6_confirmAmount'] : '';
$p7_realAmount = isset($_REQUEST['p7_realAmount']) ? $_REQUEST['p7_realAmount'] : '';
$p8_cardStatus = isset($_REQUEST['p8_cardStatus']) ? $_REQUEST['p8_cardStatus'] : '';
$p9_MP = isset($_REQUEST['p9_MP']) ? $_REQUEST['p9_MP'] : '';
$pb_BalanceAmt = isset($_REQUEST['pb_BalanceAmt']) ? $_REQUEST['pb_BalanceAmt'] : '';
$pc_BalanceAct = isset($_REQUEST['pc_BalanceAct']) ? $_REQUEST['pc_BalanceAct'] : '';
$hmac = isset($_REQUEST['hmac']) ? $_REQUEST['hmac'] : '';

return null;

}


function CheckHmac($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,
$pc_BalanceAct,$hmac)
{
	if($hmac==getCallbackHmacString($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,
	$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct))
		return true;
	else
		return false;

}


function HmacMd5($data,$key)
{

	$key = iconv("GBK","UTF-8",$key);
	$data = iconv("GBK","UTF-8",$data);

	$b = 64;
	if (strlen($key) > $b) {
	$key = pack("H*",md5($key));
	}
	$key = str_pad($key, $b, chr(0x00));
	$ipad = str_pad('', $b, chr(0x36));
	$opad = str_pad('', $b, chr(0x5c));
	$k_ipad = $key ^ $ipad ;
	$k_opad = $key ^ $opad;

	return md5($k_opad . pack("H*",md5($k_ipad . $data)));

}
function logstr($orderid,$str,$hmac,$keyValue)
{
include 'merchantProperties.php';
$james=fopen($logName,"a+");
fwrite($james,"\r\n".date("Y-m-d H:i:s")."|orderid[".$orderid."]|str[".$str."]|hmac[".$hmac."]|keyValue[".$keyValue."]");
fclose($james);
}

function arrToString($arr,$Separators)
{
	$returnString = "";
	foreach ($arr as $value) {
    		$returnString = $returnString.$value.$Separators;
	}
	return substr($returnString,0,strlen($returnString)-strlen($Separators));
}

function arrToStringDefault($arr)
{
	return arrToString($arr,",");
}

?>
