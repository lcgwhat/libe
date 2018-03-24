<?php
class weixin{
	private $postData;
	private $gateUrl;
	private $key;
	private $sign;
	private $timeOut=30;
	private $responseCode;
	private $resContent;
	private $errInfo;
	private $postXmlData;
	
	function __construct(){
		$this->gateUrl='https://pay.swiftpass.cn/pay/gateway';
	}
	
	function setKey($key){
		$this->key=$key;
	}
	
	function getKey(){
		return $this->key;
	}
	
	function getPostData(){
		return $this->postData;
	}
	
	function setPostData($data){
		$this->postData=$data;
	}
	
	public function makeSign(){
		if(!$this->postData || !is_array($this->postData)) return false;
		$newData='';
		ksort($this->postData);
		foreach($this->postData as $key=>$val){
			if($val!=='' && $key!='sign'){
				$newData.=$key.'='.$val.'&';
			}
		}
		if(!$newData) return false;
		//$this->logs('makeSign',$newData.'key='.$this->getKey());
		$this->sign=strtoupper(md5($newData.'key='.$this->getKey()));
		return $this;
	}
	
	private function toXml($array){
        $xml = '<xml>';
        foreach($array as $k=>$v){
            $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
        }
        $xml.='</xml>';
        return $xml;
	}
	
	public function submitOrder(){
		$this->postData['sign']=$this->sign;
		$this->postXmlData=$this->toXml($this->postData);
		$this->postXml();
	}
	
	private function postXml(){
		//启动一个CURL会话
		$ch = curl_init();

		// 设置curl允许执行的最长秒数
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeOut);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
		// 获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		
        //发送一个常规的POST请求。
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $this->gateUrl);
        //要传送的所有数据
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postXmlData);
		
		// 执行操作
		$res = curl_exec($ch);
		$this->responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		if ($res == NULL) { 
		   $this->errInfo = "call http err :" . curl_errno($ch) . " - " . curl_error($ch) ;
		   curl_close($ch);
		   return false;
		} else if($this->responseCode  != "200") {
			$this->errInfo = "call http err httpcode=" . $this->responseCode  ;
			curl_close($ch);
			return false;
		}
		
		curl_close($ch);
		$this->resContent = $res;
		return true;
	}
	
	function getResponseCode(){
		return $this->responseCode;
	}
	
	function getResContent(){
		return $this->parseXML($this->resContent);
	}
	
	function getErrInfo(){
		return $this->errInfo;
	}
	
    public function parseXML($xmlSrc){
        if(empty($xmlSrc)){
            return false;
        }
        $array = array();
        $xml = simplexml_load_string($xmlSrc);
        $encode = $this->getXmlEncode($xmlSrc);

        if($xml && $xml->children()) {
			foreach ($xml->children() as $node){
				//有子节点
				if($node->children()) {
					$k = $node->getName();
					$nodeXml = $node->asXML();
					$v = substr($nodeXml, strlen($k)+2, strlen($nodeXml)-2*strlen($k)-5);
					
				} else {
					$k = $node->getName();
					$v = (string)$node;
				}
				
				if($encode!="" && $encode != "UTF-8") {
					$k = iconv("UTF-8", $encode, $k);
					$v = iconv("UTF-8", $encode, $v);
				}
				$array[$k] = $v;
			}
		}
        return $array;
    }

    //获取xml编码
	function getXmlEncode($xml) {
		$ret = preg_match ("/<?xml[^>]* encoding=\"(.*)\"[^>]* ?>/i", $xml, $arr);
		if($ret) {
			return strtoupper ( $arr[1] );
		} else {
			return "";
		}
	}
	
    public function logs($title,$data){
        $handler = fopen('result.txt','a+');
        $content = "================".$title."===================\n";
        if(is_string($data) === true){
            $content .= $data."\n";
        }
        if(is_array($data) === true){
            forEach($data as $k=>$v){
                $content .= "key: ".$k." value: ".$v."\n";
            }
        }
        $flag = fwrite($handler,$content);
        fclose($handler);
    }
	
	public function verifySign(){
		$xml=file_get_contents('php://input');
		if(!$xml) return false;
		$array=$this->parseXML($xml);
		$this->postData=$array;
		$this->makeSign();
		return $this->getSign()==$array['sign'];
	}
	
	public function getParam($key){
		return $this->postData[$key];
	}
	
	public function getSign(){
		return $this->sign;
	}
}
?>