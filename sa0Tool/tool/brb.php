<?php
class baseRc4Base {
    public static function setEncrypt($data,$rc4key=RC4KEY,$replace=false){
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        $data=base64_encode($data);
        $result=self::rc4($data,$rc4key);
        $result=base64_encode($result);
        if($replace){
            $result = str_replace('=', '__', $result);
            $result = str_replace('+', '-', $result);
            $result = str_replace('/', '_', $result);
        }
        return $result;
    }

    public static function setDecrypt($data,$rc4key=RC4KEY,$replace=false){
        if (!$data) {
            return false;
        }
        $data=str_replace(" ","+",$data);

        if($replace){
            $data = str_replace('__', '=', $data);
            $data = str_replace('-', '+', $data);
            $data = str_replace('_', '/', $data);
        }
        $data=base64_decode($data);
        $result=self::rc4($data,$rc4key);
        $result=base64_decode($result);
        return $result;
    }

    public static function rc4($data, $pwd = "")
    {
        if(empty($pwd)){
            $pwd=RC4KEY;
        }
        $cipher = "";
        $key[] = "";
        $box[] = "";
        $pwd_length = strlen($pwd);
        $data_length = strlen($data);
        for ($i = 0; $i < 256; $i++) {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        return $cipher;
    }
}