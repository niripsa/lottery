<?php
class other_model extends model
{
    public function get_slide($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_slide`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_slide_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_slide` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function slide_add($data = array())
    {
        return $this->Insert("slide", $data);
    }

    public function slide_save($where, $data = array())
    {
        return $this->Update("slide", $data, $where);
    }

    public function slide_del($where)
    {
        $sql = "DELETE FROM `@#_slide` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_link($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_link`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_link_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_link` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function link_add($data = array())
    {
        return $this->Insert("link", $data);
    }

    public function link_save($where, $data = array())
    {
        return $this->Update("link", $data, $where);
    }

    public function link_del($where)
    {
        $sql = "DELETE FROM `@#_link` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_nav($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_nav`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_nav_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_nav` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function nav_add($data = array())
    {
        return $this->Insert("nav", $data);
    }

    public function nav_save($where, $data = array())
    {
        return $this->Update("nav", $data, $where);
    }

    public function nav_del($where)
    {
        $sql = "DELETE FROM `@#_nav` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_template_config()
    {
        $arr = system::load_sys_config("view");
        return $arr;
    }

    public function edit_template_config($key = "", $data = array(), $temp_key = "")
    {
        $templates = system::load_sys_config("view");

        foreach ($data as $k => $v ) {
            $templates[$key][$k] = $v;
            $curr_key = $k;
        }

        if ($temp_key != "") {
            if ($temp_key != $curr_key) {
                unset($templates["templates"][$temp_key]);
            }

            $res = $this->write_template_config($templates, $temp_key, $curr_key);
        }
        else {
            $res = $this->write_template_config($templates);
        }

        return $res;
    }

    private function write_template_config($data, $key_old = "", $key = "")
    {
        $old_templates = system::load_sys_config("view");
        $html = "<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.');";
        $html .= "\n return " . var_export($data, true) . ";";
        $html .= "\n ?>";

        if ($key != "") {
            $old_temp = $old_templates["templates"][$key_old];
            $new_temp = $data["templates"][$key];

            if ($old_temp["html"] != $new_temp["html"]) {
                $rename_html = @rename(G_TEMPLATES . $old_temp["dir"] . DIRECTORY_SEPARATOR . $old_temp["html"], G_TEMPLATES . $old_temp["dir"] . DIRECTORY_SEPARATOR . $new_temp["html"]);

                if (!$rename_html) {
                    echo 1;
                    exit();
                }
            }

            if ($old_temp["dir"] != $new_temp["dir"]) {
                echo G_TEMPLATES . $old_temp["dir"];
                echo "--";
                echo G_TEMPLATES . $new_temp["dir"];
                $rename_dir = @rename(G_TEMPLATES . $old_temp["dir"], G_TEMPLATES . $new_temp["dir"]);

                if (!$rename_dir) {
                    echo 2;
                    exit();
                }
            }
        }

        if (is_writeable(G_CONFIG . "view.inc.php")) {
            $ok = file_put_contents(G_CONFIG . "view.inc.php", $html);
        }

        return $ok;
    }

    public function get_club($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_club`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_club_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_club` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function club_add($data = array())
    {
        return $this->Insert("club", $data);
    }

    public function club_save($where, $data = array())
    {
        return $this->Update("club", $data, $where);
    }

    public function club_del($where)
    {
        $sql = "DELETE FROM `@#_club` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_topic($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_club_post`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_topic_num($where)
    {
        $sql = "select count(*) as num from `@#_club_post` ";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function get_topic_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_club_post` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function topic_save($where, $data = array())
    {
        return $this->Update("club_post", $data, $where);
    }

    public function topic_del($where)
    {
        $sql = "DELETE FROM `@#_club_post` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_ad_pos($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_ad_area`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_ad_pos_num($where)
    {
        $sql = "select count(*) as num from `@#_ad_area` ";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function get_ad_pos_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_ad_area` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function ad_pos_add($data = array())
    {
        return $this->Insert("ad_area", $data);
    }

    public function ad_pos_save($where, $data = array())
    {
        return $this->Update("ad_area", $data, $where);
    }

    public function ad_pos_del($where)
    {
        $sql = "DELETE FROM `@#_ad_area` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_ad($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_ad_contents`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_ad_num($where)
    {
        $sql = "select count(*) as num from `@#_ad_contents` ";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function get_ad_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_ad_contents` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function ad_add($data = array())
    {
        return $this->Insert("ad_contents", $data);
    }

    public function ad_save($where, $data = array())
    {
        return $this->Update("ad_contents", $data, $where);
    }

    public function ad_del($where)
    {
        $sql = "DELETE FROM `@#_ad_contents` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function admin_log_cache()
    {
        $this_day_file = "admin.log." . date("Y-m-d") . ".php";
        $path = G_CACHES . "caches_log" . DIRECTORY_SEPARATOR;
        $logs = Preg_Files("/^admin\.log\.(.*)\.php/i", $path);

        foreach ($logs as $log ) {
            if ($log[0] != $this_day_file) {
                unlink($path . $log[0]);
            }
        }

        $text = "\n" . date("Y-m-d H:i:s") . "更新了全站缓存\n";
        file_put_contents($path . $this_day_file, $text, FILE_APPEND);
        return "管理员操作日志缓存更新成功!<br/>";
    }

    public function uplogscache()
    {
        $path = G_CACHES;
        $errors = Preg_Files("/^error(.*)\.logs/i", $path);

        foreach ($errors as $f ) {
            if (!is_dir($path . $f[0])) {
                unlink($path . $f[0]);
            }
        }

        return "错误日志缓存更新成功!<br/>";
    }

    public function upfulecache()
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

    public function tempcache()
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

System::load_sys_class("model", "sys", "no");

