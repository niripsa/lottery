<?php

class upload
{
    static  private $uptype;
    static  public $filedir;
    static  public $uploadpath;
    static  public $filesize;
    static  public $filename;
    static  public $files;
    static  public $error;
    static  public $ok;

    static public function upload_config($type = NULL, $size = NULL, $uploadpath = NULL)
    {
        if (empty($uploadpath)) {
            self::$uploadpath = G_UPLOAD;
        }
        else {
            self::$uploadpath = G_UPLOAD . $uploadpath . "/";
        }

        self::$uptype = (!empty($type) ? $type : System::load_sys_config("upload", "up_image_type"));
        self::$filesize = (!empty($size) ? $size : System::load_sys_config("upload", "upimgsize"));
    }

    static public function get_file_time()
    {
        return @filemtime(self::$uploadpath . self::$filename);
    }
    
    static public function get_file_name(){
        return self::$filename;
    }
    
    static public function go_upload($file, $watermark = false)
    {
        if (!$file) {
            self::$ok = 0;
            return false;
        }

        if (!self::$uptype) {
            self::upload_config();
        }

        self::$files = $file;

        if (!self::set_error()) {
            self::$ok = 0;
            return false;
        }

        $filetype = explode(".", self::$files["name"]);
        $filetype = strtolower(array_pop($filetype));
        $filetype = trim($filetype, ";");
        if (($filetype == "php") || ($filetype == "asp") || ($filetype == "jsp")) {
            self::$error = "注意:上传文件类型不正确!";
            self::$ok = 0;
            return self::$ok;
        }

        if ($filetype == "jpeg") {
            $filetype = "jpg";
        }

        if (in_array($filetype, self::$uptype)) {
            if (is_uploaded_file(self::$files["tmp_name"])) {
                self::$filedir = date("Ymd");
                self::$uploadpath = self::$uploadpath . self::$filedir . "/";

                if (!is_dir(self::$uploadpath)) {
                    if (!mkdir(self::$uploadpath, 511)) {
                        self::$error = "上传失败,请检查文件夹权限mkdir";
                        self::$ok = 0;
                        return self::$ok;
                    }

                    if (!chmod(self::$uploadpath, 511)) {
                        self::$error = "上传失败,请检查文件夹权限chmod";
                        self::$ok = 0;
                        return self::$ok;
                    }
                }

                $rand = substr(md5(microtime(true)), 0, 10);
                self::$filename = $rand . "." . $filetype;
                $error = move_uploaded_file(self::$files["tmp_name"], self::$uploadpath . self::$filename);

                if ($error) {
                    self::$error = "上传成功";
                    self::$ok = 1;
                    if (!$watermark && System::load_sys_config("upload", "watermark_off")) {
                        self::watermark();
                    }
                }
                else {
                    self::$error = "上传失败,请检查文件夹权限";
                    self::$ok = 0;
                }
            }
            else {
                self::$error = "不是上传文件";
                self::$ok = 0;
            }
        }
        else {
            self::$error = "上传文件类型不正确";
            self::$ok = 0;
        }

        return self::$ok;
    }

    static private function set_error()
    {
        if (self::$filesize < self::$files["size"]) {
            self::$error = "文件大小超过了允许上传大小";
            return false;
        }

        switch (self::$files["error"]) {
        case 0:
            self::$error = "上传成功没有错误";
            return true;
            break;

        case 1:
            self::$error = "文件大小超过了ini大小";
            return false;
            break;

        case 2:
            self::$error = "文件大小超过了HTML大小";
            return false;
            break;

        case 3:
            self::$error = "文件只有部分被上传";
            return false;
            break;

        case 4:
            self::$error = "文件没有被上传";
            return false;
            break;

        case 5:
            self::$error = "上传文件大小为0";
            return false;
            break;

        case 6:
            self::$error = "没有找到临时文件夹";
            return false;
            break;

        default:
            return false;
            break;
        }

        return true;
    }

    static public function thumbs($width = NULL, $height = NULL, $fugai = false, $path = NULL, $point = NULL)
    {
        if (!$path) {
            $path = self::$uploadpath . self::$filename;
        }

        if (!file_exists($path)) {
            return false;
        }

        $imgSize = @getimagesize($path);
        $imgType = $imgSize[2];

        if ($point == NULL) {
            $point = array("x" => 0, "y" => 0, "w" => $imgSize[0], "h" => $imgSize[1], "z" => false);
        }
        else {
            $point["z"] = true;
        }

        switch ($imgType) {
        case 1:
            $srcImg = @imagecreatefromgif($path);
            break;

        case 2:
            $srcImg = @imagecreatefromjpeg($path);
            break;

        case 3:
            $srcImg = @imagecreatefrompng($path);
            break;

        case 6:
            $srcImg = self::ImageCreateFromBMP($path);
            break;

        default:
        }

        $width = intval($width);
        $height = intval($height);
        $srcW = $imgSize[0];
        $srcH = $imgSize[1];

        if (!$point["z"]) {
            if ($srcH < $srcW) {
                $width = ($srcW < $width ? $srcW : $width);
                $height = $srcH * ($width / $srcW);
            }
            else {
                $height = ($srcH < $height ? $srcH : $height);
                $width = $srcW * ($height / $srcH);
            }

            if (empty($width) && !empty($height)) {
                $width = $srcW * ($height / $srcH);
            }

            if (!empty($width) && empty($height)) {
                $height = $srcH * ($width / $srcW);
            }
        }

        $targetImg = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($targetImg, 255, 255, 255);
        imagefill($targetImg, 0, 0, $white);
        imagecopyresampled($targetImg, $srcImg, 0, 0, $point["x"], $point["y"], $width, $height, $point["w"], $point["h"]);

        if ($fugai) {
            $tag_name = "";
        }
        else {
            $tag_name = "." . $width . $height . ".jpg";
        }

        $imgType = 2;

        switch ($imgType) {
        case 1:
            imagegif($targetImg, $path . $tag_name);
            break;

        case 2:
            imagejpeg($targetImg, $path . $tag_name);
            break;

        case 3:
            imagepng($targetImg, $path . $tag_name);
            break;

        default:
            imagejpeg($targetImg, $path . $tag_name);
            break;
        }

        imagedestroy($srcImg);
        imagedestroy($targetImg);
        return 1;
    }

    static public function watermark($bgimg = NULL, $type = NULL, $content = NULL, $minsize = NULL, $pos = NULL)
    {
        $bgimg = (!empty($bgimg) ? $bgimg : self::$uploadpath . self::$filename);
        $type = (!empty($type) ? $type : System::load_sys_config("upload", "watermark_type"));
        $minsize = (!empty($minsize) ? $minsize : System::load_sys_config("upload", "watermark_condition"));
        $pos = (!empty($pos) ? $pos : System::load_sys_config("upload", "watermark_position"));

        if (file_exists(!$bgimg)) {
            return false;
        }

        $bgimg_info = getimagesize($bgimg);
        $bg_height = $bgimg_info[1];
        $bg_width = $bgimg_info[0];

        switch ($bgimg_info[2]) {
        case 1:
            $from_bgimg = imagecreatefromgif($bgimg);
            break;

        case 2:
            $from_bgimg = imagecreatefromjpeg($bgimg);
            break;

        case 3:
            $from_bgimg = imagecreatefrompng($bgimg);
            break;

        case 4:
            $from_bgimg = self::ImageCreateFromBMP($bgimg);
            break;

        default:
            break;
        }

        if (($bg_width < $minsize["width"]) || ($bg_height < $minsize["height"])) {
            return false;
        }

        imagealphablending($from_bgimg, true);

        if ($type == "text") {
            if (!is_array($content)) {
                $content = System::load_sys_config("upload", "watermark_text");
            }

            $temp = imagettfbbox($content["size"], 0, $content["font"], $content["text"]);
            $markwidth = $temp[2] - $temp[6];
            $markheight = $temp[3] - $temp[7];
            unset($temp);

            switch ($pos) {
            case "lt":
                $pos_x = 10;
                $pos_y = $markheight;
                break;

            case "t":
                $pos_x = ($bg_width - $markwidth) / 2;
                $pos_y = $markheight;
                break;

            case "rt":
                $pos_x = $bg_width - $markwidth - 10;
                $pos_y = $markheight;
                break;

            case "r":
                $pos_x = $bg_width - $markwidth - 10;
                $pos_y = ($bg_height + ($markheight / 2)) / 2;
                break;

            case "rb":
                $pos_x = $bg_width - $markwidth - 10;
                $pos_y = $bg_height - 10;
                break;

            case "b":
                $pos_x = ($bg_width - $markwidth) / 2;
                $pos_y = $bg_height - 10;
                break;

            case "lb":
                $pos_x = 10;
                $pos_y = $bg_height - $markheight - 10;
                break;

            case "l":
                $pos_x = 10;
                $pos_y = ($bg_height + ($markheight / 2)) / 2;
                break;

            case "c":
                $pos_x = ($bg_width - $markwidth) / 2;
                $pos_y = ($bg_height + ($markheight / 2)) / 2;
                break;

            case "s":
                $pos_x = rand(0, $bg_width - $markwidth - 10);
                $pos_y = rand($markheight, $bg_height - 10);
                break;

            default:
                $pos_x = rand(0, $bg_width - $markwidth - 10);
                $pos_y = rand($markheight, $bg_height - 10);
                break;
            }

            if (!empty($content["color"]) && (strlen($content["color"]) == 7)) {
                $R = hexdec(substr($content["color"], 1, 2));
                $G = hexdec(substr($content["color"], 3, 2));
                $B = hexdec(substr($content["color"], 5));
            }
            else {
                if (!empty($content["color"]) && (strlen($content["color"]) == 3)) {
                    $R = hexdec(substr($content["color"], 1, 1));
                    $G = hexdec(substr($content["color"], 2, 2));
                    $B = hexdec(substr($content["color"], 3, 3));
                }
                else {
                    $R = "00";
                    $G = "00";
                    $B = "00";
                }
            }

            $color_qg = imagecolorallocate($from_bgimg, $R, $G, $B);
            imagettftext($from_bgimg, $content["size"], 0, $pos_x, $pos_y, $color_qg, $content["font"], $content["text"]);
        }

        if ($type == "image") {
            if (empty($content)) {
                $content = G_UPLOAD . System::load_sys_config("upload", "watermark_image");
            }

            $markimg_info = getimagesize($content);
            $markheight = $markimg_info[1];
            $markwidth = $markimg_info[0];

            switch ($markimg_info[2]) {
            case 1:
                $from_markimg = imagecreatefromgif($content);
                break;

            case 2:
                $from_markimg = imagecreatefromjpeg($content);
                break;

            case 3:
                $from_markimg = imagecreatefrompng($content);
                break;

            case 4:
                $from_markimg = self::ImageCreateFromBMP($content);
                break;

            default:
                break;
            }

            switch ($pos) {
            case "lt":
                $pos_x = 0;
                $pos_y = 0;
                break;

            case "t":
                $pos_x = ($bg_width - $markwidth) / 2;
                $pos_y = 0;
                break;

            case "rt":
                $pos_x = $bg_width - $markwidth;
                $pos_y = 0;
                break;

            case "r":
                $pos_x = $bg_width - $markwidth;
                $pos_y = ($bg_height - $markheight) / 2;
                break;

            case "rb":
                $pos_x = $bg_width - $markwidth;
                $pos_y = $bg_height - $markheight;
                break;

            case "b":
                $pos_x = ($bg_width - $markwidth) / 2;
                $pos_y = $bg_height - $markheight;
                break;

            case "lb":
                $pos_x = 0;
                $pos_y = $bg_height - $markheight;
                break;

            case "l":
                $pos_x = 0;
                $pos_y = ($bg_height - $markheight) / 2;
                break;

            case "c":
                $pos_x = ($bg_width - $markwidth) / 2;
                $pos_y = ($bg_height - $markheight) / 2;
                break;

            case "s":
                $pos_x = rand(0, $bg_width - $markwidth);
                $pos_y = rand(0, $bg_height - $markheight);
                break;

            default:
                $pos_x = rand(0, $bg_width - $markwidth);
                $pos_y = rand(0, $bg_height - $markheight);
                break;
            }

            imagecopy($from_bgimg, $from_markimg, $pos_x, $pos_y, 0, 0, $markwidth, $markheight);
        }

        switch ($bgimg_info[2]) {
        case 1:
            imagegif($from_bgimg, $bgimg);
            break;

        case 2:
            imagejpeg($from_bgimg, $bgimg, 100);
            break;

        case 3:
            imagepng($from_bgimg, $bgimg);
            break;

        case 4:
            imagewbmp($from_bgimg, $bgimg);
            break;

        default:
            break;
        }

        imagedestroy($from_bgimg);
        return $bgimg;
    }

    static private function ImageCreateFromBMP($filename = NULL)
    {
        if (!$f1 = fopen($filename, "rb")) {
            return false;
        }

        $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1, 14));

        if ($FILE["file_type"] != 19778) {
            return false;
        }

        $BMP = unpack("Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vsize_bitmap/Vhoriz_resolution/Vvert_resolution/Vcolors_used/Vcolors_important", fread($f1, 40));
        $BMP["colors"] = pow(2, $BMP["bits_per_pixel"]);

        if ($BMP["size_bitmap"] == 0) {
            $BMP["size_bitmap"] = $FILE["file_size"] - $FILE["bitmap_offset"];
        }

        $BMP["bytes_per_pixel"] = $BMP["bits_per_pixel"] / 8;
        $BMP["bytes_per_pixel2"] = ceil($BMP["bytes_per_pixel"]);
        $BMP["decal"] = ($BMP["width"] * $BMP["bytes_per_pixel"]) / 4;
        $BMP["decal"] -= floor(($BMP["width"] * $BMP["bytes_per_pixel"]) / 4);
        $BMP["decal"] = 4 - (4 * $BMP["decal"]);

        if ($BMP["decal"] == 4) {
            $BMP["decal"] = 0;
        }

        $PALETTE = array();

        if ($BMP["colors"] < 16777216) {
            $PALETTE = unpack("V" . $BMP["colors"], fread($f1, $BMP["colors"] * 4));
        }

        $IMG = fread($f1, $BMP["size_bitmap"]);
        $VIDE = chr(0);
        $res = imagecreatetruecolor($BMP["width"], $BMP["height"]);
        $P = 0;
        $Y = $BMP["height"] - 1;

        while (0 <= $Y) {
            $X = 0;

            while ($X < $BMP["width"]) {
                if ($BMP["bits_per_pixel"] == 32) {
                    $COLOR = unpack("V", substr($IMG, $P, 3));
                    $B = ord(substr($IMG, $P, 1));
                    $G = ord(substr($IMG, $P + 1, 1));
                    $R = ord(substr($IMG, $P + 2, 1));
                    $color = imagecolorexact($res, $R, $G, $B);

                    if ($color == -1) {
                        $color = imagecolorallocate($res, $R, $G, $B);
                    }

                    $COLOR[0] = ($R * 256 * 256) + ($G * 256) + $B;
                    $COLOR[1] = $color;
                }
                else if ($BMP["bits_per_pixel"] == 24) {
                    $COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
                }
                else if ($BMP["bits_per_pixel"] == 16) {
                    $COLOR = unpack("n", substr($IMG, $P, 2));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                }
                else if ($BMP["bits_per_pixel"] == 8) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                }
                else if ($BMP["bits_per_pixel"] == 4) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));

                    if ((($P * 2) % 2) == 0) {
                        $COLOR[1] = $COLOR[1] >> 4;
                    }
                    else {
                        $COLOR[1] = $COLOR[1] & 15;
                    }

                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                }
                else if ($BMP["bits_per_pixel"] == 1) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));

                    if ((($P * 8) % 8) == 0) {
                        $COLOR[1] = $COLOR[1] >> 7;
                    }
                    else if ((($P * 8) % 8) == 1) {
                        $COLOR[1] = ($COLOR[1] & 64) >> 6;
                    }
                    else if ((($P * 8) % 8) == 2) {
                        $COLOR[1] = ($COLOR[1] & 32) >> 5;
                    }
                    else if ((($P * 8) % 8) == 3) {
                        $COLOR[1] = ($COLOR[1] & 16) >> 4;
                    }
                    else if ((($P * 8) % 8) == 4) {
                        $COLOR[1] = ($COLOR[1] & 8) >> 3;
                    }
                    else if ((($P * 8) % 8) == 5) {
                        $COLOR[1] = ($COLOR[1] & 4) >> 2;
                    }
                    else if ((($P * 8) % 8) == 6) {
                        $COLOR[1] = ($COLOR[1] & 2) >> 1;
                    }
                    else if ((($P * 8) % 8) == 7) {
                        $COLOR[1] = $COLOR[1] & 1;
                    }

                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                }
                else {
                    return false;
                }

                imagesetpixel($res, $X, $Y, $COLOR[1]);
                $X++;
                $P += $BMP["bytes_per_pixel"];
            }

            $Y--;
            $P += $BMP["decal"];
        }

        fclose($f1);
        return $res;
    }
}


?>
