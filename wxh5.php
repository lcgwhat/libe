<?php 

//var_dump($_GET);die;
//echo '支付通道维护升级，请广大客户耐心等待!! ';die;
//$murl=$_SERVER['HTTP_HOST'];
//var_dump($_GET);die;
$url='http://'.$_SERVER['HTTP_HOST'].'/pay/dou/demo.php?orderid='.$_GET['orderid'].'&price=' .$_GET['price'].'&rurl='.$_GET['remark'];
//var_dump($url);die;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title></title>
</head>
<body>
	<style type="text/css">
	*{margin:0; padding:0;}
	img{max-width: 100%; height: auto;}
	.test{height: 600px; max-width: 600px; font-size: 40px;}
	</style>
	<div class="test">
		<img src='/static/wx-zfb/124.png'>
		
	</div>
	<script type="text/javascript">
		function is_weixin() {
		    var ua = navigator.userAgent.toLowerCase();
		    if (ua.match(/MicroMessenger/i) == "micromessenger") {
		        return true;
		    } else {
		        return false;
		    }
		}
		var isWeixin = is_weixin();
		var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight;
		function loadHtml(){
			var div = document.createElement('div');
			div.id = 'weixin-tip';
			div.innerHTML = '<p><img src="/static/wx-zfb/live_weixin.png" alt="微信打开"/></p>';
			document.body.appendChild(div);
		}
		
		function loadStyleText(cssText) {
	        var style = document.createElement('style');
	        style.rel = 'stylesheet';
	        style.type = 'text/css';
	        try {
	            style.appendChild(document.createTextNode(cssText));
	        } catch (e) {
	            style.styleSheet.cssText = cssText; //ie9以下
	        }
            var head=document.getElementsByTagName("head")[0]; //head标签之间加上style样式
            head.appendChild(style); 
	    }
	    var cssText = "#weixin-tip{position: fixed; left:0; top:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80); width: 100%; height:100%; z-index: 100;} #weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}";
		if(isWeixin){
			loadHtml();
			loadStyleText(cssText);
			
		}else{
			window.location.href="<?php echo $url ?>"; 
		}
		
	</script>
</body>
</html>