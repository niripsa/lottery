<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class( "admin", G_ADMIN_DIR, "no" );
/**
 * 地区管理 控制器
 */
class area extends admin
{
    private $db;
    private $model;
    private $ment;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
        $this->model = System::load_app_model( "area", "common" );
        $this->ment = array(
            array( 'area_list', '地区列表', ROUTE_M . '/' . ROUTE_C . '/area_list' ),
            array( 'area_add',  '添加地区', ROUTE_M . '/' . ROUTE_C . '/area_add' )
        );
    }


    /**
     * 地区列表
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     * @param  [param]
     * @return [type]     [description]
     */
    public function area_list()
    {
        $area_list = $this->model->get_area_list( '', "*", "`area_sort` ASC" );

        foreach ( $area_list as $v )
        {
            $v["addsun"]   = G_ADMIN_PATH . '/' . ROUTE_C . '/area_add/';
            $v["editcate"] = G_ADMIN_PATH . '/' . ROUTE_C . '/area_edit/';
            $v["delcate"]  = G_ADMIN_PATH . '/' . ROUTE_C . '/area_del/';
            $categorys[$v['area_id']] = $v;
        }

        $tree = System::load_sys_class( 'tree' );
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        $tree->pid  = 'area_parent_id';
        $html = "\t\t\t<tr>\r\n            <td align='center'><input name='listorders[\$area_id]' type='text' size='3' value='\$area_sort' class='input-text-c'></td>\r\n\t\t\t<td align='left'>\$spacer\$area_name</th>\r\n            <td align='center'>\r\n                <a href='\$addsun\$area_id'>添加子栏目</a><span class='span_fenge lr5'>|</span>   \r\n\t\t\t\t<a href='\$editcate\$area_id'>修改</a><span class='span_fenge lr5'>|</span>\r\n\t\t\t\t<a href=\\\"javascript:window.parent.Del('\$delcate\$area_id', '确认删除『 \$area_name 』地区？');\\\">删除</a>\r\n            </td>\r\n          </tr>";
        $tree->init( $categorys );
        $html = $tree->get_tree( 0, $html );
        $this->view->data( 'ment', $this->ment);
        $this->view->data( 'html', $html);
        $this->view->tpl( 'area.list' );
    }

    /**
     * 添加地区
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     * @param  [param]
     * @return [type]     [description]
     */
    public function area_add()
    {

        if ( isset( $_POST["info"] ) ) 
        {
            $info    = _post( 'info' );
            if ( empty( $info['area_name'] ) )
            {
                $mesage = "地区名称不能为空";
            }

            if ( ! empty( $mesage ) )
            {
                _message( $mesage, NULL, 3 );
            }
            /* 判断上级的深度，然后+1更新 */
            if ( $info['area_parent_id'] )
            {
                $parent_id = $info['area_parent_id'];
                $parent_info = $this->model->get_area_one("`area_id` = '$parent_id'");
                $info['area_deep'] = $parent_info['area_deep'] + 1;
            }
            else
            {
                $info['area_deep'] = 1;
                $info['area_parent_id'] = 0;
            }

            $res = $this->model->add_area( $info );

            if ( $res ) 
            {
                _message( "地区添加成功!", WEB_PATH . "/" . ROUTE_M . "/area/area_list" );
            }
            else 
            {
                _message( "地区添加失败!" );
            }
        }

        $area_list = $this->model->get_area_list( '', '*', '`area_sort` ASC' );

        foreach ( $area_list as $v )
        {
            $categorys[$v['area_id']] = $v;
        }

        $tree = System::load_sys_class( 'tree' );
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;";
        $tree->pid  = 'area_parent_id';
        $tree->init( $categorys );
        $cate_html = "<option value='\$area_id'>\$spacer\$area_name</option>";
        $cate_html = $tree->get_tree(0, $cate_html);
        $this->view->data( 'categoryshtml', $cate_html);
        $this->view->data( 'ment', $this->ment);
        $this->view->tpl( 'area.add' );
    }

    /**
     * 编辑地区
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function area_edit()
    {
        $area_id = $this->segment( 4 );

        if ( ! intval( $area_id ) ) {
            _message("参数错误");
        }

        $area_info = $this->model->get_area_one("`area_id` = '$area_id'");
        if ( ! $area_info ) {
            _message( '没有这个地区' );
        }

        $area_list = $this->model->get_area_list( '', '*', '`area_sort` ASC' );
        foreach ( $area_list as $v ) 
        {
            $categorys[$v['area_id']] = $v;
        }

        $tree = System::load_sys_class( 'tree' );
        $tree->icon = array("│ ", "├─ ", "└─ ");
        $tree->nbsp = "&nbsp;";
        $tree->pid  = 'area_parent_id';
        $categoryshtml = "<option value='\$area_id'>\$spacer\$area_name</option>";
        $tree->init( $categorys );
        $categoryshtml = $tree->get_tree( 0, $categoryshtml );
        $topinfo = $this->model->get_area_one("`area_id` = '" . $area_info['area_parent_id'] . "'");

        if ( $topinfo ) {
            $categoryshtml .= "<option value='{$topinfo["area_id"]}' selected>≡ {$topinfo["area_name"]} ≡</option>";
        }
        else {
            $categoryshtml .= "<option value='0' selected>≡ 作为一级栏目 ≡</option>";
        }

        $info = array();
        if ( isset( $_POST['info'] ) ) 
        {
            $info = _post( 'info' );
            if ( empty( $info['area_name'] ) ) {
                _message( '地区不能为空' );
            }
            /* 判断上级的深度，然后+1更新 */
            if ( $info['area_parent_id'] )
            {
                $parent_id = $info['area_parent_id'];
                $parent_info = $this->model->get_area_one("`area_id` = '$parent_id'");
                $info['area_deep'] = $parent_info['area_deep'] + 1;
            }
            else
            {
                $info['area_deep'] = 1;
                $info['area_parent_id'] = 0;
            }
            $res = $this->model->save_area( $info, "`area_id`='" . $area_id . "'");
            if ( $res !== false ) 
            {
                _message( "操作成功!", WEB_PATH . "/" . ROUTE_M . "/area/area_list" );
            }
            else 
            {
                _message( "操作失败!" );
            }
        }

        $this->view->data( 'categoryshtml', $categoryshtml );
        $this->view->data( 'ment', $this->ment );
        $this->view->data( 'area_info', $area_info );
        $this->view->tpl( 'area.edit' );
    }

    /**
     * 删除地区
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function area_del()
    {
        $area_id = $this->segment( 4 );

        if ( ! intval( $area_id ) ) 
        {
            exit( 'no' );
        }

        $ids = '';
        $area_deep = $this->model->get_area_name( "`area_id`='$area_id'", 'area_deep' );
        switch ( $area_deep )
        {
            /* 省级 */
            case 1:
                $second_id = $this->model->get_area_list( "`area_parent_id`='$area_id'", 'area_id' );
                if ( $second_id )
                {
                    foreach ( (array)$second_id as $k => $v )
                    {
                        $ids .= ',' . $v['area_id'];
                    }
                    $ids = substr( $ids, 1 );
                    
                    $third_id = $this->model->get_area_list( "`area_parent_id` IN ( '$ids' )", 'area_id' );
                    if ( $third_id )
                    {
                        foreach ( (array)$third_id as $k => $v )
                        {
                            $ids .= ',' . $v['area_id'];
                        }
                    }

                    $ids = $area_id . ',' . $ids;
                }
                
            break;
            /* 市级 */
            case 2:
                $second_id = $this->model->get_area_list( "`area_parent_id`='$area_id'", 'area_id' );
                if ( $second_id )
                {
                    foreach ( (array)$second_id as $k => $v )
                    {
                        $ids .= ',' . $v['area_id'];
                    }
                    $ids = substr( $ids, 1 );
                    $ids = $area_id . ',' . $ids;
                }                
            break;
        }
        
        $ids = $ids ? : $area_id;
        $this->db->Query("DELETE FROM `@#_area` WHERE `area_id` IN ( $ids )");
        if ( $this->db->affected_rows() ) 
        {
            echo WEB_PATH . "/" . ROUTE_M . "/area/area_list/";
        }
        else 
        {
            echo "no";
        }
    }

    /**
     * 排序
     */
    public function listorder()
    {
        if ( $this->segment( 4 ) == 'dosubmit' ) 
        {
            $data = _post( 'listorders' );
            foreach ( $data as $id => $listorder ) 
            {
                $this->model->save_area(array("area_sort" => $listorder), "`area_id` = '" . $id . "'");
            }
            _message( "操作成功!", WEB_PATH . "/" . ROUTE_M . "/area/area_list" );
        }
        else 
        {
            _message("请排序");
        }
    }

    /**
     * 获取子级地区
     * @author Yusure  http://yusure.cn
     * @date   2016-10-13
     * @param  [param]
     * @return [type]     [description]
     */
    public function get_clild()
    {
        $area_id = intval( $this->segment( 4 ) );
        $area_id or json_error( '参数获取失败' );
        $where = 'area_parent_id = ' . $area_id;
        $field = 'area_id, area_name';
        $area_list = $this->model->get_area_list( $where, $field, 'area_sort' );
        if ( $area_list )
        {
            json_success( $area_list );
        }
    }


}