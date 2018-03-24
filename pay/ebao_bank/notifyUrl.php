<?php
require_once 'inc.php';
require_once 'yeepayCommon.php';
use WY\app\model\Handleorder;
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
$bRet=1;

if($bRet){
	if($r1_Code=="1"){
		if($r9_BType=="1"){
            $handle=@new Handleorder($r6_Order,$r3_Amt);
            $handle->updateUncard();
			header('location:returnUrl.php?orderid='.$r6_Order);
		}elseif($r9_BType=="2"){
			echo "success";
            $handle=@new Handleorder($r6_Order,$r3_Amt);
            $handle->updateUncard();
		}
	}
}
?>
