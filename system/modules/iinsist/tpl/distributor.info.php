<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css">
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css">
<style type="text/css">
tr{height:40px;line-height:40px}
.dingdan_content{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}
.dingdan_content li{ float:left;width:310px;}
.dingdan_content_user{width:650px;border:1px solid #d5dfe8;background:#eef3f7;float:left; padding:20px;}
.dingdan_content_user li{ line-height:25px;}

.api_b{width:80px; display:inline-block;font-weight:normal}
.yun_ma{ word-break:break-all; width:200px; background:#fff; overflow:auto; height:100px; border:5px solid #09F; padding:5px;}
</style>
</head>
<body>
<div class="header-title lr10">
    <b>分销商详情</b>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
    <div class="dingdan_content">
        <h3 style="clear:both;display:block; line-height:30px;"> <?php echo $goods["g_title"]; ?> </h3>
        <li> 用户名：  <?php echo $info['username']; ?> </li><br />
        <li> 一层分销商：<?php echo $parent_info[1]['username']; ?> </li><br />
        <li> 二层分销商：<?php echo $parent_info[2]['username']; ?> </li><br />
        <li> 三层分销商：<?php echo $parent_info[3]['username']; ?> </li>
        <input type="button" class="button" value="返回" onclick="history.back( -1 );" />
    </div>
</div>
</body>
</html>