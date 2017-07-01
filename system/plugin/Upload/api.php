<?php 

/**
 *  官方上传文件或者图片插件
 *  根据传进来的 Action 操作
 *  <战线> booobusy@gmail.com
 *  2015年2月27日17:22:49
 *  
 */
 
defined("G_EXECMODE") or die("I'm sorry, you don't have the access");

//要操作的函数
$action = isset($_GET['action']) ?  $_GET['action'] : false;

switch($action){
    case "view":
        return upload_view();
    break;
    case "upload":
        return upload_upload();
    break;  
    case "del":
        return upload_del();
    break;
    case "thumb":
        return upload_thumb();
    break;  
}


/**********************/
/***    控制器函数  ***/
/**********************/

//上传视图
function upload_view(){
    
    /* 
        GetUploadify('<?php echo WEB_PATH; ?>','uploadify','缩略图上传','photo',1,'imagetext')
    */ 
    $ini = System::load_sys_config("upload");
    $title = isset($_GET['title']) ? htmlspecialchars($_GET['title']) : 'uploadphoto';  //标题 
    $frame = isset($_GET['frame']) ? htmlspecialchars($_GET['frame']) : 'uploadify';    //iframe id
    $num   = isset($_GET['num'])   ? abs(intval($_GET['num']))        : 1;              //上传数目
    $dir   = isset($_GET['dir'])   ? htmlspecialchars($_GET['dir'])    : "";            //回调JS函数
    $input = isset($_GET['input']) ? htmlspecialchars($_GET['input']) : "";             //保存的input的ID
    $func  = isset($_GET['func'])  ? htmlspecialchars($_GET['func'])  : false;          //回调JS函数
    
    
    include "upfile.class.php";
    $upload = new upfile();
    
    $size = $ini['upimgsize']; 
    $size_str = $upload->GetSizeStr($size);
    $uptype = "*.".str_ireplace(",",";*.",$ini['up_image_type']);
    $desc = "shuo ming ....";
    
    System::load_sys_class("SystemAction","sys","no");
    System::load_app_class("admin",G_ADMIN_DIR,"no");
    $admincheck = admin::StaticCheckAdminInfo() ? 1 : 0;
    
    include "uploadify.tpl.php";
}

//开始上传
function upload_upload(){

    System::load_sys_class('model','sys','no'); 
    include "upfile.class.php";
    $upload = new upfile(); 
    $dir  = isset($_GET['save']) ? $_GET['save'] : NULL;    
    $upload->UploadPhoto($dir); 

}


//删除
function upload_del(){

    
    $filename=isset($_POST['filename']) ? $_POST['filename'] : exit(0);
    $filename=str_replace('../','',$filename);
    $filename=trim($filename,'.');
    $filename=trim($filename,'/');  
    $filename = str_ireplace(G_UPLOAD_PATH."/", "", $filename);
    
    if(!file_exists(G_UPLOAD.$filename)){
        echo 0; exit;
    }else{
        $time = filemtime(G_UPLOAD.$filename);      
    }

    System::load_sys_class("model","sys","no");
    $uid  = System::load_app_class("UserCheck","common") -> UserInfo['uid'];    
    $db = System::load_app_model("files","common"); 
    
    if(unlink(G_UPLOAD.$filename)){
        $db->file_del($uid,$filename,$time);
        echo 1; 
    }else{
        echo 0;
    }       
    exit;
    /*
    if(!empty($filename)){
        $filename=G_UPLOAD.$filename;           
        $size=@getimagesize($filename);         
        $filetype=explode('/',$size['mime']);           
        if($filetype[0]!='image'){
            return false;
            exit;
        }       
        unlink($filename);
        exit;
    }    
    
    */
}


//缩略图
function  upload_thumb(){
    System::load_sys_class('model','sys','no'); 
    
    include "upfile.class.php";
    $upload = new upfile();

    $type = isset($_GET['type']) ? $_GET['type'] : NULL;
    $upload->PhotoThumbs($type);
}