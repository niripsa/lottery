<?php

class model
{
    protected $db_config       = "";
    protected $db              = "";
    protected $db_setting      = "default";
    protected $tablename       = "";
    static public $db_tablepre = "";
    static public $strtablepre = "";
    public $Autocommit         = "";
    public $sql_log            = array();

    public function __construct()
    {
        if ( empty( $this->db_config ) )
        {
            $this->db_config = System::load_sys_config("database");
        }

        if ( ! isset( $this->db_config[$this->db_setting] ) )
        {
            $this->db_setting = "default";
        }

        $DBTYPE = $this->db_config[$this->db_setting]["type"];
        System::load_sys_class($DBTYPE, "sys", "no");
        self::$strtablepre = System::load_sys_config("system", "tablepre");
        self::$strtablepre = base64_decode(self::$strtablepre);
        self::$db_tablepre = $this->db_config[$this->db_setting]["tablepre"];
        $this->table_name  = $this->db_config[$this->db_setting]["database"];
        $this->db = call_user_func_array("$DBTYPE::GetObject", array($this->db_config[$this->db_setting]));
    }

    final public function GetList($sql, $info = array("type" => 1, "key" => ""))
    {
        if (empty($sql)) {
            return false;
        }

        if (!is_array($info)) {
            return false;
        }

        $sql = self::replacesql($sql);
        $this->db->execute($sql);
        $type = (isset($info["type"]) ? $info["type"] : 1);
        $key  = (isset($info["key"]) ? $info["key"] : "");
        return $this->db->get_fetch_type($type, $key);
    }

    final public function GetOne($sql, $info = array("type" => 1))
    {
        if (empty($sql)) {
            return false;
        }

        if (!is_array($info)) {
            return false;
        }

        $type = (isset($info["type"]) ? $info["type"] : 1);
        $sql = self::replacesql($sql);
        return $this->db->get_one($this->db->execute($sql), $type);
    }

    final public function GetPage($sql, $info = array("type" => 1, "key" => ""))
    {
        if (empty($sql)) {
            return false;
        }

        if (!is_array($info)) {
            return false;
        }

        $page = (intval($info["page"]) ? intval($info["page"]) : 1);

        if ($page <= 0) {
            $page = 1;
        }

        $sql   = self::replacesql($sql);
        $sql   = str_ireplace("limit", "limit", $sql);
        $sql   = explode("limit", $sql);
        $sql   = trim($sql[0]);
        $limit = " LIMIT " . (($page - 1) * $num) . "," . $num;
        $sql   = $sql . $limit;
        $this->db->execute($sql);
        $type  = (isset($info["type"]) ? $info["type"] : 1);
        $key   = (isset($info["key"]) ? $info["key"] : "");
        return $this->db->get_fetch_type($type, $key);
    }

    final public function GetDataCustom($sql, $info = array("type" => 1, "key" => ""))
    {
        if (empty($sql)) {
            return false;
        }

        if (!is_array($info)) {
            return false;
        }

        $page = (intval($info["page"]) ? intval($info["page"]) : 1);

        if ($page <= 0) {
            $page = 1;
        }

        $sql = self::replacesql($sql);
        $num = (!empty($info["num"]) ? intval($info["num"]) : 20);
        $limit = "LIMIT " . (($page - 1) * $num) . "," . $num;
        $sql = str_ireplace("@limit@", $limit, $sql);
        $this->db->execute($sql);
        $type = (isset($info["type"]) ? $info["type"] : 1);
        $key = (isset($info["key"]) ? $info["key"] : "");
        return $this->db->get_fetch_type($type, $key);
    }

    final public function GetCount($sql)
    {
        if (empty($sql)) {
            return false;
        }

        $sql = self::replacesql($sql);
        $sql = preg_replace("/^SELECT (.*) FROM/i", "SELECT COUNT(*) FROM", $sql);
        $lastresult = $this->db->execute($sql);
        return $this->db->num_count($lastresult);
    }

    final public function GetNum($sql)
    {
        if (empty($sql)) {
            return false;
        }

        $sql = self::replacesql($sql);
        $lastresult = $this->db->execute($sql);
        return $this->db->num_rows($lastresult);
    }

    final static private function replacesql($sql)
    {
        return str_ireplace(self::$strtablepre . "_", self::$db_tablepre, trim($sql));
    }

    public function Query($sql)
    {
        if (empty($sql)) {
            return false;
        }

        $sql = self::replacesql($sql);
        $this->db->execute($sql);

        if (defined("G_IN_ADMIN")) {
            preg_match("/^UPDATE|^DELETE/i", $sql, $matches, PREG_OFFSET_CAPTURE);

            if (isset($matches[0][0])) {
                $this->sql_log[] = $sql;
            }
        }

        return $this->db->lastresult;
    }

    public function Delete($sql)
    {
        if (empty($sql)) {
            return false;
        }

        $sql = self::replacesql($sql);
        return $this->db->execute($sql);
    }

    public function GetInsertId()
    {
        return $this->db->insert_id();
    }

    final public function affected_rows($link = NULL)
    {
        return $this->db->affected_rows();
    }

    public function GetVersion($bool = false)
    {
        return $this->db->GetVersion($bool);
    }

    public function __destruct()
    {
    }

    public function sql_and($where)
    {
        if (empty($where)) {
            return false;
        }

        if (!is_array($where)) {
            return false;
        }

        $str = "";

        foreach ($where as $k => $v ) {
            $str .= "`" . $k . "` = '" . $v . "' and ";
        }

        return rtrim($str, " and ");
    }

    public function sql_or($where)
    {
        if (empty($where)) {
            return fasle;
        }

        if (!is_array($where)) {
            return false;
        }

        $str = "";

        foreach ($where as $k => $v ) {
            $str .= "`" . $k . "` = '" . $v . "' or ";
        }

        return rtrim($str, " or ");
    }

    public function sql_insert($arr)
    {
        $keys = $vals = "";

        foreach ($arr as $k => $v ) {
            $keys .= "`" . $k . "`,";
            $vals .= "'" . $v . "',";
        }

        return array("keys" => rtrim($keys, ","), "vals" => rtrim($vals, ","));
    }

    public function Insert($table, $arr)
    {
        $res = false;

        if (!empty($table)) {
            $tmp_sql = $this->array_sql($arr);

            if (!empty($tmp_sql)) {
                $sql = "INSERT INTO `@#_" . $table . "` SET " . $tmp_sql;
                $sql = self::replacesql($sql);

                if ($this->db->execute($sql)) {
                    $res = $this->GetInsertId();

                    if (!$res) {
                        $res = $this->affected_rows();
                    }
                }
            }
        }

        return $res;
    }

    public function Update($table, $arr, $where)
    {
        $res = false;

        if (!empty($table)) {
            $tmp_sql = $this->array_sql($arr);

            if (!empty($tmp_sql)) {
                $sql = "UPDATE `@#_" . $table . "` SET " . $tmp_sql;

                if (!empty($where)) {
                    $sql .= " WHERE " . $where;
                }

                $sql = self::replacesql($sql);

                if ($this->db->execute($sql)) {
                    $res = $this->affected_rows();
                }
            }
        }

        return $res;
    }

    public function data_list( $table, $where = "", $field = "*", $order = "", $num = "" )
    {
        if ( empty( $field ) ) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_" . $table . "`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if ( ! empty( $num ) && (strpos($num, ",") <= 0) ) {
            $num = "0," . $num;
        }

        $sql .= ( empty( $num ) ? "" : " LIMIT " . $num );
        $sql = self::replacesql( $sql );
        $res = $this->GetList( $sql );

        if ( ! $res ) {
            return false;
        }

        return $res;
    }

    public function data_num( $table, $where )
    {
        $sql = "select count(*) as num from `@#_" . $table . "` ";

        if ( ! empty( $where ) ) {
            $sql .= " where " . $where;
        }

        $sql = self::replacesql( $sql );
        $tmp = $this->GetOne( $sql );
        return $tmp["num"];
    }

    public function data_sum($table, $where, $field)
    {
        $sql = "select sum(" . $field . ") as num from `@#_" . $table . "` ";

        if ( ! empty( $where ) ) {
            $sql .= " where " . $where;
        }

        $sql = self::replacesql( $sql );
        $tmp = $this->GetOne( $sql );
        return $tmp["num"];
    }

    public function data_one($table, $where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_" . $table . "` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $sql = self::replacesql($sql);
        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function data_del( $table, $where )
    {
        $sql = "DELETE FROM `@#_" . $table . "` WHERE " . $where;
        $sql = self::replacesql( $sql );
        return $this->Delete( $sql );
    }

    public function array_sql( $arr )
    {
        if ( is_array( $arr ) && (0 < count($arr) ) )
        {
            $str = "";

            foreach ( $arr as $k => $v )
            {
                $str .= ($str == "" ? "`" . $k . "`='" . $v . "'" : ",`" . $k . "`='" . $v . "'");
            }

            return $str;
        }
        else
        {
            return "";
        }
    }

    final public function sql_begin()
    {
        $this->db->sql_begin();
    }

    final public function sql_commit()
    {
        $this->db->sql_commit();
    }

    final public function sql_rollback()
    {
        $this->db->sql_rollback();
    }
}