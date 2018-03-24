<?php
namespace WY\app\libs;

if (!defined('WY_ROOT')) {
    exit;
}
class Http
{
    private $resCode;
    private $errInfo;
    private $resContent;
    function __construct($url, $data, $build = 0, $timeout = 15)
    {
        $this->url = $url;
        $this->data = $data;
        $this->timeout = $timeout;
        $this->build = $build;
    }
    public function toUrl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build ? http_build_query($this->data) : $this->data);
        $res = curl_exec($ch);//— 执行一个cURL会话
        $this->resCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);// 获取一个cURL连接资源句柄的信息,最后一个收到的HTTP代码
        if ($res == NULL) {
            $this->errInfo = "call http err :" . curl_errno($ch) . " - " . curl_error($ch);
            curl_close($ch);
            return false;
        } else {
            if ($this->resCode != "200") {
                $this->errInfo = "call http err httpcode=" . $this->resCode;
                curl_close($ch);
                return false;
            }
        }
        curl_close($ch);
        $this->resContent = $res;
        return true;
    }
    public function getResContent()//得到返回内容
    {
        return $this->resContent;
    }
    public function getResCode()
    {
        return $this->resCode;
    }
    public function getErrInfo() //错误信息
    {
        return $this->errInfo;
    }
}
?>