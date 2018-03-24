<?php
namespace WY\app\libs;

use WY\app\woodyapp;
use WY\app\libs\Router;
use WY\app\libs\Req;
use WY\app\libs\Res;
use WY\app\libs\Page;
use WY\app\libs\Session;
use WY\app\controller\chkcode;
use WY\app\libs\Model;
use WY\app\Config;
use WY\app\model\Verifyuser;
if (!defined('WY_ROOT')) {
    exit;
}
/*
 * 类Controller：辅助工具类实例
 * */
class Controller
{
    public $data;
    public $tpl = 'view/default/';
    function __construct()
    {
        $this->router = new Router(); //路由规则
        $this->req = new Req();  //sesseion.cookie.安全验证的封装
        $this->res = new Res();  //
        $this->page = new Page(); //分页
        $this->session = new Session(); //sesseion值得获取
        $this->chkcode = new chkcode(); //验证码生成器
        $this->config = $this->model()->select()->from('config')->fetchRow();
        $this->action = $this->router->put();
        $this->setConfig = new Config();
        $this->verifyUser = new Verifyuser();
    }
    public function model()
    {
        return new Model();
    }
    public function put($file, $data = array())
    {
        if ($data) {
            extract($data);
        }
        if (!file_exists($this->tpl . $file)) {
            $file = 'woodyapp.php';
        }
        require_once $this->tpl . $file;
        $content = ob_get_contents();
        ob_get_clean();
        echo $content;
        if (ob_get_level()) {
            ob_end_flush();
        }
    }
}
?>