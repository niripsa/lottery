<?php


//插件操作
$action       = isset($_GET['action']) ? basename($_GET['action']) : _SendStatus(404);   //选择的 action
$action       = "Plugin_Template_".$action;



function Plugin_Template_edit(){
    _PluginCheckAdmin();
    include "template_plugin.model.php";
    $templateobj =  new template_plugin_model();
    $type   =   $_GET['type'];
    $temp_config=system::load_sys_config("view");
    $temp_template=$temp_config['templates'];
    $template=$temp_template[$type];
    if(!isset($template))_message("没有这个模板");
    if(!is_writable(G_CONFIG.'view.inc.php')) _message('Please chmod  templates  to 0777 !');
    if(isset($_POST['dosubmit'])){
        $data=_post();
        $template['name']=$data['name'];
        $template['html']=$data['html'];
        $template['status']=$data['status'];
        $res=$templateobj->edit_template_config("templates",$template,$type);
        if($res){
            echo "<script>
                alert('修改成功');
                window.location.href='".WEB_PATH.'/plugin=1&api=Manager&action=showview'."';
                </script>";
        }
    }
    include "tpl/edit.php";
}

function Plugin_Template_install(){
    _PluginCheckAdmin();
   include "template_plugin.model.php";
    $path=G_CACHES.'caches_upfile';
    //创建缓存文件夹
    if(!file_exists($path)) {
        @mkdir($path);
    }
    file_put_contents($path.DIRECTORY_SEPARATOR.$_GET['dir'].".zip",file_get_contents("http://www.yungoucms.com/plugin/api.php?action=plugin_installtemplate&name=".$_GET['name']));
    include "lib/pclzip.class.php";
    //保存到本地地址
    $zip_path = G_CACHES.'caches_upfile'.DIRECTORY_SEPARATOR.$_GET['dir'].".zip";
    //解压路径
    $zip_source_path = G_CACHES.'caches_upfile'.DIRECTORY_SEPARATOR.basename($_GET['dir'],".zip");
    //解压缩
    $archive = new PclZip($zip_path);

    if($archive->extract(PCLZIP_OPT_PATH, $zip_source_path, PCLZIP_OPT_REPLACE_NEWER) == 0) {
        die("Error : ".$archive->errorInfo(true));
    }

    //拷贝文件夹到根目录
    $copy_from = $zip_source_path.DIRECTORY_SEPARATOR;
    $copy_to = G_APP_PATH."statics/templates/";
    $copyfailnum = 0;
    copydir($copy_from, $copy_to,'cover');
    $templateobj =  new template_plugin_model();
    $temp_config=system::load_sys_config("view");
    $temp_config['templates'][$_GET['dir']]=array(
        'name' => $_GET['name'],
        'type' => $_GET['type'],
        'dir' => $_GET['dir'],
        'html' => 'html',
        'author' => '韬龙',
        'dosubmit' => $_GET['author'],
        'version' => $_GET['version'],
        'desc' => $_GET['desc'],
        'kid' => $_GET['id'],
        'status' => '1',
    );
    $templateobj->write_template_config($temp_config);
    deletedir($copy_from);
    //检查文件操作权限，是否复制成功
    if($copyfailnum > 0) {
        die("模板复制失败");
    }else{
       echo "<script>
                alert('安装成功');
                window.location.href='".WEB_PATH.'/plugin=1&api=Manager&action=showview'."';
                </script>";

    }

}

 function copydir($dirfrom, $dirto, $cover='') {
    //如果遇到同名文件无法复制，则直接退出
    if(is_file($dirto)){
        die("同名文件无法复制".$dirto);
    }
    //如果目录不存在，则建立之
    if(!file_exists($dirto)){
        mkdir($dirto);
    }
    $handle = opendir($dirfrom); //打开当前目录
    //循环读取文件
    while(false !== ($file = readdir($handle))) {
        if($file != '.' && $file != '..'){ //排除"."和"."
            //生成源文件名
            $filefrom = $dirfrom.$file;
            //生成目标文件名
            $fileto = $dirto.$file;
            if(is_dir($filefrom)){ //如果是子目录，则进行递归操作
                copydir($filefrom.DIRECTORY_SEPARATOR, $fileto.DIRECTORY_SEPARATOR,$cover);
            } else { //如果是文件，则直接用copy函数复制
                if(!empty($cover)) {
                    if(!copy($filefrom, $fileto)) {
                        $copyfailnum++;
                        echo 'copy'.$filefrom.'to'.$fileto.'failed'."<br />";
                    }else{
                        //copy 成功
                    }
                } else {
                    if(fileext($fileto) == 'html' && file_exists($fileto)) {
                        //文件==html 不copy
                    } else {
                        if(!copy($filefrom, $fileto)) {
                            $copyfailnum++;
                            echo 'copy'.$filefrom.'to'.$fileto.'failed'."<br />";
                        }else{
                            //copy 成功
                        }
                    }
                }
            }
        }
    }
}

 function deletedir($dirname){
    $result = false;
    if(! is_dir($dirname)){
        echo " $dirname is not a dir!";
        exit(0);
    }
    $handle = opendir($dirname); //打开目录
    while(($file = readdir($handle)) !== false) {
        if($file != '.' && $file != '..'){ //排除"."和"."
            $dir = $dirname.DIRECTORY_SEPARATOR.$file;
            //$dir是目录时递归调用deletedir,是文件则直接删除
            is_dir($dir) ? deletedir($dir) : unlink($dir);
        }
    }
    closedir($handle);
    $result = rmdir($dirname) ? true : false;
    return $result;
}
if(!function_exists($action)){
    _SendStatus(404);
}


$action();

