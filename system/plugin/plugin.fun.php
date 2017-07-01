<?php

function _PluginRouteIndex()
{
    require G_PLUGIN . "./routes.php";
    $path = trim($_SERVER["plugin_path"], "/");
    $path = preg_replace("/^" . basename(__FILE__) . "\//i", "", $path);

    if (isset($routes[$path])) {
        $_GET += $routes[$path];
    }

    unset($path);
}

function _PluginGetOne( $pname )
{
    $package = _PluginGetAll();

    if ( isset( $package[$pname] ) ) 
    {
        return $package[$pname];
    }

    return false;
}

function _PluginGetAll()
{
    static $inc = false;

    if ($inc) {
        return $inc;
    }
    else {
        $inc = include G_PLUGIN . "package.php";

        foreach ($inc as $k => $v ) {
            $inc[$k]["Photo"] = G_UPLOAD_PATH . "/banner/" . basename($v["Photo"]);
        }

        return $inc;
    }
}

function _PluginIncludeHTML($name)
{
    System::load_sys_class("view", "sys");
    include view::includes($name);
}

function _PluginCheckAdmin($bool = false)
{
    System::load_app_class("admin", G_ADMIN_DIR, "no");

    if ($bool) {
        return admin::StaticCheckAdminInfo() ? 1 : 0;
    }

    admin::StaticCheckAdminInfo() ? 1 : _SendStatus(404);
}

function _PluginUpdatePackage($pname = NULL, $data = NULL)
{
    ($package = _plugingetone($pname)) || _message("not found the plugin.");
    $keys = array("Name", "Status", "Action", "Author", "Email", "Version", "Index", "Install", "Uninstall", "Photo", "Desc");

    foreach ($data as $k => $v ) {
        if (!in_array($k, $keys)) {
            unset($data[$k]);
        }
    }

    $package = array_merge($package, $data);
    $packages = &_plugingetall();
    $packages[$pname] = $package;
    $html = "<?php " . PHP_EOL;
    $html .= "\n\t/** \n\t *\t插件配置管理器\n\t *\t插件状态分为: \n\t *  \n\t *\t0.  未安装\n\t *\t1.\t使用中\n\t *\t2.\t已停止\n\t *  3.  已卸载\n\t *\t\n\t */" . PHP_EOL . PHP_EOL;
    $html .= "return " . var_export($packages, true) . ";";
    file_put_contents(G_PLUGIN . "package.php", $html);
}

function _PluginAddPackage($pname = NULL, $data = NULL)
{
    if (_plugingetone($pname)) {
        return false;
    }

    $keys = array("Name", "Status", "Action", "Author", "Email", "Version", "Index", "Install", "Uninstall", "Photo", "Desc");

    foreach ($data as $k => $v ) {
        if (!in_array($k, $keys)) {
            unset($data[$k]);
        }
    }

    $packages = &_plugingetall();
    $packages[$pname] = $data;
    $html = "<?php " . PHP_EOL;
    $html .= "\n\t/** \n\t *\t插件配置管理器\n\t *\t插件状态分为: \n\t *  \n\t *\t0.  未安装\n\t *\t1.\t使用中\n\t *\t2.\t已停止\n\t *  3.  已卸载\n\t *\t\n\t */" . PHP_EOL . PHP_EOL;
    $html .= "return " . var_export($packages, true) . ";";
    file_put_contents(G_PLUGIN . "package.php", $html);
    return true;
}

function plugin_shudtown_check()
{
    if (!defined("PLUGIN_HASH_KEY")) {
        return NULL;
    }
}


