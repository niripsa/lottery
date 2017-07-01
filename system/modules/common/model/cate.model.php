<?php

class cate_model extends model
{
    private $model_arr;

    public function __construct()
    {
        parent::__construct();
        $this->model_arr = array(
            1 => array("modelid" => 1, "name" => "商品模型", "table" => "goods"),
            2 => array("modelid" => 2, "name" => "文章模型", "table" => "article"),
            3 => array("modelid" => 3, "name" => "网页模型", "table" => "web"),
            4 => array("modelid" => 4, "name" => "链接模型", "table" => "link")
            );
    }

    public function get_cate_list($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_cate`";
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

    public function get_cate_one($where = "", $field = "*")
    {
        $sql = "SELECT " . $field . " FROM `@#_cate`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $cate = $this->GetOne($sql);

        if (!$cate) {
            return false;
        }

        return $cate;
    }

    public function get_cate_name($where = "", $field = "name")
    {
        $sql = "SELECT " . $field . " FROM `@#_cate`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $cate = $this->GetOne($sql);

        if (!$cate) {
            return false;
        }

        return $cate[$field];
    }

    public function get_type($modelid = "", $key = "")
    {
        if (isset($modelid)) {
            if (!isset($key)) {
                return $this->type_arr[$modelid];
            }
            else {
                return $this->type_arr[$modelid][$key];
            }
        }
        else {
            return $this->type_arr;
        }
    }

    public function get_model($modelid = "", $key = "")
    {
        if (isset($modelid) && ($modelid != "")) {
            if (!isset($key)) {
                return $this->model_arr[$modelid];
            }
            else {
                return $this->model_arr[$modelid][$key];
            }
        }
        else {
            return $this->model_arr;
        }
    }

    public function get_model_key($key, $val, $field)
    {
        $res = false;

        foreach ($this->model_arr as $row ) {
            if ($row[$key] == $val) {
                $res = $row[$field];
            }
        }

        return $res;
    }

    public function add_cate($data)
    {
        return $this->Insert("cate", $data);
    }

    public function save_cate($data, $where)
    {
        return $this->Update("cate", $data, $where);
    }

    public function del_cate($where)
    {
        $sql = "DELETE FROM `@#_cate` WHERE" . $where;
        return $this->Delete($sql);
    }
}

System::load_sys_class("model", "sys", "no");

?>
