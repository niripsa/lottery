<?php

class auth_model extends model
{
    public function get_group($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_admin_group`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_group_num($where)
    {
        $sql = "select count(*) as num from `@#_admin_group` ";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function get_group_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_admin_group` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function group_add($data = array())
    {
        return $this->Insert("admin_group", $data);
    }

    public function group_save($where, $data = array())
    {
        return $this->Update("admin_group", $data, $where);
    }

    public function group_del($where)
    {
        $sql = "DELETE FROM `@#_admin_group` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_admin($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_admin`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_admin_num($where)
    {
        $sql = "select count(*) as num from `@#_admin` ";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function get_admin_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_admin` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function admin_add($data = array())
    {
        return $this->Insert("admin", $data);
    }

    public function admin_save($where, $data = array())
    {
        return $this->Update("admin", $data, $where);
    }

    public function admin_del($where)
    {
        $sql = "DELETE FROM `@#_admin` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function get_auth($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("ments", $where, $field, $order, $num);
        return $res;
    }

    public function get_auth_num($where)
    {
        $res = $this->data_num("ments", $where);
        return $res;
    }

    public function get_auth_one($where, $field = "*")
    {
        $res = $this->data_one("ments", $where, $field);
        return $res;
    }

    public function auth_add($data = array())
    {
        return $this->Insert("ments", $data);
    }

    public function auth_save($where, $data = array())
    {
        return $this->Update("ments", $data, $where);
    }

    public function auth_del($where)
    {
        return $this->data_del("ments", $where);
    }

    public function auth_parent($n = 1)
    {
        $data = $this->get_auth("pid=0", "id,name,m,c,a,d,url");

        if (2 <= $n) {
            foreach ($data as &$row ) {
                $tmp = $this->get_auth("pid=" . $row["id"], "id,name,m,c,a,d,url");

                if ($n == 3) {
                    foreach ($tmp as &$r ) {
                        $r["sub_data"] = $this->get_auth("pid=" . $r["id"], "id,name,m,c,a,d,url");
                    }
                }

                $row["sub_data"] = $tmp;
            }
        }

        return $data;
    }
}


?>
