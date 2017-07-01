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
<div class="lr10" style="line-height:30px;color:#3c8dbc">
    <b>商家列表:</b> <?php echo $select_where; ?> &nbsp;&nbsp;&nbsp; 共找到 <?php echo $total; ?> 个会员
</div>
<div class="table-list lr10">        
  <!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">UID</th>
            <th align="center">用户名</th>
            <th align="center">邮箱</th>
            <th align="center">手机</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
    <?php if ( is_array( $members ) && ( 0 < count( $members ) ) ) { ?>
    <?php foreach ( $members as $v ) { ?>
        <tr>
            <td align="center"> <?php echo $v["uid"]; ?> </td>
            <td align="center"><a href="<?php echo WEB_PATH . '/uname/' . idjia($v["uid"]); ?>" target="_blank"><?php echo $v["username"]; ?></a></td>
            <td align="center"> <?php echo $v["email"]; ?> 
            <?php if ($v["emailcode"] == 1) { ?>
            <span style="color:#0c0">√</span>
            <?php } else { ?>
            <span style="color:red">×</span>
            <?php } ?>
            </td>   
            <td align="center"> <?php echo $v["mobile"]; ?> 
            <?php if ($v["mobilecode"] == 1) { ?>
            <span style="color:#0c0">√</span>
            <?php } else { ?>
            <span style="color:red">×</span>
            <?php } ?>
            </td>         
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
    </tbody>
</table>
</div><!--table-list end-->

<div id="pages" style="margin:10px 10px">       
    <ul>
        <li>共 <?php echo $total; ?> 条</li>
        <?php echo $page; ?>
    </ul>
</div>
<script>
</script>
</body>
</html> 