<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class sys extends admin
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = System::load_app_model("sys", "common");
        $this->ment = array(
            array("webtongji", "网站统计", ROUTE_M . "/" . ROUTE_C . "/webtongji"),
            array("websitemap", "网站地图", ROUTE_M . "/" . ROUTE_C . "/websitemap")
            );
    }

    public function websubmit()
    {
        $this->view->tpl("sys.websubmit");
    }

    public function webtongji()
    {
        if (file_exists(G_CONFIG . "acc.inc.php")) {
            $acc_code = file_get_contents(G_CONFIG . "acc.inc.php");
            $this->view->data("acc_code", $acc_code);
        }

        if (file_exists(G_CONFIG . "verify.inc.php")) {
            $verify_code = file_get_contents(G_CONFIG . "verify.inc.php");
            $this->view->data("verify_code", $verify_code);
        }

        if (!empty($_POST["dosubmit"])) {
            $data = _post();
            $res = false;
            $rs = false;

            if (!empty($data["acc_code"])) {
                $res = $this->model->account($_POST["acc_code"]);
            }
            else {
                $res = true;
            }

            if (!empty($data["verify_code"])) {
                $rs = $this->model->web_verify($_POST["verify_code"]);
            }
            else {
                $rs = true;
            }

            if ($res && $rs) {
                _message("生成成功");
            }
            else {
                _message("生成失败");
            }
        }

        $this->view->tpl("sys.tongji")->data("ments", $this->ment);
    }

    public function websitemap()
    {
        $link = array(1 => "商品", 2 => "用户", 3 => "文章", 4 => "晒单");
        $this->view->data("link", json_encode($link));

        if (!empty($_POST["dosubmit"])) {
            $data = _post();
            $res = $this->model->get_url($data);

            if ($res) {
                _message("生成成功");
            }
            else {
                _message("生成失败");
            }
        }

        $this->view->tpl("sys.sitemap")->data("ments", $this->ment);
    }

    private function admin_log_cache()
    {
        $this_day_file = "admin.log." . date("Y-m-d") . ".php";
        $path = G_CACHES . "caches_log" . DIRECTORY_SEPARATOR;
        $logs = preg_files("/^admin\.log\.(.*)\.php/i", $path);

        foreach ($logs as $log ) {
            if ($log[0] != $this_day_file) {
                unlink($path . $log[0]);
            }
        }

        $text = "\n" . date("Y-m-d H:i:s") . "更新了全站缓存\n";
        file_put_contents($path . $this_day_file, $text, FILE_APPEND);
        return "管理员操作日志缓存更新成功!<br/>";
    }

    private function uplogscache()
    {
        $path = G_CACHES;
        $errors = preg_files("/^error(.*)\.logs/i", $path);

        foreach ($errors as $f ) {
            if (!is_dir($path . $f[0])) {
                unlink($path . $f[0]);
            }
        }

        return "错误日志缓存更新成功!<br/>";
    }

    private function upfulecache()
    {
        $path = G_CACHES . "caches_upfile" . DIRECTORY_SEPARATOR;

        if (file_exists($path)) {
            $ret = $this->tempdeldir($path);

            if ($ret) {
                mkdir($path, 511, true) || exit("Not Dir");
                chmod($path, 511);
                return "文件缓存更新成功!<br/>";
            }
            else {
                return "文件缓存更新失败!<br/>";
            }
        }
    }

    private function tempcache()
    {
        $path = G_CACHES . "caches_template" . DIRECTORY_SEPARATOR . G_STYLE . DIRECTORY_SEPARATOR;

        if (file_exists($path)) {
            $ret = $this->tempdeldir($path);

            if ($ret) {
                return "模板缓存更新成功!<br/>";
            }
            else {
                return "模板缓存更新失败!<br/>";
            }
        }
    }

    private function tempdeldir($dir)
    {
        $dh = opendir($dir);

        while ($file = readdir($dh)) {
            if (($file != ".") && ($file != "..")) {
                $fullpath = $dir . "/" . $file;

                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                }
                else {
                    $this->tempdeldir($fullpath);
                }
            }
        }

        closedir($dh);

        if (@rmdir($dir)) {
            return true;
        }
        else {
            return false;
        }
    }
}
?>
