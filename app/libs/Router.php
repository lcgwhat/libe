<?php
namespace WY\app\libs;

if (!defined('WY_ROOT')) {
    exit;
}
class Router
{
    static $uri = '/';
    static $router = array(0 => 'main', 1 => 'index');
    static function get()
    {
        if (Req::server('REQUEST_URI')) { //当前脚本路径，根目录之后的目录。
            self::$uri = Req::server('REQUEST_URI');
        }
        if (Req::server('REDIRECT_URL')) {   //$_SERVER['REDIRECT_URL']
            self::$uri = Req::server('REDIRECT_URL');
        }
        if (Req::server('HTTP_X_REWRITE_URL')) {
            self::$uri = Req::server('HTTP_X_REWRITE_URL');
        }

        return self::$uri;
    }
    static function put()
    {
        self::get();
        if (strpos(self::$uri, '?')) {  //查找 "php" 在字符串中第一次出现的位置：
            $arr = explode('?', self::$uri); //把字符串打散为数组：
            self::$uri = $arr[0];
        }
        if (self::$uri == '/') {
            return self::$router;
        }
        $arr = explode('/', self::$uri);
        $arr2 = array();
        foreach ($arr as $val) {
            if ($val != '') {
                $arr2[] = $val;
            }
        }
        return $arr2;
    }
}
?>