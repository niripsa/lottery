<?php

final class template
{
    private $content;
    private $module;
    private $template;

    public function init($filetpl, $filehtml, $ModuleName = "", $TemplateName = "")
    {
        if (!file_exists($filehtml)) {
            exit("模板文件不存在");
        }

        if (file_exists($filetpl) && (filemtime($filehtml) <= filemtime($filetpl))) {
            return NULL;
        }
        else if (!is_dir(dirname($filetpl))) {
            mkdir(dirname($filetpl), 511, true) || exit("Not Dir");
            chmod(dirname($filetpl), 511);
        }

        $this->module = $ModuleName;
        $this->template = $TemplateName;
        $this->content = file_get_contents($filehtml);
        $this->template_parse();

        if (gettype(file_put_contents($filetpl, $this->content)) != "integer") {
            return false;
        }

        return true;
    }

    private function template_parse()
    {
        $stag = base64_decode("d2M=");
        $etag = base64_decode("ZW5k");
        $foreach = base64_decode("bG9vcA==");
        /*$this->content = preg_replace ( "/<\\?php(.*)/i", "&lt;?php\\1",$this->content);
        $this->content = preg_replace ( "/<\\?=(.*)\\?>/is", "&lt;?=\\1&gt;",$this->content);*/
        $this->content = preg_replace("/<script\s+language\s*=\s*php\s*>/is", "<script>", $this->content);
        $this->content = preg_replace("/<script\s+language\s*=\s*[\'|\"]php\s*[\'|\"]\s*>/is", "<script>", $this->content);
        $this->content = preg_replace("/\{$stag:if\s+(.+?)\}/", "<?php if(\\1): ?>", $this->content);
        $this->content = preg_replace("/\{$stag:else\}/", "<?php  else: ?>", $this->content);
        $this->content = preg_replace("/\{$stag:elseif\s+(.+?)\}/", "<?php elseif (\\1): ?>", $this->content);
        $this->content = preg_replace("/\{$stag:if:$etag\}/", "<?php endif; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:page:(\w+)\}/", "<?php echo \$page->show('\\1'); ?>", $this->content);
        $this->content = preg_replace("/\{$stag:page:\\$(\w+)\}/", "<?php echo \$page->\\1; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:$foreach\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2): ?>", $this->content);
        $this->content = preg_replace("/\{$stag:$foreach\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php if(is_array(\\1)) foreach(\\1 AS \\2 => \\3): ?>", $this->content);
        $this->content = preg_replace("/\{$stag:$foreach:$etag\}/", "<?php endforeach; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:fun:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\s*([\+\-\*\/])\s*(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1\\2\\3; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)([\$a-zA-Z_0-9\[\]\'\"$\x7f-\xff]+)\}/es", "\$this->addquote('<?php echo \\1\\2; ?>')", $this->content);
        $this->content = preg_replace("/\{$stag:([a-zA-Z_0-9\[\]\'\"$\x7f-\xff\+\-\*\/]+)\}/es", "\$this->addquote('<?php echo \\1; ?>')", $this->content);
        $this->content = preg_replace("/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)--\}/", "<?php echo \\1--; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)++\}/", "<?php echo \\1++; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*):([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1->\\2; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)::([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1::\\2; ?>", $this->content);
        $this->content = preg_replace("/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1; ?>", $this->content);
        $this->content = preg_replace("/\{$stag:(\w+)\s+([^}]+)\}/ie", "self::pc_tag_html('$1','$2', '$0')", $this->content);
        $this->content = preg_replace("/\{$stag:(\w+):$etag\}/ie", "self::end_html_tag('$1','$0')", $this->content);
        $this->content = preg_replace("/\{$stag:m=(\w+|\w+\.\w+) (mod|echo)=(\w+\((.*)\))([^}]*)\}/ie", "self::pc_module_tag('$1','$3','$4','$2','$5')", $this->content);
        $this->content = preg_replace("/\{$stag:m=(\w+):$etag\}/ie", "self::end_module_tag('$1','$0')", $this->content);
        $this->content = "<?php defined('G_IN_SYSTEM')or exit('No permission resources.'); ?>" . $this->content;
        $this->content = preg_replace("/\{$stag:templates\s+(.+)\}/ie", "self::include_view_r('$1')", $this->content);
    }

    static private function include_view_r($str)
    {
        $html = "<?php ";
        $html .= "include self::includes(";
        $html .= trim(stripslashes($str));
        $html .= "); ?>";
        return $html;
    }

    static private function include_view($str)
    {
        $keys = explode(",", stripslashes($str));
        $mods = array();

        foreach ($keys as $v ) {
            if (strpos($v, "=")) {
                $mod = explode("=", $v);
                $mods[$mod[0]] = $mod[1];
            }
        }

        $html = "<?php ";

        if (isset($mods["key"])) {
            $html .= "\$key = " . $mods["key"] . ";";
        }
        else {
            $html .= "\$key = array();";
        }

        $html .= "include templates(";
        $html .= $keys[0] . ",";
        $html .= $keys[1];

        if (isset($mods["skin"])) {
            $html .= "," . $mods["skin"];
        }

        $html .= ");";

        if (isset($mods["key"])) {
            $html .= "unset(\$key);";
        }

        $html .= " ?>";
        return $html;
    }

    static private function pc_module_tag($op, $mod, $key, $type, $return)
    {
        if (!empty($return)) {
            $return = stripslashes($return);
            $return = explode("=", $return);

            if (isset($return[1])) {
                $return = trim(trim($return[1], "\""));
            }
            else {
                $return = "datas";
            }
        }
        else {
            $return = "datas";
        }

        $mod = stripslashes($mod);
        $op = explode(".", $op);

        if (!isset($op[1])) {
            $op[1] = $op[0];
            $op[0] = "common";
        }

        $module = $op[0];
        $model = $op[1];
        $module_file = "mod_" . $op[0] . "_" . $op[1];

        if ($type == "mod") {
            $html = "<?php \$$module_file = System::load_app_model('$model','$module');\$$return = \$$module_file->$mod; ?>";
        }
        else if ($type == "echo") {
            $html = "<?php \$$module_file = System::load_app_model('$model','$module');echo \$$module_file->$mod; ?>";
        }

        return $html;
    }

    static private function end_module_tag($op, $htmls)
    {
        $op = trim($op);
        return "";
    }

    static private function pc_tag_html($op, $data, $htmls)
    {
        static $display = array("get" => false, "page" => false);
        $oparr = array("getpage", "getlist", "getcount", "getone", "htmlcache", "block");
        $modarr = array("sql", "cache", "mod", "name", "return", "num", "page", "type", "pageurl", "key", "func");
        $datas = array();
        $op = trim($op);
        $html = "";
        preg_match_all("/([a-z]+)\=[\"]?([^\"]+)[\"]?/i", stripslashes($data), $matches, PREG_SET_ORDER);

        foreach ($matches as $v ) {
            $datas[$v[1]] = $v[2];

            if (in_array($v[1], $modarr)) {
                $$v[1] = $v[2];
                continue;
            }
        }

        $datas["mod"] = $mod = (isset($mod) && trim($mod) ? trim($mod) : "get");
        $datas["sql"] = $sql = (isset($sql) && trim($sql) ? trim($sql) : "");
        $datas["num"] = $num = (isset($num) ? intval($num) : 20);

        if ($num <= 0) {
            $datas["num"] = $num = 20;
        }

        $datas["page"] = $page = "\$_GET['p']";
        $datas["pageurl"] = $pageurl = 0;
        $datas["key"] = $key = (isset($key) ? trim($key) : "''");
        $datas["cache"] = $cache = (isset($cache) && intval($cache) ? intval($cache) : 0);
        $datas["return"] = $return = (isset($return) && trim($return) ? trim($return) : "data");
        $datas["name"] = $name = (isset($name) && trim($name) ? trim($name) : 0);
        $datas["type"] = $type = (isset($type) ? trim($type) : 1);

        if (!in_array($type, array("MYSQL_ASSOC", "MYSQL_NUM", "MYSQL_BOTH"))) {
            $type = 1;
        }

        if (!in_array($op, $oparr)) {
            return stripslashes($htmls);
        }

        switch ($op) {
        case "getlist":
            if (empty($sql)) {
                return stripslashes($htmls);
            }

            $html .= "<?php \$" . $return . "=\$this->DB()->GetList(\"" . $sql . "\",array(\"type\"=>" . $type . ",\"key\"=>" . $key . ",\"cache\"=>" . $cache . ")); ?>";
            break;

        case "getpage":
            if (empty($sql)) {
                return stripslashes($htmls);
            }

            $html .= "<?php \$num=" . $num . ";\$total=\$this->DB()->GetCount(\"" . $sql . "\"); ?>";
            $html .= "<?php \$page=System::load_sys_class('page');if(isset(" . $page . ")){\$pagenum=" . $page . ";}else{\$pagenum=1;} \$page->config(\$total,\$num,\$pagenum,\"" . $pageurl . "\"); ?>";
            $html .= "<?php \$" . $return . "=\$this->DB()->GetPage(\"" . $sql . "\",array(\"num\"=>\$num,\"page\"=>\$pagenum,\"type\"=>" . $type . ",\"key\"=>" . $key . ",\"cache\"=>" . $cache . ")); ?>";
            break;

        case "getone":
            if (empty($sql)) {
                return stripslashes($htmls);
            }

            $html .= "<?php \$mysql_model=System::load_sys_class('model'); ?>";
            $html .= "<?php \$" . $return . "=\$this->DB()->GetOne(\"" . $sql . "\",array(\"cache\"=>" . $cache . ")); ?>";
            break;

        case "block":
            $html = "<?php echo include '" . G_CACHES . "caches_codes/tag.$name.php'; ?>";
            break;
        }

        return $html;
    }

    static private function end_html_tag($op, $htmls)
    {
        $op = trim($op);
        $oparr = array("getpage", "getlist", "getcount", "getone", "htmlcache", "block");

        if (!in_array($op, $oparr)) {
            return stripslashes($htmls);
        }

        return "<?php if(defined('G_IN_ADMIN')) {echo '<div style=\"padding:8px;background-color:#F93; color:#fff;border:1px solid #f60;text-align:center\"><b>This Tag</b></div>';}?>";
    }

    static private function pc_tag($op, $data, $html)
    {
        static $display = array("op" => false, "page" => false);
        $datas = array();
        $html = "";
        $modarr = array("mod", "sql", "page", "cache", "return", "one");
        preg_match_all("/([a-z]+)\=[\"]?([^\"]+)[\"]?/i", stripslashes($data), $matches, PREG_SET_ORDER);

        foreach ($matches as $v ) {
            $datas[$v[1]] = $v[2];

            if (in_array($v[1], $modarr)) {
                $$v[1] = $v[2];
                continue;
            }
        }

        $CacheTime = System::load_sys_config("system", "cache");
        $datas["mod"] = $mod = (isset($mod) && trim($mod) ? trim($mod) : "get");
        $datas["sql"] = $sql = (isset($sql) && trim($sql) ? trim($sql) : "");
        $datas["page"] = $page = (isset($page) ? true : false);
        $datas["cache"] = $cache = (isset($cache) && intval($cache) ? intval($cache) : intval($CacheTime));
        $datas["return"] = $return = (isset($return) && trim($return) ? trim($return) : "data");
        $datas["one"] = $one = (isset($one) && trim($one) ? true : false);

        if (!file_exists(G_SYSTEM . "model" . DIRECTORY_SEPARATOR . $op . "_tag.class.php")) {
            exit("Model File Error: File Not Found");
        }

        if ($page && !$display["page"]) {
            $display["page"] = true;
            $html .= "<?php \$page=System::load_sys_class(\"page\"); ?>";
        }

        $html .= "<?php ";
        if ($op && !$display["op"]) {
            $display["op"] = true;
            $html .= "\$" . $op . "_tag";
            $html .= "=System::load_app_model(\"" . $op . "_tag\");";
        }

        if ($op) {
            $html .= "if(method_exists(\$" . $op . "_tag,\"" . $mod . "\")){";
            $html .= "\$" . $return . "=\$" . $op . "_tag->" . $mod . "(" . self::arr_to_html($datas) . ");} ?>";
        }

        return $html;
    }

    static private function end_pc_tag()
    {
        return "<?php if(defined('G_IN_ADMIN')) {echo '<div style=\"padding:8px;background-color:#F93; color:#fff;border:1px solid #f60;text-align:center\"><b>This Tag</b></div>';}?>";
    }

    public function addquote($var)
    {
        return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
    }

    static private function arr_to_html($data)
    {
        if (is_array($data)) {
            $str = "array(";

            foreach ($data as $key => $val ) {
                if (is_array($val)) {
                    $str .= "'$key'=>" . self::arr_to_html($val) . ",";
                }
                else if (strpos($val, "\$") === 0) {
                    $str .= "'$key'=>$val,";
                }
                else if ($key == "sql") {
                    $str .= "'$key'=>\"" . $val . "\",";
                }
                else {
                    $str .= "'$key'=>'" . new_addslashes($val) . "',";
                }
            }

            return $str . ")";
        }

        return false;
    }
}


?>
