<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ment ); ?>
</div>
<div class="bk10"></div>
<div class="header-data lr10">
    <form name="myform" action="" method="post">
    搜索条件：
    <select name="sousuo">
        <option <?php echo $data["sousuo"] == "id" ? 'selected' : ''; ?> value="id">会员uid</option>
        <option <?php echo $data["sousuo"] == "nickname" ? 'selected' : ''; ?> value="nickname">会员昵称</option>
        <option <?php echo $data["sousuo"] == "email" ? 'selected' : ''; ?> value="email">会员邮箱</option>
        <option <?php echo $data["sousuo"] == "mobile" ? 'selected' : ''; ?> value="mobile">会员手机</option>
    </select>
    <input type="text" name="content" class="input-text" value="<?php echo $data["content"]; ?>">
    <input type="submit" class="button" name="submit" value="确认搜索" >
    </form>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
<form name="myform" action="#" method="post">
    <table width="100%" cellspacing="0">
    <?php if ( ! empty( $members ) ) { ?>
    <thead>
        <tr>
            <th align="center">UID</th>
            <th align="center">用户名</th>
            <th align="center">邮箱</th>
            <th align="center">手机</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <?php foreach ( $members as $v ) { ?>
    <tr>
        <td align="center"> <?php echo $v["uid"]; ?> </td>
        <td align="center"> <?php echo $v["username"]; ?> </td>
        <td align="center"> <?php echo $v["email"]; ?> </td>
        <td align="center"> <?php echo $v["mobile"]; ?> </td>
            <td align="center">
            <?php if ($v["status"] == "-1") { ?>
                [<a href="<?php echo G_MODULE_PATH; ?>/shop/huifu/<?php echo $v["uid"]; ?>">恢复</a>]
                [<a href="<?php echo G_MODULE_PATH; ?>/shop/del_true/<?php echo $v["uid"]; ?>" onClick="return confirm('是否真的删除！');">删除</a>]
            <?php } else { ?>
                [<a href="<?php echo G_MODULE_PATH; ?>/shop/modify/<?php echo $v["uid"]; ?>">改</a>]
                [<a href="<?php echo G_MODULE_PATH; ?>/shop/del/<?php echo $v["uid"]; ?>" onClick="return confirm('是否真的删除！');">删</a>]
            <?php } ?>
            </td> 
    </tr>
    <?php } } ?>
    </table>
</form>
</div><!--table-list end-->
<script>
function upImage()
{
    return document.getElementById('imgfield').click();
}
</script>
</body>
</html> 