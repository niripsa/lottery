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
    </select>    
    <input type="submit" name="paixu_submit" class="button"value=" 排序 "  />
    </form>
</div>
<div class="bk10"></div>
<div class="table-list lr10">
<!--start-->
  <table width="100%" cellspacing="0">
    <thead>
        <tr>
            <th align="center">订单号</th>
            <!-- <th align="center">中奖号码</th> -->
            <th align="center">购买彩票</th>
            <th align="center">购买用户</th>
            <th align="center">购买总价</th>
            <th align="center">购买日期</th>
            <th align="center">订单状态</th>
            <th align="center">奖励积分</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ( (array)$recordlist as $v ) { ?>
        <tr>
            <td align="center"> <?php echo $v['order_sn']; ?><?php echo $v["code_tmp"] ? " <font color='#ff0000'>[多]</font>" : ''; ?> 
            </td>
            <td align="center">
                第<?php echo $v["stage_no"];?>期： 买<?php echo $v["buy_content"];?>
            </td>
            <td align="center"> 
             <?php echo get_user_name( $v["user_id"] ); ?> 
            </td>
            <td align="center">￥<?php echo $v["buy_money"]; ?>元</td>
            <td align="center"><?php echo $v["buy_time"]; ?></td>
            <td align="center"><?php echo $v["status_txt"]; ?></td>
            <td align="center"><?php echo $v["award_points"];?></td>
        </tr>
    <?php } ?>
    </tbody>
</table>
<div id="pages"><ul>共 <?php echo $page; ?> </ul></div>
</div>
</body>
</html>