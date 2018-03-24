<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/7
 * Time: 13:40
 */
namespace WY\app\controller;

use WY\app\libs\Controller;
use WY\app\libs\Std3Des;
if (!defined('WY_ROOT')) {
    exit;
}
class apishiming extends api123
{
    public function index()
    {

        extract($this->params); //extract()函数从数组中将变量导入到当前的符号表

        $signStr = 'version=' . $version . '&customerid=' . $customerid  . '&sdorderno=' . $sdorderno . '&cardNo=' . $cardNo . '&cardName=' . $cardName .'&idCardNo=' . $idCardNo .'&phoneNum=' . $phoneNum . '&' . $this->userData['apikey'];


        $mysign = md5($signStr);
      // var_dump($signStr);exit;
        if ($sign != $mysign) {
            echo $this->ret->put('201', $cardnum ? true : false);
            exit;
        }
        $std=new Std3Des("D8BE3EC1527EF151A144C164F2678C39","12345678");
        $data=$std->encrypt(json_encode($this->params));
        $udata=urlencode($data);
        $url = 'http://' . $this->req->server('HTTP_HOST') . '/pay/swift_tongming/shiming.php?data='.$udata;

        $this->res->redirect($url);
    }
}
?>