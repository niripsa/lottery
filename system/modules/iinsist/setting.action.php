<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class setting extends admin
{
    private $db;
    public $ments;
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->db    = System::load_sys_class("model");
        $this->model = System::load_app_model("setting", "common");
        $this->ment = array(
            array("base",      "基本配置", ROUTE_M . "/" . ROUTE_C . "/base"),
            array("money",     "返现配置", ROUTE_M . "/" . ROUTE_C . "/money"),
            array("commission", "分销佣金", ROUTE_M . "/" . ROUTE_C . "/commission"),
            array("seo",       "SEO设置", ROUTE_M . "/" . ROUTE_C . "/seo"),
            array("upload",    "上传配置", ROUTE_M . "/" . ROUTE_C . "/upload"),
            array("watermark", "水印配置", ROUTE_M . "/" . ROUTE_C . "/watermark"),
            array("email",     "邮箱配置", ROUTE_M . "/" . ROUTE_C . "/email"),
            array("mobile",    "短信配置", ROUTE_M . "/" . ROUTE_C . "/mobile"),
            array("contact",   "联系方式", ROUTE_M . "/" . ROUTE_C . "/contact"),
            array("pay",       "支付方式", ROUTE_M . "/" . ROUTE_C . "/pay"),
            array("paybank",   "支付银行选择", ROUTE_M . "/" . ROUTE_C . "/pay_bank"),
            array("ship",      "快递公司", ROUTE_M . "/" . ROUTE_C . "/ship"),
            array("domain",    "模块域名绑定", ROUTE_M . "/" . ROUTE_C . "/domain"),
            array("app",       "移动端下载", ROUTE_M . "/" . ROUTE_C . "/app"),
            array("template",  "模板设置", ROUTE_M . "/" . ROUTE_C . "/template")
        );
        $this->ments = $this->headerment($this->ment);
    }

    /**
     * 基本配置
     */
    public function base()
    {
        if ( isset( $_POST["dosubmit"] ) )
        {
            $data = _post();

            if ( empty( $data["cache"] ) )
            {
                $data["lang"] = "zh_cn";
            }

            if ( empty( $data["cache"] ) )
            {
                $data["cache"] = "3600";
            }

            $data["goods_end_time"] = intval( $data["goods_end_time"] );
            if ( ($data["goods_end_time"] < 30) && ($data["goods_end_time"] != 0) )
            {
                $data["goods_end_time"] = 180;
            }

            if ( 300 <= $data["goods_end_time"] )
            {
                $data["goods_end_time"] = 180;
            }

            unset( $data["dosubmit"] );

            if ( ! empty( $data["admindir"] ) )
            {
                $admindir_one = dirname(__FILE__);
                $admindir_two = dirname($admindir_one) . DIRECTORY_SEPARATOR . $data["admindir"];

                if ( $admindir_one !== $admindir_two )
                {
                    $reok = rename($admindir_one, $admindir_two);

                    if ( ! $reok )
                    {
                        $data["admindir"] = NULL;
                    }
                    else
                    {
                        $admindir = System::load_sys_config("system", "admindir");
                        $sql = "UPDATE `@#_ments` SET `m` = '{$data["admindir"]}' WHERE `m` = '$admindir'";
                        $this->model->Query( $sql );
                    }
                }
            }

            $res = $this->model->write_setting( 'base', $data );
            $ok = $this->cfgPut();
            if ( $this->db->affected_rows() && $ok )
            {
                _message("修改成功");
            }
            else
            {
                _message("修改失败");
            }
        }

        $timezone = array("Asia/Shanghai" => "上海", "Asia/Chongqing" => "重庆", "Asia/guangzhou" => "广州", "Asia/Urumqi" => "乌鲁木齐", "Asia/Hong_Kong" => "香港", "Asia/Macao" => "澳门", "sia/Taipei" => "台北", "Asia/Singapore" => "新加坡", "America/Los_Angeles" => "洛杉矶", "Australia/Sydney" => "悉尼", "Europe/Berlin" => "柏林", "Asia/Ho_Chi_Minh" => "胡志明市");
        $web = System::load_sys_config("system");
        $this->view->data("charset", json_encode(array("utf-8" => "UTF-8")));
        $this->view->data("timezone", json_encode($timezone));
        $this->view->data("web", $web);
        $this->view->data("ments", $this->ment);
        $this->view->tpl("setting.base");
    }

    /**
     * 返现配置
     */
    public function money()
    {
        if ( isset( $_POST["dosubmit"] ) )
        {
            $data = _post();
            unset( $data["dosubmit"] );
            $res = $this->model->write_setting( "money", $data );
            _message("修改成功");
        }

        $money = $this->model->ready_setting( "money" );
        $this->view->data( "money", $money );
        $this->view->data( 'ments', $this->ment );
        $this->view->tpl( 'setting.money' );
    }

    /**
     * 分销佣金比例设置
     */
    public function commission()
    {
        if ( isset( $_POST["dosubmit"] ) )
        {
            $data = _post();
            unset( $data["dosubmit"] );
            if ( '100' != $data['commission_1'] + $data['commission_2'] + $data['commission_3'] )
            {
                _message( '比例之和要等于100' );
            }
            $res = $this->model->write_setting( 'commission', $data );
            if ( $res )
            {
                $this->model->cfgPut( 'commission', 'commission' );
            }
            _message( '修改成功' );
        }

        $commission = $this->model->ready_setting( "commission" );
        $this->view->data( "commission", $commission );
        $this->view->data( 'ments', $this->ment );
        $this->view->tpl( 'setting.commission' );
    }

    public function seo()
    {
        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);

            if (!empty($data["robots"])) {
                $rs = $this->model->robots($data["robots"]);
            }

            unset($data["robots"]);
            $res = $this->model->write_setting("seo", $data);

            if ($res) {
                _message("修改成功");
            }
            else {
                _message("修改失败");
            }
        }

        $seo = $this->model->ready_setting("seo");
        $this->view->data("web", $seo);
        $this->view->tpl("setting.seo")->data("ments", $this->ment);
    }

    /**
     * 联系方式
     */
    public function contact()
    {
        if ( isset( $_POST["dosubmit"] ) )
        {
            $data = _post();
            unset( $data["dosubmit"] );
            $res = $this->model->write_setting( "contact", $data );
            _message("修改成功");
        }

        $seo = $this->model->ready_setting("contact");
        $this->view->data("web", $seo);
        $this->view->data("ments", $this->ment);
        $this->view->tpl("setting.contact");
    }

    public function app()
    {
        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $res = $this->model->write_setting("app", $data);

            if ($res) {
                _message("修改成功");
            }
            else {
                _message("修改失败");
            }
        }

        $seo = $this->model->ready_setting("app");
        $this->view->data("web", $seo);
        $this->view->tpl("setting.app")->data("ments", $this->ment);
    }

    public function upload()
    {
        $ini = System::load_sys_config("upload");

        if (isset($_POST["dosubmit"])) {
            unset($_POST["dosubmit"]);
            $data = $_POST;
            $data["thumb_user"] = (empty($data["thumb_user"]) ? $ini["thumb_user"] : json_decode(stripslashes($data["thumb_user"]), 1));
            $data["thumb_goods"] = (empty($data["thumb_goods"]) ? $ini["thumb_goods"] : json_decode(stripslashes($data["thumb_goods"]), 1));
            $HTML = "<?php " . PHP_EOL;
            $HTML .= "\r\n\t\t\t/*\r\n\t\t\t\t上传和水印配置\r\n\t\t\t\t@up_image_type \t\t上传图片类型\r\n\t\t\t\t@up_soft_type\t\t上传附件类型\r\n\t\t\t\t@up_media_type\t\t上传媒体类型\r\n\t\t\t    @upimgsize\t\t\t允许图片最大大小\r\n\t\t\t\t@upfilesize\t\t\t允许附件最大大小\r\n\t\t\t\t@watermark_off\t\t水印开启\r\n\t\t\t\t@watermark_type\t\t水印类型\r\n\t\t\t\t@watermark_condition\t水印添加条件\r\n\t\t\t\t@watermark_text\t\t文本水印配置\r\n\t\t\t\t@watermark_image\t图片水印地址\r\n\t\t\t\t@watermark_position 水印位置\r\n\t\t\t    @watermark_apache\t透明度\r\n\t\t\t    @watermark_good\t\t清晰度\r\n\r\n\t\t\t\t@thumb_user         用户头像缩略图\r\n\t\t\t\t@thumb_goods        商品图片缩略图\r\n\t\t\t*/\r\n\t\t\t" . PHP_EOL;
            $HTML .= "return ";
            $ini = array_merge($ini, $data);
            $data["thumb_user"] = json_encode($ini["thumb_user"]);
            $data["thumb_goods"] = json_encode($ini["thumb_goods"]);
            $res = $this->model->write_setting("upload", $data);

            if (!is_writable(G_CONFIG . "upload.inc.php")) {
                _message("Please chmod  upload.inc.php  to 0777 !");
            }

            $HTML .= var_export($ini, true);
            $HTML .= ";" . PHP_EOL;
            $ok = file_put_contents(G_CONFIG . "upload.inc.php", $HTML);

            if ($ok) {
                _message("write ok.");
            }
            else {
                _message("write error.");
            }
        }

        $ini["thumb_user"] = json_encode($ini["thumb_user"]);
        $ini["thumb_goods"] = json_encode($ini["thumb_goods"]);
        $this->view->data("web", $ini);
        $this->view->tpl("setting.upload")->data("ments", $this->ment);
    }

    public function watermark()
    {
        $ini = System::load_sys_config("upload");

        if (isset($_POST["dosubmit"])) {
            $data = $_POST;
            unset($data["dosubmit"]);
            $HTML = "<?php " . PHP_EOL;
            $HTML .= "\r\n\t\t\t/*\r\n\t\t\t\t上传和水印配置\r\n\t\t\t\t@up_image_type \t\t上传图片类型\r\n\t\t\t\t@up_soft_type\t\t上传附件类型\r\n\t\t\t\t@up_media_type\t\t上传媒体类型\r\n\t\t\t    @upimgsize\t\t\t允许图片最大大小\r\n\t\t\t\t@upfilesize\t\t\t允许附件最大大小\r\n\t\t\t\t@watermark_off\t\t水印开启\r\n\t\t\t\t@watermark_type\t\t水印类型\r\n\t\t\t\t@watermark_condition\t水印添加条件\r\n\t\t\t\t@watermark_text\t\t文本水印配置\r\n\t\t\t\t@watermark_image\t图片水印地址\r\n\t\t\t\t@watermark_position 水印位置\r\n\t\t\t    @watermark_apache\t透明度\r\n\t\t\t    @watermark_good\t\t清晰度\r\n\r\n\t\t\t\t@thumb_user         用户头像缩略图\r\n\t\t\t\t@thumb_goods        商品图片缩略图\r\n\t\t\t*/\r\n\t\t\t" . PHP_EOL;
            $HTML .= "return ";
            $ini = array_merge($ini, $data);
            $data["watermark_text"] = json_encode($data["watermark_text"]);
            $data["watermark_condition"] = json_encode($data["watermark_condition"]);
            $res = $this->model->write_setting("watermark", $data);

            if (!is_writable(G_CONFIG . "upload.inc.php")) {
                _message("Please chmod  upload.inc.php  to 0777 !");
            }

            $HTML .= var_export($ini, true);
            $HTML .= ";" . PHP_EOL;
            $ok = file_put_contents(G_CONFIG . "upload.inc.php", $HTML);

            if ($ok) {
                _message("write ok.");
            }
            else {
                _message("write error.");
            }

            return NULL;
        }

        $this->view->data("web", $ini);
        $this->view->data("watermark_type", json_encode(array("text" => "文本", "image" => "图片")));
        $this->view->tpl("setting.watermark")->data("ments", $this->ment);
    }

    public function template()
    {
        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $res = $this->model->write_setting("template", $data);

            if ($res) {
                _message("修改成功!");
            }
            else {
                _message("修改失败!");
            }
        }

        $data = $this->model->ready_setting("template");
        $this->view->data("web", $data);
        $this->view->tpl("setting.template")->data("ments", $this->ment);
    }

    public function sendconfig()
    {
        $type = System::load_sys_config("send", "type");

        if (isset($_POST["s_type"])) {
            $s_type = abs($_POST["s_type"]);
            if (($s_type == $type) || (3 < $s_type)) {
                _message("更新完成!");
            }

            $html = "<?php return array('type'=>'$s_type'); ?>";

            if (!is_writable(G_CONFIG . "send.inc.php")) {
                exit("send.inc.php 没有写入权限!");
            }

            file_put_contents(G_CONFIG . "send.inc.php", $html);
            _message("更新完成!");
        }

        include $this->tpl(ROUTE_M, "config.send");
    }

    public function domain()
    {
        $domain_str = $this->model->ready_setting("domain", "domain");
        $domain_list = unserialize($domain_str);

        if (empty($domain_list)) {
            $domain_list = array();
        }

        if (isset($_POST["dosubmit"]) && ($_POST["dosubmit"] == "mobile")) {
            $data = _post();
            $domain = str_ireplace("http://", "", trim($data["domain"], "/"));
            $domain_list[$domain] = array();
            $domain_list[$domain]["domain"] = $domain;
            $domain_list[$domain]["type"] = "mobile";
            $domain_list[$domain]["templates"] = _htmtocode($_POST["templates"]);
            $res = $this->model->write_setting("domain", array("domain" => serialize($domain_list)));
            $this->cfgPut("domain", "domian");
            ob_clean();

            if ($res) {
                echo "ok";
            }
            else {
                echo "fail";
            }

            return NULL;
        }

        if (isset($_POST["dosubmit"]) && ($_POST["dosubmit"] != "del")) {
            $data = _post();
            $op = $data["dosubmit"];
            unset($data["dosubmit"]);
            if (!$data["domain"] || !$data["module"]) {
                exit("请正确填写绑定参数!");
            }

            if (array_key_exists($data["edit_key"], $domain_list)) {
                if ($op == "add") {
                    exit("绑定的域名已经被使用!");
                }

                if ($op == "edit") {
                    unset($domain_list[$data["edit_key"]]);
                }
            }

            unset($data["edit_key"]);
            $data["domain"] = str_ireplace("http://", "", trim($data["domain"], "/"));
            $domain_list[$data["domain"]] = $data;
            $domain["domain"] = serialize($domain_list);
            $res = $this->model->write_setting("domain", $domain);
            $this->cfgPut("domain", "domian");
            ob_clean();

            if ($res) {
                echo "ok";
            }
            else {
                echo "fail";
            }

            exit();
        }

        if (isset($_POST["dosubmit"]) && ($_POST["dosubmit"] == "del")) {
            $data = _post();
            unset($data["dosubmit"]);

            if (empty($data["domain"])) {
                exit("操作失败1!");
            }

            unset($domain_list[$data["domain"]]);
            $domain["domain"] = serialize($domain_list);
            $res = $this->model->write_setting("domain", $domain);
            $this->cfgPut("domain", "domian");
            ob_clean();

            if ($res) {
                exit("ok");
            }
            else {
                exit("操作失败2!");
            }
        }

        $this->view->data("domain", $domain_list);
        $this->view->data("ments", $this->ment);
        $this->view->tpl("setting.domain");
    }

    public function cfgPut($module = "base", $filename = "system")
    {
        $cfg = $this->db->GetList("select * from `@#_config` where `modules`='" . $module . "'");

        if ($module == "domain") {
            $HTML = "<?php " . PHP_EOL;
            $HTML .= "return ";
            $data = unserialize($cfg[0]["value"]);

            if (!is_writable(G_CONFIG . "domain.inc.php")) {
                trigger_error("Please chmod  domain.inc.php  to 0777 !", 512);
            }

            $HTML .= var_export($data, true);
            $HTML .= ";" . PHP_EOL;
            return $ok = file_put_contents(G_CONFIG . "domain.inc.php", $HTML);
        }

        $html = "<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.'); \n";
        $html .= "return array( \n";

        foreach ($cfg as $v ) {
            $v["value"] = addslashes($v["value"]);
            $html .= "'{$v["name"]}' => '{$v["value"]}',//{$v["zhushi"]}";
            $html .= "\n";
        }

        $html .= "); \n ?>";

        if (!is_writable(G_CONFIG . $filename . ".inc.php")) {
            _message("Please chmod  " . $filename . "  to 0777 !");
        }

        return $ok = file_put_contents(G_CONFIG . $filename . ".inc.php", $html);
    }

    /**
     * 邮箱配置
     */
    public function email()
    {
        $cesi = $this->segment(4);

        if ( $cesi == "cesi" )
        {
            $youxiang = $this->segment(5);
            $youxiang = str_replace( "|", ".", $youxiang );

            $ok = _sendemail($youxiang, "aaaaa", "后台邮箱配置测试成功", "<b>恭喜你邮箱测试成功</b>", "1", "0");

            if ( $ok == "1" )
            {
                echo "邮件测试成功";
            }
            else
            {
                echo "邮件测试失败";
            }

            exit();
        }

        if ( isset( $_POST["dosubmit"] ) )
        {
            $data = _post();
            unset($data["dosubmit"]);
            $data["nohtml"] = "不支持HTML格式";
            $res = $this->model->write_setting( 'email', $data );

            if ( $res )
            {
                $this->model->cfgPut( 'email', 'email' );
                _message("操作成功");
            }
            else
            {
                _message("操作失败");
            }
        }

        $this->view->data("big", json_encode(array("utf-8" => "UTF-8", "gbk" => "GBK")));
        $info = $this->model->ready_setting("email");
        $this->view->data("info", $info);
        $this->view->tpl("setting.email")->data("ments", $this->ment);
    }

    /**
     * 短信配置
     */
    public function mobile()
    {

        $mobiles = System::load_sys_config("mobile");
        $sendobj = System::load_sys_class("sendmobile");

        if ( isset( $_POST["chg_status"] ) ) {
            $cfg_type = abs( intval( $_POST["interface"] ) );
            $mobiles["cfg_mobile_on"] = $cfg_type;

            if ( ! is_writable( G_CONFIG . "mobile.inc.php" ) ) {
                _message("Please chmod  mobile.ini.php  to 0777 !");
            }

            $html = var_export( $mobiles, true );
            $html = "<?php \n return " . $html . "; \n?>";
            $ok = file_put_contents(G_CONFIG . "mobile.inc.php", $html);

            if ( $ok ) {
                _message("短信开启成功!");
            }
        }

        if ( isset( $_POST["dosubmit"] ) ) {
            $cfg_id                     = trim($_POST["mid"]);
            $cfg_pass                   = trim($_POST["mpass"]);
            $cfg_qianming               = trim(isset($_POST["mqianming"]) ? $_POST["mqianming"] : "");
            $cfg_type                   = abs(intval($_POST["interface"]));
            $mobiles["cfg_mobile_on"]   = $cfg_type;
            $key                        = "cfg_mobile_" . $cfg_type;
            $mobiles[$key]["mid"]       = $cfg_id;
            $mobiles[$key]["mpass"]     = $cfg_pass;
            $mobiles[$key]["mqianming"] = $cfg_qianming;

            if ($cfg_pass == "******") {
                _message("保存需要在输入一次短信密码!!!");
            }

            if (!is_writable(G_CONFIG . "mobile.inc.php")) {
                _message("Please chmod  mobile.ini.php  to 0777 !");
            }

            $html = var_export($mobiles, true);
            $html = "<?php \n return " . $html . "; \n?>";
            $ok = file_put_contents(G_CONFIG . "mobile.inc.php", $html);

            if ($ok) {
                _message("短信配置更新成功!");
            }
        }

        if (isset($_POST["ceshi_submit"])) {
            $_POST["ceshi_haoma"] = trim($_POST["ceshi_haoma"]);
            $_POST["ceshi_con"] = trim($_POST["ceshi_con"]);
            if (empty($_POST["ceshi_con"]) || empty($_POST["ceshi_haoma"])) {
                echo json_encode(array("-1", "内容或者手机号不能为空!"));
                return NULL;
            }

            if (!is_numeric($_POST["ceshi_haoma"])) {
                echo json_encode(array("-1", "手机号不正确!"));
                return NULL;
            }

            $sendok = _sendmobile($_POST["ceshi_haoma"], $_POST["ceshi_con"]);
            echo json_encode($sendok);
            return NULL;
        }

        foreach ( $mobiles as $k => $v ) {
            if ( is_array( $v ) ) {

                $k_t = explode("_", $k);
                $k_t = array_pop($k_t);
                $k_t_fun = "cfg_getdata_" . $k_t;
                // $sendobj->{$k_t_fun}();

                if ( $sendobj->v ) {
                    if ( is_numeric( $sendobj->v ) ) {
                        $mobiles[$k]["mobile_text"] = "<b style='color:#0c0'>短信功能正常</b>,短信还剩余 " . $sendobj->v . " 条";
                    }
                    else {
                        $mobiles[$k]["mobile_text"] = "<b style='color:#0c0'>短信功能正常</b>," . $sendobj->v . "";
                    }
                }
                else {
                    $mobiles[$k]["mobile_text"] = "<b style='color:#ff0000'>短信测试失败</b>,失败原因:" . $sendobj->error;
                }
            }
        }

        $this->view->data("mobiles", $mobiles);
        $this->view->data("ments", $this->ment);
        $this->view->tpl( "setting.mobile" );
    }

    public function mobiles()
    {
        $mobile = array("mid" => "", "mpass" => "");
        $mobile = System::load_sys_config("mobile");
        $cesi = $this->segment(4);

        if (isset($_POST["dosubmit_ceshi"])) {
            $sendobj = System::load_sys_class("sendmobile");
            $_POST["ceshi_haoma"] = trim($_POST["ceshi_haoma"]);
            $_POST["ceshi_con"] = trim($_POST["ceshi_con"]);
            if (empty($_POST["ceshi_con"]) || empty($_POST["ceshi_haoma"])) {
                echo json_encode(array("-1", "内容或者手机号不能为空!"));
                return NULL;
            }

            $ret = $sendobj->mobile_con_check($_POST["ceshi_con"]);

            if ($ret[0] == -1) {
                echo json_encode($ret);
                return NULL;
            }

            if (!is_numeric($_POST["ceshi_haoma"])) {
                echo json_encode(array("-1", "手机号不正确!"));
                return NULL;
            }

            $sendok = _sendmobile($_POST["ceshi_haoma"], $_POST["ceshi_con"]);
            echo json_encode($sendok);
            return NULL;
        }

        if (isset($_POST["dosubmit"])) {
            $cfg_id = trim($_POST["mid"]);
            $cfg_pass = trim($_POST["mpass"]);
            $cfg_qianming = trim(isset($_POST["mqianming"]) ? $_POST["mqianming"] : "");
            $cfg_type = abs(intval($_POST["interface"]));

            if ($cfg_type == 1) {
                $mobile["cfg_mobile_1"]["mid"] = $cfg_id;
                $mobile["cfg_mobile_1"]["mpass"] = $cfg_pass;
                $mobile["cfg_mobile_1"]["mqianming"] = $cfg_qianming;
                $mobile["cfg_mobile_2"]["mid"] = $mobile["cfg_mobile_2"]["mid"];
                $mobile["cfg_mobile_2"]["mpass"] = $mobile["cfg_mobile_2"]["mpass"];
                $mobile["cfg_mobile_2"]["mqianming"] = $mobile["cfg_mobile_2"]["mqianming"];
            }

            if ($cfg_type == 2) {
                $mobile["cfg_mobile_1"]["mid"] = $mobile["cfg_mobile_1"]["mid"];
                $mobile["cfg_mobile_1"]["mpass"] = $mobile["cfg_mobile_1"]["mpass"];
                $mobile["cfg_mobile_1"]["mqianming"] = $mobile["cfg_mobile_1"]["mqianming"];
                $mobile["cfg_mobile_2"]["mid"] = $cfg_id;
                $mobile["cfg_mobile_2"]["mpass"] = $cfg_pass;
                $mobile["cfg_mobile_2"]["mqianming"] = $cfg_qianming;
            }

            $mobile["cfg_mobile_on"] = $cfg_type;

            if (!is_writable(G_CONFIG . "mobile.inc.php")) {
                _message("Please chmod  mobile.ini.php  to 0777 !");
            }

            $html = var_export($mobile, true);
            $html = "<?php \n return " . $html . "; \n?>";
            $ok = file_put_contents(G_CONFIG . "mobile.inc.php", $html);

            if ($ok) {
                _message("短信配置更新成功!");
            }
        }

        $sendmobile = System::load_sys_class("sendmobile");
        $sendmobile->GetBalance();

        if ($sendmobile->error == 1) {
            $text2 = "<b style='color:#0c0'>短信功能正常</b>,短信还剩余 " . $sendmobile->v . " 条";
        }
        else {
            $text2 = "<b style='color:#ff0000'>短信测试失败</b>,失败原因:" . $sendmobile->v;
        }

        $new_mbl = $sendmobile->GetBalance_new();

        if ($new_mbl["id"]) {
            $text1 = "<b style='color:#0c0'>短信功能正常</b>,短信还剩余 " . $new_mbl["id"] . " 条";
        }
        else {
            $text1 = "<b style='color:#ff0000'>短信测试失败</b>,失败原因:" . $new_mbl["err"];
        }

        if (!isset($mobile["cfg_mobile_2"])) {
            $mobile["cfg_mobile_1"] = $mobile["cfg_mobile_2"] = array();
            $mobile["cfg_mobile_2"]["mid"] = $mobile["mid"];
            $mobile["cfg_mobile_2"]["mpass"] = $mobile["mpass"];
            $mobile["cfg_mobile_2"]["mqianming"] = $mobile["mqianming"];
            $mobile["cfg_mobile_1"] = array();
            $mobile["cfg_mobile_1"]["mid"] = "";
            $mobile["cfg_mobile_1"]["mpass"] = "";
            $mobile["cfg_mobile_1"]["mqianming"] = "";
        }

        include $this->tpl(ROUTE_M, "config.mobile");
    }

    public function empower()
    {
        if (isset($_POST["dosubmit"])) {
            $code = (isset($_POST["code"]) ? $_POST["code"] : NULL);

            if ($code == NULL) {
                _message("您输入的授权码格式不正确!");
            }

            $code = strtoupper($code);
//          $check = @fopen("http://www.yungoucms.com/get.php?set_code=$code", "r");

            if (!$check) {
//              _message("您输入的授权码不正确!");
            }

            $html = "\r\n\t\t\t\t<?php\r\n\t\t\t\t\treturn array('code' => '$code');\r\n\t\t\t\t?>\r\n\t\t\t";
            $path = G_CONFIG . "/code.inc.php";
            file_put_contents($path, $html);
            _message("绑定成功");
        }

        $code = System::load_sys_config("code", "code");

        if ($code) {
//          echo "\t\t\t<iframe src=\"http://www.yungoucms.com/get.php?code=$code\" width=\"100%\" height=\"100%\" scrolling=\"no\"  style=\" border:0px;background:#fff; text-align:center\"></iframe>";
        }
        else {
            include $this->tpl(ROUTE_M, "config.empower");
        }
    }

    public function pay()
    {
        $paylist = $this->model->get_payment();
        $this->view->data("paylist", $paylist);
        $this->view->tpl("setting.pay")->data("ments", $this->ment);
    }

    public function pay_bank()
    {
        if (isset($_POST["dosubmit"])) {
            $bank_type = htmlspecialchars($_POST["bank_type"]);
            $data["pay_bank_type"] = $bank_type;
            $q_ok = $this->model->write_setting("base", $data);

            if ($q_ok) {
                _message("操作成功!");
            }
            else {
                _message("操作失败!");
            }
        }

        $bank = $this->model->ready_setting("base", "pay_bank_type");

        if (!$bank) {
            _message("查询失败");
        }

        $paylist = $this->model->get_payment();
        $this->view->data("bank", $bank);
        $this->view->tpl("setting.paybank")->data("ments", $this->ment);
    }

    public function pay_set()
    {
        $payid = intval($this->segment(4));
        $pay = $this->model->get_payment_one("`pay_id` = '" . $payid . "'");

        if (!$pay) {
            _message("参数错误");
        }

        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $res = $this->model->payment_save("`pay_id`='" . $payid . "'", $data);

            if ($res) {
                _message("操作成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/pay");
            }
            else {
                _message("操作失败！");
            }
        }

        $this->view->data("info", $pay);
        $this->view->data("pay_type", json_encode(array(1 => "即时到账", 2 => "担保交易")));
        $this->view->data("pay_mobile", json_encode(array(1 => "pc", 2 => "手机")));
        $this->view->data("ments", $this->ment);
        $this->view->tpl("setting.pay_set");
    }

    public function ship()
    {
        $shiplist = $this->model->get_ship();
        $this->view->data("shiplist", $shiplist);
        $this->view->tpl("setting.ship")->data("ments", $this->ment);
    }

    public function ship_add()
    {
        if (!empty($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $res = $this->model->ship_add($data);

            if ($res) {
                _message("操作成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/ship");
            }
            else {
                _message("操作失败！");
            }

            exit();
        }

        $this->view->tpl("setting.ship_add")->data("ments", $this->ment);
    }

    public function ship_edit()
    {
        $id = $this->segment(4);
        $ship = $this->model->get_ship_one("eid='" . $id . "'");

        if (!empty($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $res = $this->model->ship_save("eid='" . $id . "'", $data);

            if ($res) {
                _message("操作成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/ship");
            }
            else {
                _message("操作失败！");
            }

            exit();
        }

        $this->view->tpl("setting.ship_add")->data("ments", $this->ment)->data("web", $ship);
    }

    public function ship_del()
    {
        $id = $this->segment(4);
        $res = $this->model->ship_del("eid='" . $id . "'");

        if ($res) {
            _message("操作成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/ship");
        }
        else {
            _message("操作失败！");
        }
    }
}
