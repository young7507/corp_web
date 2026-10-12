<?php

namespace app\kcadminapi\controller;

use think\facade\Cache;

class Slideverify
{
    private $bgWidth = 300;
    private $bgHeight = 150;
    private $blockSize = 50;
    private $blockRadius = 8;
    private $tolerance = 5;

    public function generate()
    {
        $bgImg = $this->createBackground();
        if (!$bgImg) {
            return jsonError('验证码生成失败');
        }

        $blockX = rand($this->blockSize + 10, $this->bgWidth - $this->blockSize - 10);
        $blockY = rand(10, $this->bgHeight - $this->blockSize - 10);

        $puzzlePath = $this->createPuzzlePath($this->blockSize, $this->blockRadius);

        $blockW = $this->blockSize + $this->blockRadius;
        $blockH = $this->blockSize + $this->blockRadius;

        $blockImg = imagecreatetruecolor($blockW, $blockH);
        imagealphablending($blockImg, false);
        imagesavealpha($blockImg, true);
        $blockBg = imagecolorallocatealpha($blockImg, 0, 0, 0, 127);
        imagefill($blockImg, 0, 0, $blockBg);

        imagecopy($blockImg, $bgImg, 0, 0, $blockX, $blockY, $blockW, $blockH);

        $blockMask = imagecreatetruecolor($blockW, $blockH);
        imagealphablending($blockMask, false);
        imagesavealpha($blockMask, true);
        $maskBg = imagecolorallocatealpha($blockMask, 0, 0, 0, 127);
        imagefill($blockMask, 0, 0, $maskBg);
        $maskFg = imagecolorallocatealpha($blockMask, 255, 255, 255, 0);

        imagefilledpolygon($blockMask, $puzzlePath, $maskFg);

        imagealphablending($blockImg, false);
        $transparentColor = imagecolorallocatealpha($blockImg, 0, 0, 0, 127);
        for ($x = 0; $x < $blockW; $x++) {
            for ($y = 0; $y < $blockH; $y++) {
                $maskColor = imagecolorat($blockMask, $x, $y);
                $maskAlpha = ($maskColor >> 24) & 0x7F;
                if ($maskAlpha == 127) {
                    imagesetpixel($blockImg, $x, $y, $transparentColor);
                }
            }
        }

        imagealphablending($blockImg, true);
        $borderColor = imagecolorallocatealpha($blockImg, 255, 255, 255, 40);
        imagesetthickness($blockImg, 2);
        imagepolygon($blockImg, $puzzlePath, $borderColor);

        $holeMask = imagecreatetruecolor($this->bgWidth, $this->bgHeight);
        imagealphablending($holeMask, false);
        imagesavealpha($holeMask, true);
        $holeBg = imagecolorallocatealpha($holeMask, 0, 0, 0, 127);
        imagefill($holeMask, 0, 0, $holeBg);
        $holeFg = imagecolorallocatealpha($holeMask, 0, 0, 0, 0);
        $offsetPath = [];
        for ($i = 0; $i < count($puzzlePath); $i += 2) {
            $offsetPath[] = $puzzlePath[$i] + $blockX;
            $offsetPath[] = $puzzlePath[$i + 1] + $blockY;
        }
        imagefilledpolygon($holeMask, $offsetPath, $holeFg);

        for ($x = 0; $x < $this->bgWidth; $x++) {
            for ($y = 0; $y < $this->bgHeight; $y++) {
                $maskColor = imagecolorat($holeMask, $x, $y);
                $maskAlpha = ($maskColor >> 24) & 0x7F;
                if ($maskAlpha == 0) {
                    $bgColor = imagecolorat($bgImg, $x, $y);
                    $r = ($bgColor >> 16) & 0xFF;
                    $g = ($bgColor >> 8) & 0xFF;
                    $b = $bgColor & 0xFF;
                    $darkColor = imagecolorallocatealpha($bgImg, (int)($r * 0.6), (int)($g * 0.6), (int)($b * 0.6), 0);
                    imagesetpixel($bgImg, $x, $y, $darkColor);
                }
            }
        }

        $holeBorderColor = imagecolorallocatealpha($bgImg, 255, 255, 255, 60);
        imagesetthickness($bgImg, 2);
        imagepolygon($bgImg, $offsetPath, $holeBorderColor);

        $bgBase64 = $this->imageToBase64($bgImg);
        $blockBase64 = $this->imageToBase64($blockImg);

        imagedestroy($bgImg);
        imagedestroy($blockImg);
        imagedestroy($blockMask);
        imagedestroy($holeMask);

        $token = md5(uniqid((string)mt_rand(), true));
        Cache::set('captcha_' . $token, $blockX, 300);

        return jsonSuccess([
            'bg'     => $bgBase64,
            'block'  => $blockBase64,
            'token'  => $token,
            'blockY' => $blockY,
        ]);
    }

    public function verify()
    {
        $token = input('token', '');
        $x     = input('x', 0, 'floatval');

        if (empty($token) || $x <= 0) {
            return jsonError('参数错误');
        }

        $correctX = Cache::get('captcha_' . $token);
        Cache::delete('captcha_' . $token);

        if ($correctX === null) {
            return jsonError('验证码已过期，请刷新');
        }

        if (abs($x - $correctX) <= $this->tolerance) {
            return jsonSuccess(null, '验证成功');
        }

        return jsonError('验证失败，请重试');
    }

    private function createBackground()
    {
        $img = imagecreatetruecolor($this->bgWidth, $this->bgHeight);

        $c1r = rand(100, 200); $c1g = rand(100, 200); $c1b = rand(100, 200);
        $c2r = rand(100, 200); $c2g = rand(100, 200); $c2b = rand(100, 200);

        for ($x = 0; $x < $this->bgWidth; $x++) {
            $ratio = $x / $this->bgWidth;
            $r = (int)($c1r * (1 - $ratio) + $c2r * $ratio);
            $g = (int)($c1g * (1 - $ratio) + $c2g * $ratio);
            $b = (int)($c1b * (1 - $ratio) + $c2b * $ratio);
            $color = imagecolorallocate($img, $r, $g, $b);
            imageline($img, $x, 0, $x, $this->bgHeight, $color);
        }

        for ($i = 0; $i < 15; $i++) {
            $cx = rand(0, $this->bgWidth);
            $cy = rand(0, $this->bgHeight);
            $cr = rand(20, 60);
            $cc = imagecolorallocatealpha($img, rand(0, 255), rand(0, 255), rand(0, 255), rand(40, 100));
            imagefilledellipse($img, $cx, $cy, $cr * 2, $cr * 2, $cc);
        }

        for ($i = 0; $i < 8; $i++) {
            $x1 = rand(0, $this->bgWidth);
            $y1 = rand(0, $this->bgHeight);
            $x2 = rand(0, $this->bgWidth);
            $y2 = rand(0, $this->bgHeight);
            $lc = imagecolorallocatealpha($img, rand(0, 255), rand(0, 255), rand(0, 255), rand(60, 120));
            imagesetthickness($img, rand(1, 3));
            imageline($img, $x1, $y1, $x2, $y2, $lc);
        }

        return $img;
    }

    private function createPuzzlePath($size, $radius)
    {
        $r = $radius;
        $path = [
            0, 0,
            $size - $r, 0,
        ];
        $cx = $size - $r;
        $cy = $r;
        for ($a = 270; $a >= 180; $a -= 10) {
            $rad = deg2rad($a);
            $path[] = $cx + $r * cos($rad);
            $path[] = $cy + $r * sin($rad);
        }
        $path[] = $size;
        $path[] = $r;
        $path[] = $size;
        $path[] = $size / 2 - $r;
        $cx2 = $size - $r;
        $cy2 = $size / 2;
        for ($a = 0; $a <= 90; $a += 10) {
            $rad = deg2rad($a);
            $path[] = $cx2 + $r * cos($rad);
            $path[] = $cy2 + $r * sin($rad);
        }
        $path[] = $size;
        $path[] = $size;
        $path[] = 0;
        $path[] = $size;

        return $path;
    }

    private function imageToBase64($img)
    {
        ob_start();
        imagepng($img);
        $data = ob_get_clean();
        return 'data:image/png;base64,' . base64_encode($data);
    }
}