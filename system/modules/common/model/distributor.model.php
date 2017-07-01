<?php
System::load_sys_class( 'model', 'sys', 'no' );
/**
 * 分销商 Model
 */
class distributor_model extends model
{
    /**
     * 构造方法
     */
    public function __construct()
    {
        parent::__construct();
        $this->table = 'distributor';
    }

    /**
     * 获取列表
     */
    public function get_list( $where = "", $field = "*", $order = "", $num = "" )
    {
        $sql = "SELECT " . $field . " FROM `@#_{$this->table}`";
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
     */
    public function get_info( $where = "", $field = "*" )
    {
        $sql = "SELECT " . $field . " FROM `@#_{$this->table}`";
        $sql .= empty( $where ) ? "" : " WHERE " . $where;
        $cate = $this->GetOne( $sql );
        if ( ! $cate ) 
        {
            return false;
        }

        return $cate;
    }

    /**
     * 获取某字段的值
     */
    public function get_field( $where = "", $field = "" )
    {
        $sql = "SELECT " . $field . " FROM `@#_{$this->table}`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $cate = $this->GetOne( $sql );
        if ( ! $cate )
        {
            return false;
        }

        return $cate[$field];
    }

    /**
     * 获取总数
     */
    public function get_count( $where )
    {
        $sql = "SELECT COUNT(*) AS num FROM `@#_{$this->table}`";
        if ( ! empty( $where ) )
        {
            $sql .= " WHERE " . $where;
        }
        $tmp = $this->GetOne( $sql );
        return $tmp['num'];
    }

    /**
     * 添加信息
     */
    public function add( $data )
    {
        return $this->Insert( $this->table, $data );
    }

    /**
     * 修改
     */
    public function save( $data, $where )
    {
        return $this->Update( $this->table, $data, $where );
    }

    /**
     * 删除
     */
    public function del( $where )
    {
        $sql = "DELETE FROM `@#_{$this->table}` WHERE" . $where;
        return $this->Delete( $sql );
    }
}