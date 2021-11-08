<?php

namespace App;

class ImageCollage
{
    private $realWidth;
    private $realHeight;
    private $gridWidth;
    private $gridHeight;
    private $image;

    public function __construct($realWidth, $realHeight, $gridWidth=5, $gridHeight=2)
    {
        $this->realWidth = $realWidth;
        $this->realHeight = $realHeight;
        $this->gridWidth = $gridWidth;
        $this->gridHeight = $gridHeight;

        // create destination image
        $this->image = imagecreatetruecolor($realWidth, $realHeight);

        // set image transparent background
        $transparent = imagecolorallocatealpha($this->image, 0, 0, 0, 127);
        imagefill($this->image, 0, 0, $transparent);
        imagesavealpha($this->image, true);
        imagecolortransparent($this->image, $transparent);
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }

    public function display()
    {
        // display collage on web
        header("Content-type: image/png");
        imagepng($this->image);
    }

    public function saveCollage($name)
    {
        // save collage to file
        ob_start();
        @imagepng($this->image);
        $contents = ob_get_contents();
        ob_end_clean();
        file_put_contents($name, $contents);
    }

    public function putImage($img, $sizeW, $sizeH, $posX, $posY)
    {
        // cell width
        $cellWidth = $this->realWidth / $this->gridWidth;
        $cellHeight = $this->realHeight / $this->gridHeight;

        // conversion of virtual sizes/positions to real ones
        $realSizeW = ceil($cellWidth * $sizeW);
        $realSizeH = ceil($cellHeight * $sizeH);
        $realPosX = ($cellWidth * $posX);
        $realPosY = ($cellHeight * $posY);

        // copying the image
        imagecopyresampled($this->image, $img, $realPosX, $realPosY, 0, 0, $realSizeW, $realSizeH, imagesx($img), imagesy($img));
    }

    public function addPadding($path, $x)
    {
        // get source image and dimensions.
        $src = imagecreatefromstring(file_get_contents($path));
        $src_w = imagesx($src);
        $src_h = imagesy($src);

        // create destination image with dimensions increased from $src for borders.
        $dest_w = $src_w + 10;
        $dest_h = $src_h+ 10;
        $dest = imagecreatetruecolor($dest_w, $dest_h);
        $transparent = imagecolorallocatealpha($dest, 0, 0, 0, 127);
        imagefill($dest, 0, 0, $transparent);
        imagesavealpha($dest, true);

        // copy source image into destination image.
        imagecopy($dest, $src, 5, 5, 0, 0, $src_w, $src_h);

        // save image with added padding
        ob_start();
        imagepng($dest);
        $contents = ob_get_contents();
        ob_end_clean();
        file_put_contents("temp/".$x."_temp.png", $contents);
    }
}