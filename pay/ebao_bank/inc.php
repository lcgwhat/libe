<?php
require_once '../../app/init.php';
use WY\app\woodyapp;
use WY\app\model\Payacp;

$app=woodyapp::getInstance();
$acp=new Payacp();
$acpData=$acp->get('ebao');
extract($acpData);

function GetBankCode($bankid){
	$bankcode="";
	if($bankid=='ICBC') $bankcode="ICBC-NET-B2C";
	if($bankid=='ABC') $bankcode="ABC-NET-B2C";
	if($bankid=='BOCSH') $bankcode="BOC-NET-B2C";
	if($bankid=='CCB') $bankcode="CCB-NET-B2C";
	if($bankid=='CMB') $bankcode="CMB-NET-B2C";
	if($bankid=='SPDB') $bankcode="SPDB-NET-B2C";
	if($bankid=='GDB') $bankcode="GDB-NET-B2C";
	if($bankid=='BOCOM') $bankcode="COMM-NET-B2C";
	if($bankid=='PSBC') $bankcode="POST-NET-B2C";
	if($bankid=='CNCB') $bankcode="CITIC-NET-B2C";
	if($bankid=='CMBC') $bankcode="CMBC-NET-B2C";
	if($bankid=='CEB') $bankcode="CEB-NET-B2C";
	if($bankid=='HXB') $bankcode="HXB-NET";
	if($bankid=='CIB') $bankcode="CIB-NET-B2C";
	if($bankid=='BOS') $bankcode="BOS-NET-B2C";
	if($bankid=='SRCB') $bankcode="SHRCB-NET-B2C";
	if($bankid=='PAB') $bankcode="PINGANBANK-NET";
	return $bankcode;
}
?>
