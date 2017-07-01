<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class cate extends admin
{
    private $db;
    private $model;
    private $model_id;
    private $ment;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
        $this->model = System::load_app_model("cate", "common");
    }

    protected function set_ment($sg)
    {
        $this->model_id = $this->model->get_model_key("table", $sg, "modelid");
        $sg_data = $sg;

        if ($sg == "goods") {
            $this->ment = array(
                array("lists", "商品栏目", ROUTE_M . "/" . ROUTE_C . "/lists/" . $sg_data),
                array("addcate", "添加栏目", ROUTE_M . "/" . ROUTE_C . "/addcate/" . $sg_data)
                );
        }

        if ($sg == "article") {
            $this->ment = array(
                array("lists", "文章栏目", ROUTE_M . "/" . ROUTE_C . "/lists/" . $sg_data),
                array("addcate", "添加栏目", ROUTE_M . "/" . ROUTE_C . "/addcate/" . $sg_data)
                );
        }

        if ($sg == "web") {
            $this->ment = array(
                array("lists", "单页管理", ROUTE_M . "/" . ROUTE_C . "/lists/" . $sg_data),
                array("addcate", "添加单页", ROUTE_M . "/" . ROUTE_C . "/addcate/web")
                );
        }

        if ($sg == "link") {
            $this->ment = array(
                array("lists", "外部链接管理", ROUTE_M . "/" . ROUTE_C . "/lists/" . $sg_data),
                array("addcate", "添加外部链接", ROUTE_M . "/" . ROUTE_C . "/addcate/link")
                );
        }
    }

    public function lists()
    {
        $cate_type = $this->segment(4);
        $this->set_ment($cate_type);
        $cate_where = (empty($cate_type) ? "" : "`model`=" . $this->model_id);
        $cate = $this->model->get_cate_list($cate_where, "*", "`parentid` ASC,`sort` ASC");

        foreach ($cate as $v ) {
            $v["typename"] = cattype($v["model"]);
            $v["modelname"] = (0 < $v["model"] ? $this->model->get_model($v["model"], "name") : "");
            $v["addsun"] = G_ADMIN_PATH . "/" . ROUTE_C . "/addcate/" . $cate_type . "/";
            $v["editcate"] = G_ADMIN_PATH . "/" . ROUTE_C . "/editcate/" . $cate_type . "/";
            $v["delcate"] = G_ADMIN_PATH . "/" . ROUTE_C . "/delcate/" . $cate_type . "/";
            $categorys[$v["cateid"]] = $v;
        }

        $tree = System::load_sys_class("tree");
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $html = "\t\t\t<tr>\r\n            <td align='center'><input name='listorders[\$cateid]' type='text' size='3' value='\$sort' class='input-text-c'></td>\r\n\t\t\t<td align='center'>\$cateid</td>\r\n            <td align='left'>\$spacer\$name</th>\r\n            <td align='center'>\$typename</td>\r\n            <td align='center'>\$modelname</td>\r\n            <td align='center'></td>\r\n\t\t\t<td align='center'>\r\n                <a href='\$addsun\$cateid'>添加子栏目</a><span class='span_fenge lr5'>|</span>   \r\n\t\t\t\t<a href='\$editcate\$cateid'>修改</a><span class='span_fenge lr5'>|</span>\r\n\t\t\t\t<a href=\\\"javascript:window.parent.Del('\$delcate\$cateid', '确认删除『 \$name 』栏目？');\\\">删除</a>\r\n            </td>\r\n          </tr>";
        $tree->init($categorys);
        $html = $tree->get_tree(0, $html);
        $this->view->data("cate_type", $cate_type);
        $this->view->data("ment", $this->ment);
        $this->view->tpl("cate.list")->data("html", $html);
    }

    public function addcate()
    {
        $catetype = $this->segment(4);
        $this->set_ment($catetype);
        $template = "";

        switch ($catetype) {
        case "web":
            $template = "web_";
            break;

        case "link":
            $template = "link_";
            break;
        }

        if (isset($_POST["info"])) {
            $info = _post("info");
            $thumb = _post("thumb");
            $setting = _post("setting");

            switch ($catetype) {
            case "article":
                $setting["thumb"] = $thumb;
                $info["model"] = $this->model_id;
                $setting = serialize($setting);
                $info["channel"] = 0;
                $info["info"] = $setting;
                $info["sort"] = 1;
                $info["typeid"] = 1;

                if (empty($info["name"])) {
                    $mesage = "栏目名不能为空";
                }

                if (empty($info["catdir"])) {
                    $mesage = "英文名不能为空";
                }

                if (!empty($mesage)) {
                    _message($mesage, NULL, 3);
                }

                $res = $this->model->add_cate($info);

                if ($res) {
                    _message("栏目添加成功!", WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $catetype);
                }
                else {
                    _message("栏目添加失败!");
                }

                break;

            case "goods":
                $setting["thumb"] = $thumb;
                $info["model"] = $this->model_id;
                $setting = serialize($setting);
                $info["channel"] = 0;
                $info["info"] = $setting;
                $info["sort"] = 1;

                if (empty($info["name"])) {
                    $mesage = "栏目名不能为空";
                }

                if (empty($info["catdir"])) {
                    $mesage = "英文名不能为空";
                }

                if (!empty($mesage)) {
                    _message($mesage, NULL, 3);
                }

                $res = $this->model->add_cate($info);

                if ($res) {
                    _message("栏目添加成功!", WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $catetype);
                }
                else {
                    _message("栏目添加失败!");
                }

                break;

            case "web":
                $info["model"] = $this->model_id;
                $setting["content"] = base64_encode(editor_safe_replace($_POST["content"]));
                unset($info["content"]);
                $setting = serialize($setting);
                $info["channel"] = 0;
                $info["info"] = $setting;
                $info["sort"] = 1;

                if (empty($info["name"])) {
                    $mesage = "栏目名不能为空";
                }

                if (empty($info["catdir"])) {
                    $mesage = "英文名不能为空";
                }

                if (!empty($mesage)) {
                    _message($mesage, NULL, 3);
                }

                $res = $this->model->add_cate($info);

                if ($res) {
                    _message("栏目添加成功!", WEB_PATH . "/" . ROUTE_M . "/cate/addcate/" . $catetype . "/");
                }
                else {
                    _message("栏目添加失败!");
                }

                break;

            case "link":
                $info["model"] = 4;

                if (empty($info["name"])) {
                    _message("栏目名不能为空");
                }

                if (empty($info["url"])) {
                    _message("地址不能为空");
                }

                $res = $this->model->add_cate($info);

                if ($res) {
                    _message("栏目添加成功!", WEB_PATH . "/" . ROUTE_M . "/cate/lists" . $catetype . "/");
                }
                else {
                    _message("栏目添加失败!");
                }

                break;
            }
        }

        $cate = $this->model->get_cate_list("`model`='" . $this->model_id . "'", "*", "`parentid` ASC,`cateid` ASC");

        foreach ($cate as $v ) {
            $categorys[$v["cateid"]] = $v;
        }

        $tree = System::load_sys_class("tree");
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;";
        $tree->init($categorys);
        $cate_html = "<option value='\$cateid'>\$spacer\$name</option>";
        $cate_html = $tree->get_tree(0, $cate_html);
        $this->view->data("categoryshtml", $cate_html);
        $this->view->data("ment", $this->ment);
        $this->view->tpl("cate." . $template . "add")->data("catetype", $catetype);
    }

    public function editcate()
    {
        $catetype = $this->segment(4);
        $cateid = $this->segment(5);

        switch ($catetype) {
        case "web":
            $template = "web_";
            brank;
        case "link":
            $template = "link_";
            brank;
        }

        $this->set_ment($catetype);

        if (!intval($cateid)) {
            _message("参数错误");
            exit();
        }

        $cateinfo = $this->model->get_cate_one("`cateid` = '$cateid'");
        $model_table = $this->model->get_model($cateinfo["model"], "table");

        if (!$cateinfo) {
            _message("没有这个栏目");
        }

        $cateinfo["info"] = unserialize($cateinfo["info"]);
        $cate = $this->model->get_cate_list("`model`='" . $this->model_id . "'", "*", "`parentid` ASC,`sort` ASC");

        foreach ($cate as $v ) {
            $categorys[$v["cateid"]] = $v;
        }

        $tree = System::load_sys_class("tree");
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;";
        $categoryshtml = "<option value='\$cateid'>\$spacer\$name</option>";
        $tree->init($categorys);
        $categoryshtml = $tree->get_tree(0, $categoryshtml);
        $topinfo = $this->model->get_cate_one("`cateid` = '" . $cateinfo[parentid] . "'");

        if ($topinfo) {
            $categoryshtml .= "<option value='{$topinfo["cateid"]}' selected>≡ {$topinfo["name"]} ≡</option>";
        }
        else {
            $categoryshtml .= "<option value='0' selected>≡ 作为一级栏目 ≡</option>";
        }

        $info = array();

        if (isset($_POST["info"])) {
            $info = _post("info");
            $thumb = _post("thumb");
            $setting = _post("setting");

            switch ($catetype) {
            case "article":
                $info["model"] = $this->model_id;
                unset($info["modelid"]);

                if (empty($info["name"])) {
                    _message("栏目名不能为空");
                }

                if (empty($info["catdir"])) {
                    _message("地址不能为空");
                }

                $setting["thumb"] = $thumb;
                $setting = serialize($setting);
                $info["info"] = $setting;
                unset($setting);
                $res = $this->model->save_cate($info, "`cateid`='" . $cateid . "'");

                if ($res) {
                    _message("操作成功!", WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $catetype . "/");
                }
                else {
                    _message("操作失败!");
                }

                break;

            case "goods":
                $info["model"] = $this->model_id;
                unset($info["modelid"]);

                if (empty($info["name"])) {
                    _message("栏目名不能为空");
                }

                if (empty($info["catdir"])) {
                    _message("地址不能为空");
                }

                $setting["thumb"] = $thumb;
                $setting = serialize($setting);
                $info["info"] = $setting;
                unset($setting);
                $res = $this->model->save_cate($info, "`cateid`='" . $cateid . "'");

                if ($res) {
                    _message("操作成功!", WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $catetype . "/");
                }
                else {
                    _message("操作失败!");
                }

                break;

            case "web":
                if (empty($info["name"])) {
                    _message("栏目名不能为空");
                }

                if (empty($info["catdir"])) {
                    _message("地址不能为空");
                }

                $info["model"] = $this->model_id;
                $setting["thumb"] = $thumb;
                $setting["content"] = base64_encode(editor_safe_replace($_POST["content"]));
                $setting = serialize($setting);
                $info["info"] = $setting;
                $res = $this->model->save_cate($info, "`cateid`='$cateid'");

                if ($res) {
                    _message("操作成功!", WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $catetype . "/");
                }
                else {
                    _message("操作失败!");
                }

                break;

            case "link":
                $info["model"] = $this->model_id;

                if (empty($info["name"])) {
                    _message("栏目名不能为空");
                }

                if (empty($info["url"])) {
                    _message("地址不能为空");
                }

                $res = $this->model->save_cate($info, "`cateid`='$cateid'");

                if ($res) {
                    _message("操作成功!", WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $catetype . "/");
                }
                else {
                    _message("操作失败!");
                }

                break;
            }
        }

        $this->view->data("categoryshtml", $categoryshtml);
        $this->view->data("cateinfo", $cateinfo);
        $this->view->data("ment", $this->ment);
        $this->view->tpl("cate." . $$template . "add")->data("catetype", $catetype);
    }

    public function listorder()
    {
        $cate_type = $this->segment(4);

        if ($this->segment(5) == "dosubmit") {
            $data = _post("listorders");

            foreach ($data as $id => $listorder ) {
                $cateid = $id;
                $this->model->save_cate(array("sort" => $listorder), "`cateid` = '" . $id . "'");
            }

            $info = $this->model->get_cate_one("cateid='" . $cateid . "'");
            _message("排序更新成功", WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $cate_type . "/");
        }
        else {
            _message("请排序");
        }
    }

    public function delcate()
    {
        $cate_type = $this->segment(4);
        $cateid = $this->segment(5);

        if (!intval($cateid)) {
            echo "no";
            exit();
        }

        $info = $this->model->get_cate_one("cateid=" . $cateid);
        $this->db->Query("DELETE FROM `@#_cate` WHERE (`cateid`='$cateid') LIMIT 1");

        if ($this->db->affected_rows()) {
            echo WEB_PATH . "/" . ROUTE_M . "/cate/lists/" . $cate_type . "/";
        }
        else {
            echo "no";
        }
    }
}
?>
