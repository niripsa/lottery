<?php
System::load_sys_class( 'model', 'sys', 'no' );
/**
 * 地区 Model
 */
class area_model extends model
{
    /**
     * 构造方法
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     * @param  [param]
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取地区列表
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function get_area_list( $where = "", $field = "*", $order = "", $num = "" )
    {
        $sql = "SELECT " . $field . " FROM `@#_area`";
        $sql .= empty( $where ) ? "" : " WHERE " . $where;
        $sql .= empty( $order ) ? "" : " ORDER BY " . $order;
        if ( ! empty( $num ) && ( strpos( $num, "," ) <= 0 ) ) 
        {
            $num = "0," . $num;
        }

        $sql .= empty( $num ) ? "" : " LIMIT " . $num;
        $res = $this->GetList( $sql );

        if ( ! $res ) 
        {
            return false;
        }

        return $res;
    }

    /**
     * 获取单条数据
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function get_area_one( $where = "", $field = "*" )
    {
        $sql = "SELECT " . $field . " FROM `@#_area`";
        $sql .= empty( $where ) ? "" : " WHERE " . $where;
        $cate = $this->GetOne( $sql );

        if ( ! $cate ) 
        {
            return false;
        }

        return $cate;
    }

    /**
     * 获取地区名称
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function get_area_name( $where = "", $field = "area_name" )
    {
        $sql = "SELECT " . $field . " FROM `@#_area`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $cate = $this->GetOne($sql);

        if (!$cate) {
            return false;
        }

        return $cate[$field];
    }

    /**
     * 添加地区
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function add_area( $data )
    {
        return $this->Insert( 'area', $data );
    }

    /**
     * 修改地区
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function save_area( $data, $where )
    {
        return $this->Update( 'area', $data, $where );
    }

    /**
     * 删除地区
     * @author Yusure  http://yusure.cn
     * @date   2016-09-21
     */
    public function del_area( $where )
    {
        $sql = "DELETE FROM `@#_area` WHERE" . $where;
        return $this->Delete( $sql );
    }
}