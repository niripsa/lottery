<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class htmlcustom extends admin
{
    public function __construct()
    {
        parent::__construct();

        if ($this->checkchmod()) {
            $this->AddNewFile();
        }
        else {
            exit("caches/caches_codes 不可写");
        }
    }

    public function lists()
    {
        include (G_CACHES . "caches_codes/tag.sys.package.php");

        if (empty($TAG)) {
            $TAG = array("没有任何标签" => "...");
        }

        $this->view->data("TAG", $TAG)->tpl("htmlcustom.lists.tpl");
    }

    private function AddNewFile()
    {
        if (!file_exists(G_CACHES . "caches_codes/tag.sys.package.php")) {
            file_put_contents(G_CACHES . "caches_codes/tag.package.php", "<?php defined('G_IN_SYSTEM') or exit('No permission resources.'); \$TAG = Array(); ?><?php ");
        }
    }

    private function TagParse($text = NULL)
    {
        if (empty($text)) {
            return "";
        }

        $tag = array("{tag:time}" => "{\$TAGVAL['time']}");
        $text = str_ireplace("\$", "\\\$", $text);

        foreach ($tag as $key => $val ) {
            $text = str_ireplace($key, $val, $text);
        }

        return $text;
    }

    public function create()
    {
        if (isset($_POST["submit"])) {
            $tag_name = strtolower($_POST["tag_name"]);
            $tag_val = $this->TagParse(stripslashes($_POST["tag_val"]));
            $tag_des = _strcut(htmlentities($_POST["tag_des"], ENT_NOQUOTES, "utf-8"), 30);

            if (empty($tag_name)) {
                _message("标签名不能为空");
            }

            $this->AddNewFile();
            include (G_CACHES . "caches_codes/tag.sys.package.php");
            $header = "<?php defined('G_IN_SYSTEM') or exit('No permission resources.'); \$TAG = Array(); ?><?php " . PHP_EOL;
            $TAG[$tag_name] = $tag_des;
            $con = "\$TAG = " . var_export($TAG, true);
            $end = ";" . PHP_EOL;
            $val1 = file_put_contents(G_CACHES . "caches_codes/tag.sys.package.php", $header . $con . $end);
            $str = "QWERTYUIOPLKJHGFDSAZXCVBNM";
            $qz = $str[rand(0, 25)] . $str[rand(0, 25)] . $str[rand(0, 25)] . $str[rand(0, 25)];
            $qz .= $str[rand(0, 25)] . $str[rand(0, 25)] . $str[rand(0, 25)] . $str[rand(0, 25)];
            $header = "<?php defined('G_IN_SYSTEM') or exit('No permission resources.'); ?>" . PHP_EOL;
            $header .= "<?php return <<<" . $qz . PHP_EOL;
            $con = $tag_val;
            $end = PHP_EOL . $qz . ";" . PHP_EOL;
            $val2 = file_put_contents(G_CACHES . "caches_codes/tag." . $tag_name . ".php", $header . $con . $end);
            if ((gettype($val1) != "integer") || (gettype($val2) != "integer")) {
                _message("失败");
            }
            else {
                _message("完成", G_ADMIN_PATH . "/htmlcustom/lists");
            }
        }

        $this->checkchmod();

        if (empty($_POST)) {
            $this->view->tpl("htmlcustom.create.tpl");
        }
    }

    public function edit()
    {
        $upkey = $this->segment(4);
        include (G_CACHES . "caches_codes/tag.sys.package.php");

        if (!isset($TAG[$upkey])) {
            _message("没有这个标签");
        }

        $TAG = array("key" => $upkey, "des" => $TAG[$upkey]);
        $TAG["content"] = htmlentities(include (G_CACHES . "caches_codes/tag." . $TAG["key"] . ".php"), ENT_NOQUOTES, "utf-8");

        if (isset($_POST["submit"])) {
            $this->create();
        }
        else if (empty($_POST)) {
            $this->view->data("TAG", $TAG)->tpl("htmlcustom.create.tpl");
        }
    }

    public function del()
    {
        include (G_CACHES . "caches_codes/tag.sys.package.php");
        $key = (isset($_POST["key"]) ? $_POST["key"] : "");

        exit("no");
        $key = (empty($key) ? true : $key);
        $header = "<?php defined('G_IN_SYSTEM') or exit('No permission resources.'); \$TAG = Array(); ?><?php " . PHP_EOL;

        if (isset($TAG[$key])) {
            unset($TAG[$key]);
        }

        $con = "\$TAG = " . var_export($TAG, true);
        $end = ";" . PHP_EOL;
        $val = file_put_contents(G_CACHES . "caches_codes/tag.sys.package.php", $header . $con . $end);

        if (gettype($val) != "integer") {
            exit("删除失败");
        }
        else {
            unlink(G_CACHES . "caches_codes/tag." . $key . ".php");
            echo "yes";
        }
    }

    private function checkchmod($file = NULL)
    {
        if (empty($file)) {
            $file = G_CACHES . "caches_codes";
        }

        if (is_dir($file)) {
            $dir = $file;

            if ($fp = @fopen("$dir/test.txt", "w")) {
                @fclose($fp);
                @unlink("$dir/test.txt");
                $writeable = 1;
            }
            else {
                $writeable = 0;
            }
        }
        else if ($fp = @fopen($file, "a+")) {
            @fclose($fp);
            $writeable = 1;
        }
        else {
            $writeable = 0;
        }

        return $writeable;
    }
}
?>
