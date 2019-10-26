<?php

/***
 * @author: sa0ChunLuyu
 * @time: 2019-09-17 13:14
 */
class verifyCode
{
    private $width;                                //图片宽度
    private $height;                               //图片高度
    public $bgcolor = array(255, 255, 255); //背景颜色
    public $codeColor = array(0, 0, 0);     //验证码颜色
    public $fontSize = 20;                 //验证码字符大小
    private $totalChars = 4;                  //总计字符数
    private $numbers = 1;                  //数字形式字符数量
    private $securityCode;                         //验证码内容
    public $fontFamily = null;               //字体文件路径
    public $noise = true;               //绘制干扰
    private $img = null;               //绘图资源
    public $noiseNumber = 6;

    private function setChars()
    {
        $strall = 'abcdefghjkmnpqrstwxyz';
        for ($i = 0; $i < ($this->totalChars - $this->numbers); $i++) {
            $text[] = $strall[mt_rand(0, 20)];
        }
        for ($i = 0; $i < $this->numbers; $i++) {
            $text[] = mt_rand(2, 9);
        }
        shuffle($text);
        $this->securityCode = implode('', $text);
        $this->img = imagecreatetruecolor($this->width, $this->height);
    }

    public function createVerify($width = 88, $height = 30, $totalChars = 4, $numbers = 1, $fontFamily = 'AMBROSIA.ttf')
    {
        $this->fontFamily = dirname(__DIR__) . '\\fonts\\' . $fontFamily;
        $this->width = $width;
        $this->height = $height;
        $this->totalChars = $totalChars;
        $this->numbers = $numbers;
        if ($this->fontFamily == null) return '验证码字体设置错误';
        if (!is_file($this->fontFamily)) return '验证码字体文件不存在';
        $this->setChars();
        $bgColor = imagecolorallocate($this->img, $this->bgcolor[0], $this->bgcolor[1], $this->bgcolor[2]);
        imagefill($this->img, 0, 0, $bgColor);
        if ($this->noise) {
            $this->writeNoise();
        }
        $textColor = imagecolorallocate($this->img, $this->codeColor[0], $this->codeColor[1], $this->codeColor[2]);
        $textFffset = imagettfbbox($this->fontSize, 0, $this->fontFamily, $this->securityCode);
        $fx = intval(($this->width - ($textFffset[2] - $textFffset[0])) / 2);
        $fy = $this->height - ($this->height - $this->fontSize) / 2;
        imagefttext($this->img, $this->fontSize, 0, $fx, $fy, $textColor, $this->fontFamily, $this->securityCode);
        $base64 = $this->base64EncodeImage($this->img);
        imagedestroy($this->img);
        return array('code' => $this->securityCode, 'base64' => $base64);
    }

    public function base64EncodeImage($image_file)
    {
        ob_start();
        imagepng($image_file);
        $image_data = ob_get_contents();
        ob_end_clean();
        return $image_data_base64 = "data:image/png;base64," . base64_encode($image_data);
    }

    private function writeNoise()
    {
        $code = '012345678abcdefhijkmnopqrstuvwxyz';
        for ($i = 0; $i < $this->noiseNumber; $i++) {
            $noiseColor = imagecolorallocate($this->img, mt_rand(150, 225), mt_rand(150, 225), mt_rand(150, 225));
            for ($j = 0; $j < 2; $j++) {
                imagestring($this->img, 5, mt_rand(-10, $this->width), mt_rand(-10, $this->height), $code[mt_rand(0, 29)], $noiseColor);
            }
        }
    }
}