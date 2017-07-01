<?php
defined("G_IN_SYSTEM") || exit("No permission resources.");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class upfile extends admin
{
    private $uplist;
    private $updown;
    private $upinfo;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
        $code = System::load_sys_config("code", "code");
        $release = System::load_sys_config("version", "release");
        $this->uplist = "http://www.yungoucms.com/plugin/upfile.php?action=uplist&code=" . $code . "&url=" . G_WEB_PATH;
        $this->updown = "http://www.yungoucms.com/plugin/upfile.php?action=updown&code=" . $code . "&url=" . G_WEB_PATH;
        $this->upinfo = "http://www.yungoucms.com/plugin/upfile.php?action=upinfo&code=" . $code . "&url=" . G_WEB_PATH;
    }

    private function get_web_url($urltype = NULL, $release = NULL)
    {
        $url = $this->$urltype .= "&release=" . $release . "&php=" . PHP_VERSION;
        $ctx = stream_context_create(array(
    "http" => array("timeout" => 3)
    ));
        $i = 3;

        while ($i--) {
            $result = @file_get_contents($url, false, $ctx);

            if ($result) {
                break;
            }
        }

        if ($i == 0) {
            return false;
        }

        return $result;
    }

    public function init()
    {
        $stauts = 1;
        $release = System::load_sys_config("version", "release");
        $pathlist = $this->get_web_url("uplist", $release);

        if (!$pathlist) {
            $stauts = -1;
        }

        $this->view->tpl("admin.upfile.tpl")->data("pathlist", json_decode($pathlist))->data("geturl", $this->upinfo);
    }

    public function web()
    {
        $release = System::load_sys_config("version", "release");
        $content = $this->get_web_url("uplist", $release);
        ($content = json_decode($content)) || _message("not upfile");

        foreach ($content as $package ) {
            $zip = $package->zip;
            $zip_path = G_CACHES . "caches_upfile" . DIRECTORY_SEPARATOR . $zip;

            if (!file_exists($zip_path)) {
                @file_put_contents($zip_path, @file_get_contents($this->updown . "&release=" . $package->release));
            }
        }

        System::load_app_class("pclzip", "sys", "no");

        foreach ($content as $package ) {
            $PclZip = new PclZip(G_CACHES . "caches_upfile" . DIRECTORY_SEPARATOR . $package->zip);
            $zip_source_path = G_CACHES . "caches_upfile" . DIRECTORY_SEPARATOR . $package->release;
            $PclZip->extract(PCLZIP_OPT_PATH, $zip_source_path, PCLZIP_OPT_REPLACE_NEWER) || exit("Error : " . $PclZip->errorInfo(true));
            $copy_from = $zip_source_path . DIRECTORY_SEPARATOR;
            $copy_to = G_APP_PATH;
            $this->copyfailnum = 0;
            $this->copydir($copy_from, $copy_to, "cover");

            if (0 < $this->copyfailnum) {
                _message("升级失败");
            }

            if (file_exists($zip_source_path . "/upfile_sql.php")) {
                include_once ($zip_source_path . "/upfile_sql.php");
            }
        }

        _message("升级成功", G_MODULE_PATH . "/upfile/init");
    }

    public function copydir($dirfrom, $dirto, $cover = "")
    {
        if (is_file($dirto)) {
            exit("同名文件无法复制" . $dirto);
        }

        if (!file_exists($dirto)) {
            mkdir($dirto);
        }

        $handle = opendir($dirfrom);

        while (false !== $file = readdir($handle)) {
            if (($file != ".") && ($file != "..")) {
                $filefrom = $dirfrom . $file;
                $fileto = $dirto . $file;

                if (is_dir($filefrom)) {
                    $this->copydir($filefrom . DIRECTORY_SEPARATOR, $fileto . DIRECTORY_SEPARATOR, $cover);
                }
                else if (!empty($cover)) {
                    if (!copy($filefrom, $fileto)) {
                        $this->copyfailnum++;
                        echo "copy" . $filefrom . "to" . $fileto . "failed<br />";
                    }
                }
                else {
                    if ((fileext($fileto) == "html") && file_exists($fileto)) {
                    }
                    else if (!copy($filefrom, $fileto)) {
                        $this->copyfailnum++;
                        echo "copy" . $filefrom . "to" . $fileto . "failed<br />";
                    }
                }
            }
        }
    }

    public function deletedir($dirname)
    {
        $result = false;

        if (!is_dir($dirname)) {
            echo " $dirname is not a dir!";
            exit(0);
        }

        $handle = opendir($dirname);

        while (($file = readdir($handle)) !== false) {
            if (($file != ".") && ($file != "..")) {
                $dir = $dirname . DIRECTORY_SEPARATOR . $file;
                is_dir($dir) ? $this->deletedir($dir) : unlink($dir);
            }
        }

        closedir($handle);
        $result = (rmdir($dirname) ? true : false);
        return $result;
    }
}
?>
