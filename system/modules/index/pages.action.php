<?php

class pages extends SystemAction
{
    public function demo()
    {
        echo G_PARAM_URL;
    }

    public function init()
    {
        echo G_PARAM_URL;
        $db = System::load_sys_class("model");
        $page = System::load_sys_class("page");
        $page->config(5000, 10);
        $sql = "SELECT * FROM `@#_page_demo` WHERE 1 $page->setlimit(1)";
        $list = $db->GetList($sql);
        echo "<pre>";

        foreach ($list as $v ) {
            echo $v["name"];
            echo "<br>";
        }

        echo $page->show("two", true);
    }
}


?>
