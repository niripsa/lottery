<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class( "admin", G_ADMIN_DIR, "no" );
/**
 * 商家管理 控制器
 */
class shop extends admin
{
    private $db;
    private $model;
    private $ment;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
        $this->model = System::load_app_model( "shop", "common" );
        $this->ment = array(
            array( 'shop_list', '商家列表', ROUTE_M . '/' . ROUTE_C . '/shop_list' ),
            array( 'shop_add',  '查找商家', ROUTE_M . '/' . ROUTE_C . '/shop_search' ),
            array( 'shop_add',  '添加商家', ROUTE_M . '/' . ROUTE_C . '/shop_add' )
        );
    }
    /**
     * 查找商家
     */
    public function shop_search()
    {
        if (isset($_POST["submit"])) {
            $data = _post();
            if (empty($data["sousuo"]) || empty($data["content"])) {
                _message("参数错误");
            }

            $members = array();

            if ($data["sousuo"] == "id") {
                $where = "`uid` = '" . $data["content"] . "'";
            }

            if ($data["sousuo"] == "nickname") {
                $where = "`username` LIKE '%" . $data["content"] . "%'";
            }

            if ($data["sousuo"] == "email") {
                $where = "`email` LIKE '%" . $data["content"] . "%'";
            }

            if ($data["sousuo"] == "mobile") {
                $where = "`mobile` LIKE '%" . $data["content"] . "%'";
                $where .= " or `phone` LIKE '%" . $data["content"] . "%'";
            }

            $members = $this->model->get_user_list($where);
        }

        $this->view->data("ment", $this->ment);
        $this->view->data("data", $data);
        $this->view->data("members", $members);
        $this->view->tpl("shop.select");
    }
    /**
     * 商家列表
     */
    public function shop_list()
    {   
        $sql_where = 'role = 2';
        $num   = 10;
        $total = $this->model->get_count( $sql_where );
        $page  = System::load_sys_class( "page" );

        if ( isset( $_GET["p"] ) ) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }
        
        $page->config( $total, $num );
        $members = $this->model->get_user_list( $sql_where, "*", "uid DESC", $page->setlimit(1) );
        $total = $this->model->get_count( $sql_where );
        $this->view->data( 'ment', $this->ment);
        $this->view->data( 'total', $total);
         $this->view->data("page", $page->show("two"));
        $this->view->data("members", $members);
        $this->view->tpl( 'shop.list' );
    }
   /**
     * 添加商家
     */
    public function shop_add()
    {
        if ( isset( $_POST["submit"] ) ) {
            $data = _post();
            unset( $data["submit"] );

            if ( empty( $data["username"] ) ) {
                _message("用户名不能为空");
                exit();
            }

            if ( empty( $data["password"] ) ) {
                _message("密码不能为空");
                exit();
            }
            else {
                $data["password"] = $this->md10( $data["password"] );
            }

            $data["time"] = time();

            if ( ! empty( $data["email"] ) ) {
                $info = $this->model->get_user_one("`email` = '" . $data["email"] . "'");
            }
            else {
                $info = $this->model->get_user_one("`mobile` = '" . $data["mobile"] . ".");
            }

            if ( $info ) {
                _message("该会员已经存在！");
            }
            $data['role'] = '2';
            $res = $this->model->user_add( $data );

            if ( $res ) {
                _message( "增加成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/shop_list" );
            }
            else {
                _message("增加失败");
            }
        }
        $this->view->data("ments", $this->ment);
        $this->view->tpl("shop.insert");
    }
    /**
     * 密码加密(10遍md5)
     * @author  xuxiaowen
     * @param   string  $password    要转化的密码
     */
    public function md10($password){
        for($i=1;$i<=10;$i++){
            $pass.=$password;
            $password=md5($pass);
        }
        return $password;
    }
        /**
     * 会员修改
     */
    public function modify()
    {
        $uid = intval($this->segment(4));
        $member = $this->model->get_user_one("`uid`='" . $uid . "'");
        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);

            if (empty($data["password"])) {
                unset($data["password"]);
            }
            else {
                $data["password"] = $this->md10( $data["password"] );
            }
            $res = $this->model->user_save("`uid`='" . $uid . "'", $data);

            if ( $res !== false ) {
                _message("修改成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/shop_list");
            }
            else {
                _message("修改失败");
            }
        }
        $this->view->data("ments", $this->ment);
        $this->view->data("member", $member);

        $this->view->tpl("shop.insert");
    }
    public function huifu()
    {
        $uid = intval($this->segment(4));
        $res = $this->model->user_restore($uid);

        if ($res) {
            _message("恢复成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/shop_list");
        }
        else {
            _message("恢复失败");
        }
    }

    public function del_true()
    {
        $uid = intval($this->segment(4));
        $res = $this->model->user_del_true($uid);

        if ($res) {
            _message("删除成功");
        }
        else {
            _message("删除失败");
        }
    }

    public function del()
    {
        $uid = intval($this->segment(4));
        $res = $this->model->user_del($uid);
        if ($res) {
            _message("删除成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/shop_list");
        }
        else {
            _message("删除失败");
        }
    }
}