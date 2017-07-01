<?php
class upfile
{
    public function UploadPhoto($dir)
    {
        $user = System::load_app_class("UserCheck", "common")->UserInfo;
        System::load_sys_class("SystemAction", "sys", "no");
        System::load_app_class("admin", G_ADMIN_DIR, "no");
        $admin = (admin::StaticCheckAdminInfo() ? 1 : 0);
        $dirs = array("banner", "photo", "user", "goods", "share");

        if (!in_array($dir, $dirs)) {
            _sendmsgjson("status", -1);
            _sendmsgjson("msg", "This directory can't upload the file", 1);
        }

        if (empty($_FILES) || !isset($_FILES["Filedata"])) {
            _sendmsgjson("status", -1);
            _sendmsgjson("msg", "Did not choose to upload files", 1);
        }

        $size = @getimagesize($_FILES["Filedata"]["tmp_name"]);

        if (!$size) {
            _sendmsgjson("status", -1);
            _sendmsgjson("msg", "Data type is not correct", 1);
        }

        $ini = System::load_sys_config("upload");
        System::load_sys_class("upload", "sys", "no");
        upload::upload_config(explode(",", $ini["up_image_type"]), $ini["upimgsize"], $dir);

        if ($_POST["iswatermark"] == "false") {
            $iswatermark = true;
        }
        else {
            $iswatermark = false;
        }

        upload::go_upload($_FILES["Filedata"], $iswatermark);

        if (!upload::$ok) {
            _sendmsgjson("status", -1);
            _sendmsgjson("msg", upload::$error, 1);
        }

        switch ($dir) {
        case "user":
            upload::thumbs(300, 300, true);
            break;

        case "goods":
            break;
        }

        $url_ban = $dir . "/" . upload::$filedir . "/" . upload::$filename;
        $url_quan = G_UPLOAD_PATH . "/" . $dir . "/" . upload::$filedir . "/" . upload::$filename;
        $this->UpdataSql($dir . "/" . upload::$filedir . "/" . upload::$filename, upload::get_file_time());
        _sendmsgjson("status", 1);
        _sendmsgjson("url_ban", $url_ban);
        _sendmsgjson("url_quan", $url_quan, 1);
    }

    public function PhotoThumbs($type)
    {
        $ini = System::load_sys_config("upload");
        $x = (int) $_POST["x"];
        $y = (int) $_POST["y"];
        $w = (int) $_POST["w"];
        $h = (int) $_POST["h"];
        $point = array("x" => $x, "y" => $y, "w" => $w, "h" => $h);
        $SRC = str_ireplace(G_UPLOAD_PATH . "/", "", $_POST["src"]);
        $IMG = G_UPLOAD . $SRC;

        if (!@getimagesize($IMG)) {
            _sendmsgjson("status", -1);
            _sendmsgjson("msg", "error:not src..", 1);
        }

        if (!file_exists($IMG)) {
            _sendmsgjson("status", -1);
            _sendmsgjson("msg", "error:not src.", 1);
        }

        System::load_sys_class("upload", "sys", "no");

        switch ($type) {
        case "user":
            foreach ($ini["thumb_user"] as $k => $v ) {
                upload::thumbs($k, $v, false, $IMG, $point);
            }

            break;

        case "goods":
            foreach ($ini["thumb_goods"] as $k => $v ) {
                upload::thumbs($k, $v);
            }

            break;
        }

        _sendmsgjson("status", 1);
        _sendmsgjson("src", $SRC);
        _sendmsgjson("msg", "ok", 1);
    }

    public function GetSizeStr($size = 0, $xi = false)
    {
        $maxsize = System::load_sys_config("upload", "upsize");
        if (($maxsize < $size) || ($size < 1)) {
            $size = $maxsize;
        }

        $units = array(3 => "G", 2 => "M", 1 => "KB", 0 => "B");
        $str = "";

        foreach ($units as $i => $unit ) {
            if (0 < $i) {
                $n = ($size / pow(1024, $i)) % pow(1024, $i);
            }
            else {
                $n = $size;
            }

            if ($n != 0) {
                $str .= " $n$unit ";

                if (!$xi) {
                    return $str;
                }
            }
        }

        return $str;
    }

    private function UpdataSql($path = NULL, $time)
    {
        $uid = System::load_app_class("UserCheck", "common")->UserInfo["uid"];
        $db = System::load_app_model("files", "common");
        $db->file_add_record($uid, $path, $time);
    }
}


?>
