<?php
namespace WY\app\controller;

use WY\app\libs\Controller;
if (!defined('WY_ROOT')) {
    exit;
}
class main extends Controller
{
    public function index()
    {
		header('Location: /stzf.html');
		$url = $_SERVER['HTTP_HOST'];
		if($url == 'http://127.0.0.1/stzf.html'){
	header('Location:http://127.0.0.1/stzf.html');
		}else{
        $this->put('home.php');
			  }
	  }
}
?>