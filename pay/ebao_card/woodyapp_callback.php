<?php
include 'inc.php';
include 'yeepayCommon.php';
use WY\app\model\Handleorder;

$return=getCallBackValue($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct,$hmac);

$bRet=CheckHmac($r0_Cmd,$r1_Code,$p1_MerId,$p2_Order,$p3_Amt,$p4_FrpId,$p5_CardNo,$p6_confirmAmount,$p7_realAmount,$p8_cardStatus,$p9_MP,$pb_BalanceAmt,$pc_BalanceAct,$hmac);

$bRet=1;

if($bRet){
    echo "success";
    if($r1_Code=="1"){
        $handle=@new Handleorder($p2_Order,$p3_Amt,$p5_CardNo,$p7_realAmount,$p8_cardStatus);
        $handle->updateCard();
    }
}
?>
