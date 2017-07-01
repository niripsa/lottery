<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style>
table th{ border-bottom:1px solid #eee; font-size:12px; font-weight:100; text-align:right; width:200px;}
table td{ padding-left:10px;}
input.button{ display:inline-block}
</style>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ments ); ?>
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
        <th width="100px" class="tac">UID</th>
        <th width="100px" class="tac">用户名</th>
        <th width="100px" class="tac">邮箱</th>
        <th width="100px" class="tac">手机</th>
        <th width="100px" class="tac">账户金额</th>
        <th width="100px" class="tac">邮箱认证</th>
        <th width="100px" class="tac">手机认证</th>
        <th width="100px" class="tac">管理</th>
        </tr>
    </thead>
    <?php foreach ( $members as $v ) { ?>
    <tr>
        <td align="center"> <?php echo $v["uid"]; ?> </td>
        <td align="center"> <?php echo $v["username"]; ?> </td>
        <td align="center"> <?php echo $v["email"]; ?> </td>
        <td align="center"> <?php echo $v["mobile"]; ?> </td>
        <td align="center"> <?php echo $v["money"]; ?> </td>
        <td align="center">
            <?php if ( $v["emailcode"] == 1 ) { ?>
                <span style="color:red">已认证</span>
            <?php } else { ?>
                未认证
            <?php } ?>
        </td>
        <td align="center">
            <?php if ( $v["mobilecode"] == 1 ) { ?>
                <span style="color:red">已认证</span>
            <?php } else { ?>
                未认证
            <?php } ?>
        </td>
        <td align="center"> 
            [<a href="<?php echo G_MODULE_PATH . '/index/manage/' . $v["uid"]; ?>" target="_blank">代管</a>]
            [<a href="<?php echo G_MODULE_PATH . '/member/modify/' . $v["uid"]; ?>">改</a>]
            [<a href="<?php echo G_MODULE_PATH . '/member/del/' . $v["uid"]; ?>" onclick="return confirm('是否真的删除！');">删</a>]
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