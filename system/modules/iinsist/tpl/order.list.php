<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/global.css" type="text/css" />
<link rel="stylesheet" href="<?php echo G_GLOBAL_STYLE; ?>/global/css/style.css" type="text/css" />
<style>
tbody tr{ line-height:30px; height:30px;} 
</style>
</head>
<body>
<div class="header lr10">
    <?php echo headerment( $ment ); ?>
    <span class="lr10"> </span><span class="lr10"> </span>
    <form action="" method="post" style="display:inline-block; ">
    <select name="paixu">
        <option value="time1" <?php echo $paixu == "time1" ? 'selected' : ''; ?> > 按购买时间倒序 </option>
        <option value="time2" <?php echo $paixu == "time2" ? 'selected' : ''; ?>> 按购买时间正序 </option>
        <option value="money1" <?php echo $paixu == "money1" ? 'selected' : ''; ?>> 按购买总价倒序 </option>
        <option value="money2" <?php echo $paixu == "money2" ? 'selected' : ''; ?>> 按购买总价正序 </option>
    </select>    
    <input type="submit" name="paixu_submit" class="button"value=" 排序 "  />
    <input type="button" class="button" onclick="export_excel();" value=" 导出到EXCEL " />
    </form>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">订单号</th>
            <th align="center">商品标题</th>
            <th align="center">购买用户</th>
            <th align="center">购买总价</th>
            <th align="center">购买日期</th>
            <th align="center">中奖</th>
            <th align="center">订单状态</th>
            <th align="center">管理</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( (array)$recordlist as $v ) { ?>
        <tr>
            <td align="center"> <?php echo $v['ocode']; ?> <?php echo $v["code_tmp"] ? " <font color='#ff0000'>[多]</font>" : ''; ?> </td>
            <td align="center">
            <a target="_blank" href="<?php echo WEB_PATH . "/cgoods/" . $v["ogid"]; ?>"> 第(<?php echo $v["oqishu"]; ?>)期<?php echo _strcut( _unser( $v["og_title"], "g_title" ), 0, 25 ); ?> </a>
            </td>
             <td align="center"> <?php echo get_user_name( $v["ouid"] ); ?> </td>
            <td align="center">￥<?php echo $v["omoney"]; ?>元</td>
            <td align="center"><?php echo date( "Y-m-d H:i:s", $v["otime"] ); ?></td>
            <td align="center"><?php echo $v["ofstatus_txt"]; ?></td>
            <td align="center"><?php echo $v["status_txt"]; ?></td>
            <td align="center"><a href="<?php echo G_MODULE_PATH . "/order/detail/" . $v["oid"]; ?>">详细</a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div id="pages"><ul>共 <?php echo $page; ?> </ul></div>
</div>
<script type="text/javascript">
/* 导出excel */
function export_excel()
{
    var url = "<?php echo $url . '/' .$where . '/export'; ?>";
    location.href = url;
}
</script>
</body>
</html>