<?php
header('content-type:text/html;charset=utf-8');
session_start();
 $phone=$_POST["Tel"];
if(!preg_match('/^[0-9]{11,13}$/',$phone))
{
print("error");
exit();
}
  
$randCode = '';
$chars = '123456789';
for ( $i = 0; $i < 4; $i++ ){
	$randCode .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
}

$_SESSION['code'] = strtolower($randCode);
 
$sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL
  
$smsConf = array(
    'key'   => 'eb776c91955412a128177b0e06490438', //您申请的APPKEY
	 'mobile'    => $phone, //接受短信的用户手机号码
    'tpl_id'    => '29159', //您申请的短信模板ID，根据实际情况修改
  'tpl_value' =>'#code#='.$_SESSION['code']  //您设置的模板变量，根据实际情况修改
);
 
$content = juhecurl($sendUrl,$smsConf,1); //请求发送短信
 
if($content){
    $result = json_decode($content,true);
    $error_code = $result['error_code'];
    if($error_code == 0){
        //状态为0，说明短信发送成功
        echo "短信发送成功";
    }else{
        //状态非0，说明失败
        $msg = $result['reason'];
        echo "短信发送失败(".$error_code.")：".$msg;
    }
}else{
    //返回内容异常，以下可根据业务逻辑自行修改
    echo "请求发送短信失败";
}
 
/**
 * 请求接口返回内容
 * @param  string $url [请求的URL地址]
 * @param  string $params [请求的参数]
 * @param  class $ipost [是否采用POST形式]
 * @return  string
 */
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}
                       
					   
			
?>

