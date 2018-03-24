<?php 


class Y_des3{
    public $key;
    function __construct($key){
        $this->key=$key;
    }
    public function encrypt ($input,$ChannelID){
        $size = mcrypt_get_block_size(MCRYPT_3DES,'ecb');
        $input = $this->pkcs5_pad($input,$size);
        $td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td),MCRYPT_RAND);
        @mcrypt_generic_init($td,$this->key,$iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data=sprintf("%04d",strlen($data)+16).$ChannelID.base64_encode($data);
        return $data;
    }
    
    
    public function decrypt($encrypted){
        $encrypted = base64_decode($encrypted); //如需转换二进制可改成  bin2hex 转换
    
        $td = mcrypt_module_open(MCRYPT_3DES,'','ecb','');//3DES加密将MCRYPT_DES改为MCRYPT_3DES
    
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    
        $ks = mcrypt_enc_get_key_size($td);
        @mcrypt_generic_init($td, $this->key, $iv);
        $decrypted = mdecrypt_generic($td, $encrypted);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $y=$this->pkcs5_unpad($decrypted);
        return $y;
    }
    
    public function pkcs5_pad ($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    
    public function pkcs5_unpad($text){
        $pad = ord($text{strlen($text)-1});
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
    
}

?>