<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class article extends admin
{
    private $db;
    private $model;
    private $cate;
    private $categorys;
    private $models;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
        $this->model = System::load_app_model("article", "common");
        $this->cate = System::load_app_model("cate", "common");
        $cate = $this->cate->get_cate_list("model='2'", "*", "`parentid` ASC,`cateid` ASC");

        foreach ($cate as $v ) {
            $this->categorys[$v["cateid"]] = $v;
        }

        $this->models = $this->cate->get_model();
        $this->ment = array(
            array("lists", "文章管理", ROUTE_M . "/" . ROUTE_C . "/article_list"),
            array("insert", "添加文章", ROUTE_M . "/" . ROUTE_C . "/article_add")
            );
    }

    public function article_add()
    {
        if (isset($_POST["dosubmit"])) {
            $data = $_POST;
            unset($data["dosubmit"]);
            $title_style = "";

            if ($data["title_color"]) {
                $title_style .= "color:" . $data["title_color"] . ";";
            }

            if ($data["title_bold"]) {
                $title_style .= "font-weight:" . $data["title_bold"] . ";";
            }

            unset($data["title_color"]);
            unset($data["title_bold"]);
            unset($data["sub_text_des"]);
            unset($data["sub_text_len"]);
            $data["title_style"] = $title_style;
            $data["content"] = editor_safe_replace($_POST["content"]);
            $data["picarr"] = (isset($_POST["picarr"]) ? serialize(_post("picarr")) : serialize(array()));
            $data["posttime"] = (strtotime($data["posttime"]) ? strtotime($data["posttime"]) : time());
            $data["hit"] = intval($data["hit"]);

            if (empty($data["title"])) {
                _message("标题不能为空");
            }

            if (!$data["cateid"]) {
                _message("栏目不能为空");
            }

            $data["sort"] = 1;
            $res = $this->model->add($data);

            if ($res) {
                _message("文章添加成功");
            }
            else {
                _message("文章添加失败");
            }

            header("Cache-control: private");
        }

        $cateid = intval($this->segment(4));
        $tree = System::load_sys_class("tree");
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;";
        $categoryshtml = "<option value='\$cateid'>\$spacer\$name</option>";
        $tree->init($this->categorys);
        $catehtml = $tree->get_tree(0, $categoryshtml);
        $categoryshtml = "<option value=\"0\">≡ 请选择栏目 ≡</option>" . $catehtml;

        if ($cateid) {
            $cateinfo = $this->db->GetOne("SELECT * FROM `@#_category` WHERE `cateid` = '$cateid' LIMIT 1");

            if (!$cateinfo) {
                _message("参数不正确,没有这个栏目", G_ADMIN_PATH . "/" . ROUTE_C . "/addarticle");
            }

            $categoryshtml .= "<option value=\"" . $cateinfo["cateid"] . "\" selected=\"true\">" . $cateinfo["name"] . "</option>";
        }

        $this->view->tpl("article.insert")->data("categoryshtml", $categoryshtml)->data("ments", $this->ment);
    }

    public function article_edit()
    {
        $id = intval($this->segment(4));
        $info = $this->model->get_article_one("`id`=" . $id);

        if (isset($_POST["dosubmit"])) {
            $data = $_POST;
            unset($data["dosubmit"]);
            $title_style = "";

            if ($data["title_color"]) {
                $title_style .= "color:" . $data["title_color"] . ";";
            }

            if ($data["title_bold"]) {
                $title_style .= "font-weight:" . $data["title_bold"] . ";";
            }

            unset($data["title_color"]);
            unset($data["title_bold"]);
            unset($data["sub_text_des"]);
            unset($data["sub_text_len"]);
            $data["title_style"] = $title_style;
            $data["content"] = editor_safe_replace($_POST["content"]);
            $data["picarr"] = (isset($_POST["picarr"]) ? serialize($_POST["picarr"]) : serialize(array()));
            $data["posttime"] = (strtotime($data["posttime"]) ? strtotime($data["posttime"]) : time());
            $data["hit"] = intval($data["hit"]);

            if (empty($data["title"])) {
                _message("标题不能为空");
            }

            if (!$data["cateid"]) {
                _message("栏目不能为空");
            }

            $res = $this->model->save($data, "`id`='" . $id . "'");

            if ($res) {
                _message("操作成功!");
            }
            else {
                _message("操作失败!");
            }

            header("Cache-control: private");
        }

        if (!$info) {
            _message("参数错误");
        }

        $cateinfo = $this->cate->get_cate_one("`cateid` = '" . $info[cateid] . "'");
        $tree = System::load_sys_class("tree");
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;";
        $categoryshtml = "<option value='\$cateid'>\$spacer\$name</option>";
        $tree->init($this->categorys);
        $categoryshtml = $tree->get_tree(0, $categoryshtml);
        $categoryshtml .= "<option value=\"" . $cateinfo["cateid"] . "\" selected=\"true\">" . $cateinfo["name"] . "</option>";
        $ments = array(
            array("lists", "内容管理", ROUTE_M . "/" . ROUTE_C . "/article_list"),
            array("insert", "添加文章", ROUTE_M . "/" . ROUTE_C . "/article_add")
            );
        $info["picarr"] = unserialize($info["picarr"]);
        $info["posttime"] = date("Y-m-d H:i:s", $info["posttime"]);

        if ($info["title_style"]) {
            if (stripos($info["title_style"], "font-weight:") !== false) {
                $info["title_bold"] = "bold";
            }
            else {
                $info["title_bold"] = "";
            }

            if (stripos($info["title_style"], "color:") !== false) {
                $title_color = explode(";", $info["title_style"]);
                $title_color = explode(":", $title_color[0]);
                $info["title_color"] = $title_color[1];
            }
            else {
                $info["title_color"] = "";
            }
        }
        else {
            $info["title_color"] = "";
            $info["title_bold"] = "";
        }

        $this->view->data("categoryshtml", $categoryshtml);
        $this->view->data("info", $info);
        $this->view->tpl("article.edit")->data("ments", $this->ment);
    }

    public function article_list()
    {
        $cateid = intval($this->segment(4));
        $list_where = "";

        if (!$cateid) {
            $list_where = "1";
        }
        else {
            $list_where = "`cateid` = '$cateid'";
        }

        if (isset($_POST["sososubmit"])) {
            $posttime1 = (!empty($_POST["posttime1"]) ? strtotime(_post("posttime1")) : NULL);
            $posttime2 = (!empty($_POST["posttime2"]) ? strtotime(_post("posttime2")) : NULL);
            $sotype = $_POST["sotype"];
            $sosotext = $_POST["sosotext"];
            if ($posttime1 && $posttime2) {
                if ($posttime2 < $posttime1) {
                    _message("结束时间不能小于开始时间");
                }

                $list_where = "`posttime` > '$posttime1' AND `posttime` < '$posttime2'";
            }

            if ($posttime1 && empty($posttime2)) {
                $list_where = "`posttime` > '$posttime1'";
            }

            if ($posttime2 && empty($posttime1)) {
                $list_where = "`posttime` < '$posttime2'";
            }

            if (empty($posttime1) && empty($posttime2)) {
                $list_where = false;
            }

            if (!empty($sosotext)) {
                if ($sotype == "cateid") {
                    $sosotext = intval($sosotext);

                    if ($list_where) {
                        $list_where .= "AND `cateid` = '$sosotext'";
                    }
                    else {
                        $list_where = "`cateid` = '$sosotext'";
                    }
                }

                if ($sotype == "catename") {
                    $sosotext = htmlspecialchars($sosotext);
                    $info = $this->cate->get_cate_one("`name` = '" . $sosotext . "'", "cateid");
                    if ($list_where && $info) {
                        $list_where .= "AND `cateid` = '{$info["cateid"]}'";
                    }
                    else if ($info) {
                        $list_where = "`cateid` = '{$info["cateid"]}'";
                    }
                    else {
                        $list_where = "1";
                    }
                }

                if ($sotype == "title") {
                    $sosotext = htmlspecialchars($sosotext);
                    $list_where = "`title` like '%" . $sosotext . "%'";
                }

                if ($sotype == "id") {
                    $sosotext = intval($sosotext);
                    $list_where = "`id` = '$sosotext'";
                }
            }
            else if (!$list_where) {
                $list_where = "1";
            }
        }

        $num = 10;
        $total = $this->model->get_article_num($list_where);
        $page = System::load_sys_class("page");

        if (isset($_GET["p"])) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config($total, $num);
        $articlelist = $this->model->get_articles($list_where, "*", "`sort` DESC", $page->setlimit(1));
        $this->view->data("total", $total);
        $this->view->data("categorys", $this->categorys);
        $this->view->data("articlelist", $articlelist);
        $this->view->data("page", $page->show("one", true));
        $this->view->tpl("article.lists")->data("ments", $this->ment);
    }

    public function article_del()
    {
        $id = intval($this->segment(4));
        $res = $this->model->article_del("`id`='" . $id . "'");

        if ($res) {
            echo G_ADMIN_PATH . "/" . ROUTE_C . "/article_list";
        }
        else {
            echo "no";
        }
    }

    public function article_listorder()
    {
        $data = _post("listorders");
        $res = $this->model->article_sort($data);

        if ($res) {
            _message("排序更新成功", G_ADMIN_PATH . "/" . ROUTE_C . "/article_list");
        }
        else {
            _message("操作失败！");
        }
    }
}
?>
