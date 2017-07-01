<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class ueditor extends admin
{
    public function __construct()
    {
        parent::__construct();
    }

    public function upimage()
    {
        if (!isset($_POST["pictitle"]) && !isset($_FILES["upfile"])) {
            exit();
        }

        $ini = System::load_sys_config("upload");
        System::load_sys_class("upload", "sys", "no");
        upload::upload_config(explode(",", $ini["up_image_type"]), $ini["upimgsize"], "goods");
        upload::go_upload($_FILES["upfile"], false);
        $title = $_POST["pictitle"];

        if (!upload::$ok) {
            $url = "";
            $title = $title;
            $originalName = "";
            $state = upload::$error;
        }
        else {
            $url = G_UPLOAD_PATH . "/goods/" . upload::$filedir . "/" . upload::$filename;
            $title = $title;
            $originalName = "";
            $state = "SUCCESS";
        }

        echo "{'url':'" . $url . "','title':'" . $title . "','original':'" . $originalName . "','state':'" . $state . "'}";
    }
}
?>
