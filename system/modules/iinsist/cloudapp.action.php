<?php
System::load_app_class("admin", ROUTE_M, "no");
class cloudapp extends admin
{
    public function lists()
    {
        $this->view->tpl("cloudapp.list.tpl");
    }
}
?>
