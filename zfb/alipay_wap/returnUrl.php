<?php
require_once 'inc.php';
require_once 'alipay.class.php';
require_once 'Http.php';

$alipay=new alipay();
$alipay->userid=$userid;
$alipay->userkey=$userkey;
if($ret=$alipay->isReturn()){
    $url='http://www.7foo.com/pay/alipay_wap/callback.php';
    $data=array(
        'orderid'=>$ret['orderid'],
        'money'=>$ret['total_fee'],
        'sign'=>md5($ret['orderid'].$ret['total_fee'].$userkey),
    );
    $http=new Http($url,$data);
    $http->toUrl();
    $ret=$http->getResContent();
}

header('location:/');
?>
