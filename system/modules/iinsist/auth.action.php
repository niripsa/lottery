<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class auth extends admin
{
    private $model;
    private $ments;

    public function __construct()
    {
        parent::__construct();
        $this->model = System::load_app_model("auth", "common");
        $this->ments = array(
            array("group", "分组管理", ROUTE_M . "/" . ROUTE_C . "/group"),
            array("group_add", "分组添加", ROUTE_M . "/" . ROUTE_C . "/group_add"),
            array("admin", "管理员列表", ROUTE_M . "/" . ROUTE_C . "/admin"),
            array("admin_add", "管理员添加", ROUTE_M . "/" . ROUTE_C . "/admin_add"),
            array("fun", "功能列表", ROUTE_M . "/" . ROUTE_C . "/fun"),
            array("fun_add", "功能添加", ROUTE_M . "/" . ROUTE_C . "/fun_add")
            );
    }

    public function group()
    {
        $group = $this->model->get_group();
        $this->view->data("data", $group);
        $this->view->tpl("auth.group")->data("ments", $this->ments);
    }

    public function group_add()
    {
        if (!empty($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $res = $this->model->group_add($data);

            if ($res) {
                _message("添加成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/group");
            }
            else {
                _message("添加失败！");
            }
        }

        $this->view->tpl("auth.group_add")->data("ments", $this->ments);
    }

    public function group_edit()
    {
        $id = $this->segment(4);
        $info = $this->model->get_group_one("`gid`='" . $id . "'");

        if (!empty($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $res = $this->model->group_save("`gid`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/group");
            }
            else {
                _message("修改失败！");
            }
        }

        $this->view->data("info", $info);
        $this->view->tpl("auth.group_add")->data("ments", $this->ments);
    }

    public function group_del()
    {
        $id = $this->segment(4);
        $res = $this->model->group_del("`gid`='" . $id . "'");

        if ($res) {
            _message("删除成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/group");
        }
        else {
            _message("删除失败！");
        }
    }

    public function admin()
    {
        $admin = $this->model->get_admin();

        foreach ($admin as &$row ) {
            $tmp = $this->model->get_group_one("`gid`='" . $row["gid"] . "'");
            $row["group"] = $tmp["name"];
        }

        $this->view->data("data", $admin);
        $this->view->tpl("auth.admin")->data("ments", $this->ments);
    }

    public function admin_add()
    {
        $group = $this->model->get_group("disabled=1");

        if (!empty($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            if (empty($data["username"]) || empty($data["pwd"])) {
                _message("添加失败！");
            }

            if ($data["pwd"] != $data["repwd"]) {
                _message("添加失败！");
            }

            $data["userpass"] = md5(md5($data["pwd"]));
            unset($data["pwd"]);
            unset($data["repwd"]);
            $res = $this->model->admin_add($data);

            if ($res) {
                _message("添加成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/admin");
            }
            else {
                _message("添加失败！");
            }
        }

        $this->view->data("group", json_encode(_arr2to1($group, "gid", "name")));
        $this->view->tpl("auth.admin_add")->data("ments", $this->ments);
    }

    public function admin_edit()
    {
        $id = $this->segment(4);
        $info = $this->model->get_admin_one("`mid`='" . $id . "'");
        $group = $this->model->get_group("disabled=1");

        if (!empty($_POST["submit"])) {
            $data = _post();

            if (!$data["gid"]) {
                unset($data["gid"]);
            }

            unset($data["submit"]);

            if (empty($data["username"])) {
                _message("修改失败！");
            }

            if ($data["pwd"] != $data["repwd"]) {
                _message("修改失败！");
            }

            if (!empty($data["pwd"])) {
                $data["userpass"] = md5(md5($data["pwd"]));
            }

            unset($data["pwd"]);
            unset($data["repwd"]);
            $res = $this->model->admin_save("`mid`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/admin");
            }
            else {
                _message("修改失败！");
            }
        }

        $this->view->data("group", json_encode(_arr2to1($group, "gid", "name")));
        $this->view->data("info", $info);
        $this->view->tpl("auth.admin_add")->data("ments", $this->ments);
    }

    public function admin_del()
    {
        $id = $this->segment(4);
        $res = $this->model->admin_del("`mid`='" . $id . "'");

        if ($res) {
            _message("删除成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/admin");
        }
        else {
            _message("删除失败！");
        }
    }

    public function fun()
    {
        $auth = $this->model->get_auth("", "", "pid asc");
        $this->view->data("data", $auth);
        $this->view->tpl("auth.auth")->data("ments", $this->ments);
    }

    public function fun_add()
    {
        $p_fun = $this->model->auth_parent(2);

        if (!empty($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            if (empty($data["name"]) || empty($data["c"]) || empty($data["a"])) {
                _message("添加失败！");
            }

            $res = $this->model->auth_add($data);

            if ($res) {
                _message("添加成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/fun");
            }
            else {
                _message("添加失败！");
            }
        }

        $this->view->data("p_fun", $p_fun);
        $this->view->tpl("auth.auth_add")->data("ments", $this->ments);
    }

    public function fun_edit()
    {
        $id = $this->segment(4);
        $info = $this->model->get_auth_one("`id`='" . $id . "'");
        $p_fun = $this->model->auth_parent(2);

        if (!empty($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            if (empty($data["name"]) || empty($data["c"]) || empty($data["a"])) {
                _message("修改失败！");
            }

            $res = $this->model->auth_save("`id`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/fun");
            }
            else {
                _message("修改失败！");
            }
        }

        $this->view->data("p_fun", $p_fun);
        $this->view->data("info", $info);
        $this->view->tpl("auth.auth_add")->data("ments", $this->ments);
    }

    public function fun_del()
    {
        $id = $this->segment(4);
        $res = $this->model->auth_del("`id`='" . $id . "'");

        if ($res) {
            _message("删除成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/fun");
        }
        else {
            _message("删除失败！");
        }
    }

    /* 会员组权限 */
    public function group_auth()
    {
        $id = $this->segment(4);
        $info = $this->model->get_group_one( "`gid`='" . $id . "'" );

        if ( ! empty( $_POST["submit"] ) )
        {
            $auth = _post("auth");
            $data["ids"]          = "";
            $data["auth_content"] = "";

            foreach ( $auth as $val )
            {
                $tmp = explode( "#", $val );
                $data["ids"] .= ($data["ids"] == "" ? $tmp[0] : "," . $tmp[0]);
                $tmp[1] = ltrim( $tmp[1], "-" );
                $data["auth_content"] .= ($data["auth_content"] == "" ? $tmp[1] : "," . $tmp[1]);
            }

            $res = $this->model->group_save( "`gid`='" . $id . "'", $data );
            if ( $res !== false )
            {
                _message("修改成功！", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/group");
            }
            else
            {
                _message("修改失败！");
            }
        }

        $p_fun = $this->model->auth_parent(3);
        $this->view->data("auth", $p_fun);
        $this->view->data("info", $info);
        $this->view->tpl("auth.group_auth")->data("ments", $this->ments);
    }
}

?>
