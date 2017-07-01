<?php
final class db_mysqli
{
    /**
      * 数据库配置信息
      */
    private $config;
    /**
      * 数据库连接资源句柄
      */
    private $link;
    /**
      * 最后一次查询的资源句柄
      */
    public $lastresult;
    /**
     *  统计数据库查询次数
     */
    public $querycount = 0;
    /**
     * 数据类实例
     */
    static  private $mysqlobj = array();

    private function __construct($configs)
    {
        $this->config = $configs;
        $this->connect();
    }

    public function __destruct()
    {
        $this->close();
    }

    private function __clone()
    {
    }

    private function connect()
    {
        $this->mysql_debug_file = G_CACHES . "caches_sql_log/mysql_debug_" . time() . "_log";
        $this->link = new mysqli($this->config["hostname"], $this->config["username"], $this->config["password"], $this->config["database"]);

        if ($this->GetErrno()) {
            $this->DisplayError("Can not Connect to MySQL server", "hook_mysql_install");
        }

        if ("4.1" < $this->GetVersion(true)) {
            $charset = (isset($this->config["charset"]) ? $this->config["charset"] : "");
            $serverset = ($charset ? "character_set_connection='$charset',character_set_results='$charset',character_set_client=binary" : "");
            $serverset .= ("5.0.1" < $this->GetVersion() ? (empty($serverset) ? "" : ",") . " sql_mode='' " : "");
            $serverset && $this->link->query("SET $serverset");
        }
    }

    public function DisplayError($message = "", $hook = "")
    {
        if ($this->config["debug"]) {
            $html = "<b>MySQL Error: </b>" . $this->GetError() . "<br/>";
            $html .= "<b>MySQL Errno: </b>" . $this->GetErrno() . "<br/>";
            $html .= "<b>MySQL Message: </b>" . $message;
            echo "<div style='border:1px dotted #ccc; padding:5px; font-size:12px; clear:both;width:100%;height:auto;'>" . $html . "</div>";
            return false;
        }
        else {
            return false;
        }
    }

    final static public function GetObject($configs = array("hostname" => "", "database" => ""))
    {
        $db = $configs["hostname"] . $configs["database"];

        if (!isset(self::$mysqlobj[$db])) {
            if (!is_array($configs)) {
                $this->DisplayError("The configuration file is not an array");
            }

            $C = "db_mysqli";
            self::$mysqlobj[$db] = new $C($configs);
        }

        return self::$mysqlobj[$db];
    }

    public function ping()
    {
        if (!@mysqli_ping($this->link)) {
            @mysqli_close($this->link);
            $this->connect();
        }
    }

    public function close()
    {
        $this->link->close();
    }

    public function GetVersion($version = false)
    {
        $mysql_version = $this->link->server_info;
        $mysql_version = explode(".", trim($mysql_version));

        if ($version) {
            return $mysql_version[0] . "." . $mysql_version[1];
        }
        else {
            return $mysql_version[0] . "." . $mysql_version[1] . "." . $mysql_version[2];
        }
    }

    public function GetError()
    {
        return $this->link->error;
    }

    public function GetErrno()
    {
        return $this->link->errno;
    }

    public function insert_id()
    {
        return $this->link->insert_id;
    }

    public function execute($sql)
    {
        $this->ping($sql);
        ($this->lastresult = $this->link->query($sql)) || $this->displayerror($sql);
        $this->querycount++;

        if ($this->config["debug_log"]) {
            $text = "\n【sql】\n" . $sql;
            $text .= "\n【执行结果】\n" . var_export($this->lastresult, 1);
            file_put_contents($this->mysql_debug_file, $text, FILE_APPEND);
        }

        return $this->lastresult;
    }

    public function free_result()
    {
        if ($this->lastresult) {
            $this->lastresult->free();
        }
    }

    public function table_exists($table)
    {
        $tables = $this->list_tables();
        return in_array($table, $tables) ? 1 : 0;
    }

    public function list_tables($str = "")
    {
        $tables = array();
        $sql = "";

        if (!empty($str)) {
            $sql = " WHERE Tables_in_" . $this->config["database"] . " LIKE '" . $this->config["tablepre"] . $str . "%'";
        }

        $this->execute("SHOW TABLES" . $sql);

        if ($this->lastresult) {
            while ($r = mysqli_fetch_assoc($this->lastresult)) {
                $tables[] = $r["Tables_in_" . $this->config["database"]];
            }
        }

        return $tables;
    }

    public function num_rows($lastresult)
    {
        $lastresult->num_rows;
    }

    public function num_count($lastresult)
    {
        $data = $this->get_one($lastresult, MYSQLI_NUM);
        return $data[0];
    }

    public function affected_rows()
    {
        return $this->link->affected_rows;
    }

    final public function get_one($lastresult = NULL, $type = 1)
    {
        if (!$type) {
            $type = 1;
        }

        $type = intval($type);

        if (gettype($lastresult) === "object") {
            $list = $lastresult->fetch_array($type);
        }
        else {
            if (!$this->lastresult) {
                return array();
            }

            $list = $this->lastresult->fetch_array($type);
        }

        $this->free_result();
        return $list;
    }

    final public function get_fetch_type($type = 1, $key = "")
    {
        if (gettype($this->lastresult) !== "object") {
            $this->free_result();
            return false;
        }

        $datalist = $data = array();

        if (!$key) {
            while ($data = $this->lastresult->fetch_array($type)) {
                $datalist[] = $data;
            }
        }
        else {
            while ($data = $this->lastresult->fetch_array($type)) {
                $datalist[$data[$key]] = $data;
            }
        }

        $this->free_result();
        return $datalist;
    }

    final public function sqls($where, $font = " AND ", $op = "=")
    {
        if (is_array($where)) {
            $sql = "";

            foreach ($where as $key => $val ) {
                $sql .= ($sql ? " $font `$key` $op '$val' " : " `$key` $op '$val'");
            }

            return $sql;
        }
        else {
            return $where;
        }
    }

    final public function sql_begin()
    {
        if ($this->config["debug_log"]) {
            $text = "\nsql_begin开始\n";
            file_put_contents($this->mysql_debug_file, $text, FILE_APPEND);
        }

        $this->link->autocommit(false);
    }

    final public function sql_commit()
    {
        if ($this->config["debug_log"]) {
            $text = "\nsql_commit开始\n";
            file_put_contents($this->mysql_debug_file, $text, FILE_APPEND);
        }

        $this->link->commit();
        $this->link->autocommit(true);
    }

    final public function sql_rollback()
    {
        if ($this->config["debug_log"]) {
            $text = "\nsql_rollback开始\n";
            file_put_contents($this->mysql_debug_file, $text, FILE_APPEND);
        }

        $this->link->rollback();
        $this->link->autocommit(true);
    }
}


