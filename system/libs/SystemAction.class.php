<?php
class SystemAction
{
    public $view;
    private $routes;

    public function HookConstruct()
    {
        $this->view = System::load_sys_class("view");
        return $this;
    }

    public function HookDestruct()
    {
        $this->view->commit();
    }

    final public function __call($name = NULL, $arg = NULL)
    {
        $this->SendStatus(404);
    }

    final public function HookSetRoutes($routes)
    {
        $this->routes = $routes;
    }

    final protected function segment($n = 1)
    {
        if (!isset($this->routes[$n])) {
            return false;
        }
        else if (strpos($this->routes[$n], "&p=") !== false) {
            return "";
        }
        else {
            return $this->routes[$n];
        }
    }

    final protected function segment_array()
    {
        return $this->routes;
    }

    final protected function segment_url()
    {
        return G_PARAM_URL;
    }

    final static protected function SendMsgJson($key = "", $val = "", $th = 0)
    {
        _sendmsgjson($key, $val, $th);
    }

    final static public function SendStatus($status = 404, $data = "")
    {
        _sendstatus($status, $data);
    }
}