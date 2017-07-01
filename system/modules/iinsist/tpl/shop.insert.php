<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/jquery-1.8.3.min.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/child_area.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/global.js"></script>
    <script src="<?php echo G_GLOBAL_STYLE; ?>/global/js/from.js"></script>
    <script src="<?php echo G_PLUGIN_PATH; ?>/uploadify/api-uploadify.js" type="text/javascript"></script>
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
    <link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ments ); ?>
</div>
<div class="bk10"></div>
<div class="table-listx lr10">
<!--start-->
<form name="myform" action="" method="post" enctype="multipart/form-data">
    <table width="100%" cellspacing="0">
        <tr>
            <td width="120" align="right">昵称：</td>
            <td><input type="text" name="username" value="<?php echo $member["username"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">密码：</td>
            <td><input type="text" name="password" value="" class="input-text">(不填写默认为原密码)</td>
        </tr>
        <tr>
            <td width="120" align="right">邮箱：</td>
            <td><input type="text" name="email" value="<?php echo $member["email"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">手机：</td>
            <td><input type="text" name="mobile" value="<?php echo $member["mobile"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right">电话：</td>
            <td><input type="text" name="phone" value="<?php echo $member["phone"]; ?>" class="input-text"></td>
        </tr>
         <tr>
            <td width="120" align="right">地址：</td>
            <td><input type="text" name="address" value="<?php echo $member["address"]; ?>" class="input-text"></td>
        </tr>
        <tr>
            <td width="120" align="right"></td>
            <td>        
            <input type="submit" class="button" name="submit" value="提交" >
            </td>
        </tr>
</table>
</form>
</div><!--table-list end-->
<script>
function upImage(){
    return document.getElementById('imgfield').click();
}
</script>
</body>
</html> 