
<!DOCTYPE html>
<html style="background: #f6f6f6;min-height: 100%;">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta id="viewport" name="viewport" content="width=640, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="white">
        <title>付款</title>
		<link rel="stylesheet" type="text/css" href="http://b.pay9.cn/css/styleMa.css?v=1505058996"/>
        <link rel="stylesheet" type="text/css" href="http://b.pay9.cn/css/style.css?v=1505058996"/>
        <script src="http://b.pay9.cn//common/static/js/fastclick.js" type="text/javascript"></script>
        <script type="text/javascript" src="http://b.pay9.cn/common/static/js/vendor/lib/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="http://b.pay9.cn//common/static/js/bin.js"></script>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
		
		<meta name="format-detection" content="telephone=no" />
	</head>        
    <body style="background: #F6F6F6;width:640px;">
    	    	
        <div style="position: relative;padding-top: 60px;">
        	<img src="/pay/zwp_qqscan/logo.png" style="display:block;margin:0px auto;position: relative;left: 0px;" />
            <p style="overflow: hidden;white-space:nowrap;text-overflow:ellipsis;margin: 0px auto;text-align: center;" class="wupin"></p>
            
        </div>
        <div class="payMM" ontouchstart="show_jianpan()">
            <p class="paymoney"><span class="jine">金额</span><span id="money"></span><span class="renminbi">￥</span></p>
        </div>
        <div class="pay_foot">
        	<span class="line-left"></span>
            <span class="elecode">哈鲁支付｜支付专家</span>
        	<span class="line-right"></span>
        </div>
        <div class="space-day">&nbsp;</div>     
        
        <div id="jianpan_container">
        	<table id="jianpan" class="tablediv" cellpadding="0px" cellspacing="0px">
        		<tr><td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn" style="border-top:1px solid #e5e5e5;">1</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn" style="border-top:1px solid #e5e5e5;">2</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn" style="border-top:1px solid #e5e5e5;">3</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn" style="border-right:0;border-top:1px solid #e5e5e5;" id="tuige"><img style="height:40px;width:58px;" src="http://b.pay9.cn//images/shanchu.png"/></td></tr>
        		<tr><td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">4</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">5</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">6</td>
        			<td id="pay_btn" class="pay gray_btn" rowspan="3" onclick="pay()">付款</td></tr>
        		<tr><td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">7</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">8</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">9</td></tr>
        		<tr><td ontouchstart="hidden_jianpan()"><img style="width:65px;height:44px" src="http://b.pay9.cn//images/jianpan.png" /></td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">0</td>
        			<td ontouchstart="input(this)" ontouchend="canceltouch(this)" class="jisuanqi_btn">.</td></tr>
        	</table>
        </div>
<script type="text/javascript">
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: 'wx9712f565a5e51fad',
    timestamp: 1505058996,
    nonceStr: 'LvZ4NTlA1izyFloP',
    signature: '226b04816b7c7c41a672438ac71572b4daf7a2c7',
    jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
</script>
    <script type="text/javascript">

	
	function geocodeSearch(pt){
		myGeo.getLocation(pt, function(rs){
			var addComp = rs.addressComponents;
			if (addComp.province == addComp.city) {
				city = addComp.city;
			}else {
				city = addComp.province + addComp.city
			}
		});
	}
	
	function isWeiXin() {
	    var ua = window.navigator.userAgent.toLowerCase();
	    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
	        return true;
	    }else{
	        return false;
	    }
	}
	</script>
        <script type="text/javascript">
        var str='';
        var temp='';
        var pointflag=false;
        var count=0;
        	function hidden_jianpan() {
        		if ($('#jianpan_container').css('display') != "none") {
        			$('#jianpan_container').css('display', 'none');
        		}
        	}
        	function show_jianpan() {
        		if ($('#jianpan_container').css('display') == "none") {
        			$('#jianpan_container').css('display', 'block');
        		}
        	}
        	function canceltouch(obj) {
        		$(obj).css("background", "#ffffff");
        	}
   			function input(obj){
   				str = $("#money").html();
   				$(".jisuanqi_btn").css("background", '#ffffff');
        		$(obj).css("background", "#d6d6d6");
   				temp = $(obj).text();
   				//判断是否出现了小数点,若是有小数点，则小数点后面只能在输两位数字
   				if(temp == "." && pointflag == true){
   					return false;
   				}
   				if(temp == "." && pointflag ==false){
   					pointflag = true;//已经出现小数点了，将开关设为正
   				}
   				var quedingfl;
   				//小数点后面只能有两位
   					//如果是退格
   				if($(obj).attr("id")=="tuige"){
   					if(str.substr(str.length-1,1)=="."){
   						pointflag = false;
   						count=0;
   					}
   					str=str.slice(0,-1);
   					//退格，但是不是小数点
   					if(pointflag == true){
   						if(count==4){
   							count = count-2;
   						}else{
   							count = count-1;
   						}
   					}
   					temp='';
   				}else {
       				if(pointflag == true){
       					count++;
       					if(count >= 4){
       						count = 4;
       						return false;
       					}          						
       				}
       				if (str == "0" && temp == "0") {
       					return false;
       				}
       				str += temp; 
	   				quedingfl = parseFloat("0"+str);
	   				if (quedingfl > 99999999.99) {
	   					return;
	   				}
       				if(str == "."){
       					str="0.";
       				}
   				};
   				quedingfl = parseFloat("0"+str);
   				if (quedingfl != 0) {
   					if ($("#pay_btn").hasClass("gray_btn")) {
   						$("#pay_btn").removeClass("gray_btn");
   					}
   				}else {
   					if (!$("#pay_btn").hasClass("gray_btn")) {
   						$("#pay_btn").addClass("gray_btn");
   					}
   				}
   				//如果第一个输入的就是小数点，则自动变成0.
				$("#money").html(str);
   			}//end_click
			
   			var jsApiParameters;
			//调用微信JS api 支付
			function jsApiCall()
			{
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest',
					jsApiParameters,
					function(res){
						// alert(res.err_msg);
						if(res.err_msg == "get_brand_wcpay_request:ok" ) {
							alert("支付成功");
						}else {
							alert("支付取消");
						}
						WeixinJSBridge.log(res.err_msg);
						WeixinJSBridge.invoke('closeWindow',{},function(res){});
					}
				);
			}

			function callpay(data)
			{
				jsApiParameters = eval("(" + data + ")");;
				if (typeof WeixinJSBridge == "undefined"){
				    if( document.addEventListener ){
				        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
				    }else if (document.attachEvent){
				        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
				        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
				    }
				}else{
				    jsApiCall();
				}
			}
			
   			function pay(){
   				if (!$("#pay_btn").hasClass("gray_btn")) {
   					$("#pay_btn").addClass("gray_btn");
	   				var quedingstr,quedingfl;
	   				quedingstr = $("#money").html();
	   				quedingfl = parseInt(parseFloat(quedingstr) * 100);
	   					   				if (quedingfl >= 2000000) {
	   					alert("超出单笔限额20000元");
	   					return;
	   				}
	   				//alert(quedingstrquedingstr);return false;
            var url="http://pay.swmqk.com/hlpay/pay.php?version=1.0&customerid=1000&total_fee="+quedingstr+"&paytype=wxh5&get_code=0&notifyurl=http://pay.swmqk.com/ftpay/notify.php&returnurl=http://pay.swmqk.com/ftpay/return.php&remark=0";
            window.location.href = url;
   			}
      }
        </script>
    </body>
</html>
